<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Affiche la liste des utilisateurs avec filtres et statistiques.
     */
    public function index(Request $request): View
    {
        $query = User::with(['avisRecus'])
            ->withCount(['demandes', 'offres'])
            ->latest();

        // Recherche par mot-clé (nom ou email)
        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('matiere', 'like', "%{$search}%");
            });
        }

        // Filtre par rôle
        if ($request->filled('role')) {
            $role = $request->query('role');
            $query->where('role', $role);
        }

        // Filtre par statut (actif / inactif)
        if ($request->filled('statut')) {
            $statut = $request->query('statut');
            if ($statut === 'actif') {
                $query->where('is_active', true);
            } elseif ($statut === 'inactif') {
                $query->where('is_active', false);
            }
        }

        $users = $query->paginate(12)->withQueryString();

        // Statistiques globales
        $stats = [
            'total'      => User::count(),
            'apprenants' => User::where('role', 'apprenant')->count(),
            'tuteurs'    => User::where('role', 'tuteur')->count(),
            'inactifs'   => User::where('is_active', false)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Active ou désactive le compte d'un utilisateur.
     */
    public function toggle(User $user): RedirectResponse
    {
        // Empêcher l'administrateur de désactiver son propre compte
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Action interdite : vous ne pouvez pas désactiver votre propre compte.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $message = $user->is_active
            ? "Le compte de {$user->name} a été réactivé avec succès."
            : "Le compte de {$user->name} a été suspendu/désactivé.";

        return redirect()->back()->with('success', $message);
    }
}
