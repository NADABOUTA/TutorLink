<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Affiche la liste de toutes les notifications de l'utilisateur.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $notifications = $user->notifications()->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Marque une notification spécifique comme lue et redirige vers sa destination.
     */
    public function read(Request $request, string $id): RedirectResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        $targetUrl = $notification->data['url'] ?? route('dashboard');

        return redirect($targetUrl);
    }

    /**
     * Marque toutes les notifications non lues comme lues.
     */
    public function markAllRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'Toutes vos notifications ont été marquées comme lues.');
    }
}
