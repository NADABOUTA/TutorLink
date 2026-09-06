<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiSuggestionController extends Controller
{
    /**
     * Suggère une description personnalisée pour une demande de cours via IA (OpenAI, Gemini ou Fallback intelligent).
     */
    public function suggest(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'matiere'  => ['required', 'string', 'min:2', 'max:100'],
            'niveau'   => ['nullable', 'string', 'max:100'],
            'contexte' => ['nullable', 'string', 'max:250'],
        ], [
            'matiere.required' => 'Veuillez renseigner la matière pour générer une suggestion.',
            'matiere.min'      => 'La matière doit contenir au moins 2 caractères.',
        ]);

        $matiere  = trim($validated['matiere']);
        $niveau   = !empty($validated['niveau']) ? trim($validated['niveau']) : 'tous niveaux';
        $contexte = !empty($validated['contexte']) ? trim($validated['contexte']) : '';

        $aiConfig = config('services.ai');
        $openaiKey = $aiConfig['openai_api_key'] ?? env('OPENAI_API_KEY');
        $geminiKey = $aiConfig['gemini_api_key'] ?? env('GEMINI_API_KEY');
        $provider  = strtolower($aiConfig['provider'] ?? env('AI_PROVIDER', 'openai'));

        $prompt = "Tu es un conseiller pédagogique expert pour la plateforme de tutorat TutorLink au Maroc.\n"
                . "Rédige une description de demande de cours claire, motivante, réaliste et soignée en français pour un apprenant qui cherche un tuteur particulier.\n"
                . "Matière : {$matiere}\n"
                . "Niveau scolaire : {$niveau}\n"
                . ($contexte ? "Détails particuliers souhaités : {$contexte}\n" : "")
                . "Consignes strictes :\n"
                . "- Rédige à la première personne (\"Je recherche...\", \"Mon objectif est...\").\n"
                . "- Longueur : 3 à 4 phrases fluides et bien formulées.\n"
                . "- Évoque les notions clés, les méthodes de travail et le rythme souhaité (1 à 2 séances par semaine).\n"
                . "- Pas de formule de politesse introductive (\"Bonjour\"), pas de signature, pas de coordonnées factices.\n"
                . "- Renvoie uniquement le texte brut de la description.";

        // 1. Appel OpenAI si configuré
        if ($openaiKey && ($provider === 'openai' || !$geminiKey)) {
            try {
                $response = Http::withToken($openaiKey)
                    ->timeout(12)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model'       => 'gpt-4o-mini',
                        'messages'    => [
                            ['role' => 'system', 'content' => 'Tu es un assistant pédagogique concis et bienveillant.'],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'temperature' => 0.7,
                        'max_tokens'  => 250,
                    ]);

                if ($response->successful()) {
                    $content = trim($response->json('choices.0.message.content') ?? '');
                    if (!empty($content)) {
                        return response()->json([
                            'success'    => true,
                            'suggestion' => $content,
                            'source'     => 'openai',
                        ]);
                    }
                } else {
                    Log::warning('OpenAI Suggestion API call non-200: ' . $response->body());
                }
            } catch (\Throwable $e) {
                Log::warning('Erreur connexion OpenAI : ' . $e->getMessage());
            }
        }

        // 2. Appel Gemini si configuré
        if ($geminiKey && ($provider === 'gemini' || !$openaiKey)) {
            try {
                $response = Http::timeout(12)
                    ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$geminiKey}", [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt],
                                ],
                            ],
                        ],
                    ]);

                if ($response->successful()) {
                    $content = trim($response->json('candidates.0.content.parts.0.text') ?? '');
                    if (!empty($content)) {
                        return response()->json([
                            'success'    => true,
                            'suggestion' => $content,
                            'source'     => 'gemini',
                        ]);
                    }
                } else {
                    Log::warning('Gemini Suggestion API call non-200: ' . $response->body());
                }
            } catch (\Throwable $e) {
                Log::warning('Erreur connexion Gemini : ' . $e->getMessage());
            }
        }

        // 3. Fallback intelligent haute fidélité (Génération locale dynamique sans dépendance externe)
        $suggestion = $this->generateLocalSuggestion($matiere, $niveau, $contexte);

        return response()->json([
            'success'    => true,
            'suggestion' => $suggestion,
            'source'     => 'template_ia',
        ]);
    }

    /**
     * Génère une description structurée et contextuelle si les API externes ne sont pas configurées.
     */
    protected function generateLocalSuggestion(string $matiere, string $niveau, string $contexte): string
    {
        $matiereLower = mb_strtolower($matiere);
        $niveauText   = $niveau !== 'tous niveaux' ? "niveau {$niveau}" : "pour mon niveau";

        $extra = $contexte ? " Je souhaite mettre un accent particulier sur : {$contexte}." : "";

        if (str_contains($matiereLower, 'math')) {
            return "Je recherche un professeur pédagogue et rigoureux pour m'accompagner en Mathématiques ({$niveauText}). Mon objectif principal est de consolider les bases théoriques, de perfectionner mes méthodes de raisonnement et de m'entraîner régulièrement sur des exercices types pour réussir mes évaluations.{$extra} Rythme souhaité : 1 à 2 séances par semaine.";
        }

        if (str_contains($matiereLower, 'physiq') || str_contains($matiereLower, 'chimi')) {
            return "Besoin d'un tuteur expérimenté en Physique-Chimie ({$niveauText}) pour m'aider à surmonter mes difficultés de compréhension. Je souhaite revoir les cours en profondeur, décortiquer les formules et développer une démarche méthodique face aux problèmes complexes.{$extra} Idéalement 1 à 2 séances hebdomadaires en présentiel ou à distance.";
        }

        if (str_contains($matiereLower, 'svt') || str_contains($matiereLower, 'biolog') || str_contains($matiereLower, 'scien')) {
            return "Je cherche un soutien régulier en Sciences de la Vie et de la Terre ({$niveauText}) afin d'améliorer ma méthodologie d'analyse de documents et de synthèse écrite. L'objectif est d'assimiler le vocabulaire scientifique et de structurer mes réponses avec précision.{$extra} Rythme envisagé : 1 séance par semaine.";
        }

        if (str_contains($matiereLower, 'anglais') || str_contains($matiereLower, 'english')) {
            return "Je souhaite bénéficier de cours particuliers en Anglais ({$niveauText}) pour perfectionner ma grammaire, enrichir mon vocabulaire et gagner en aisance à l'oral comme à l'écrit. Mon but est de préparer sereinement mes examens et d'atteindre un niveau solide.{$extra} Rythme souhaité : 1 à 2 séances par semaine.";
        }

        if (str_contains($matiereLower, 'franc') || str_contains($matiereLower, 'littérat')) {
            return "Je recherche un accompagnement individualisé en Français ({$niveauText}) pour travailler l'expression écrite, la structure des dissertations et l'analyse de textes. Je souhaite combler mes lacunes stylistiques et préparer mes examens en toute confiance.{$extra} Rythme régulier : 1 séance hebdomadaire.";
        }

        if (str_contains($matiereLower, 'info') || str_contains($matiereLower, 'program') || str_contains($matiereLower, 'python') || str_contains($matiereLower, 'code')) {
            return "Je recherche un tuteur passionné en Informatique ({$niveauText}) pour m'aider à maîtriser les bases de l'algorithmique et de la programmation. L'objectif est de réaliser des projets pratiques, comprendre la logique du code et résoudre mes blocages techniques pas à pas.{$extra} Rythme souhaité : 1 à 2 séances par semaine.";
        }

        if (str_contains($matiereLower, 'écono') || str_contains($matiereLower, 'gestion') || str_contains($matiereLower, 'compta')) {
            return "Je souhaite un encadrement sérieux en Économie et Gestion ({$niveauText}) pour assimiler les concepts théoriques majeurs, comprendre l'actualité économique et m'exercer sur des cas pratiques d'examen.{$extra} Rythme souhaité : 1 à 2 séances par semaine.";
        }

        return "Je recherche un tuteur compétent et disponible pour m'accompagner en {$matiere} ({$niveauText}). Mon objectif est de renforcer mes compétences, d'acquérir une méthode de travail efficace et d'obtenir de meilleurs résultats académiques.{$extra} Rythme souhaité : 1 à 2 séances par semaine avec un suivi personnalisé.";
    }
}
