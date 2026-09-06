<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class Phase7AiSuggestionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'apprenant'], ['display_name' => 'Apprenant']);
        Role::firstOrCreate(['name' => 'tuteur'], ['display_name' => 'Tuteur']);
        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
    }

    public function test_guest_cannot_access_ai_suggestion(): void
    {
        $response = $this->postJson(route('demandes.ai-suggest'), [
            'matiere' => 'Mathématiques',
            'niveau' => 'Lycée',
        ]);

        $response->assertStatus(401);
    }

    public function test_ai_suggestion_requires_matiere(): void
    {
        $user = User::factory()->create();
        $user->addRole('apprenant');

        $response = $this->actingAs($user)->postJson(route('demandes.ai-suggest'), [
            'niveau' => 'Lycée',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('matiere');
    }

    public function test_authenticated_apprenant_receives_ai_suggestion(): void
    {
        $user = User::factory()->create();
        $user->addRole('apprenant');

        $response = $this->actingAs($user)->postJson(route('demandes.ai-suggest'), [
            'matiere' => 'Mathématiques',
            'niveau' => 'Lycée',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'suggestion',
                'source',
            ])
            ->assertJson([
                'success' => true,
            ]);

        $this->assertNotEmpty($response->json('suggestion'));
        $this->assertStringContainsString('Mathématiques', $response->json('suggestion'));
    }

    public function test_ai_suggestion_with_openai_api_mock(): void
    {
        Config::set('services.ai.openai_api_key', 'sk-test-openai-mock-key');
        Config::set('services.ai.provider', 'openai');

        Http::fake([
            'https://api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Je recherche un tuteur certifié en Physique pour préparer le concours national.',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $user = User::factory()->create();
        $user->addRole('apprenant');

        $response = $this->actingAs($user)->postJson(route('demandes.ai-suggest'), [
            'matiere' => 'Physique',
            'niveau' => 'Supérieur',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'suggestion' => 'Je recherche un tuteur certifié en Physique pour préparer le concours national.',
                'source' => 'openai',
            ]);
    }

    public function test_ai_suggestion_with_gemini_api_mock(): void
    {
        Config::set('services.ai.openai_api_key', null);
        Config::set('services.ai.gemini_api_key', 'AIza-test-gemini-mock-key');
        Config::set('services.ai.provider', 'gemini');

        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Besoin d\'un professeur en Informatique pour perfectionner mes compétences en algorithmique.'],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $user = User::factory()->create();
        $user->addRole('apprenant');

        $response = $this->actingAs($user)->postJson(route('demandes.ai-suggest'), [
            'matiere' => 'Informatique',
            'niveau' => 'Lycée',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'suggestion' => 'Besoin d\'un professeur en Informatique pour perfectionner mes compétences en algorithmique.',
                'source' => 'gemini',
            ]);
    }
}
