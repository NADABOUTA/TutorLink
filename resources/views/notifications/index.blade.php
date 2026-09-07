<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="page-title">Notifications</h1>
                <p class="page-subtitle">Suivez l'activité de vos demandes, offres et messages en temps réel.</p>
            </div>
            @if(Auth::user()->unreadNotifications->isNotEmpty())
                <form method="POST" action="{{ route('notifications.markAllRead') }}">
                    @csrf
                    <button type="submit" class="btn-secondary btn-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Tout marquer comme lu
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        @if($notifications->isEmpty())
            <div class="card empty-state py-16">
                <div class="empty-state-icon">🔔</div>
                <h3 class="empty-state-title">Aucune notification pour le moment</h3>
                <p class="empty-state-desc">Vous serez notifié dès qu'un tuteur répond à vos demandes ou dès qu'une offre est retenue.</p>
                <a href="{{ route('demandes.index') }}" class="btn-primary mt-4">Explorer les demandes</a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $isUnread = is_null($notification->read_at);
                    @endphp
                    <div class="card p-4 transition-all duration-200 flex items-start gap-4 {{ $isUnread ? 'bg-indigo-950/20 border-indigo-500/40' : 'hover:bg-white/[0.02]' }}"
                         style="border: 1px solid {{ $isUnread ? 'rgba(99,102,241,0.35)' : 'rgba(255,255,255,0.06)' }};">
                        
                        {{-- Icône --}}
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0"
                             style="background: {{ $isUnread ? 'rgba(99,102,241,0.2)' : 'rgba(255,255,255,0.05)' }};">
                            {{ $data['icon'] ?? '🔔' }}
                        </div>

                        {{-- Contenu --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                                    {{ $data['title'] ?? 'Notification' }}
                                    @if($isUnread)
                                        <span class="w-2 h-2 rounded-full bg-indigo-400 inline-block animate-pulse"></span>
                                    @endif
                                </h3>
                                <span class="text-xs flex-shrink-0" style="color: rgb(148,163,184);">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <p class="text-xs leading-relaxed mb-3" style="color: rgb(203,213,225);">
                                {{ $data['message'] ?? '' }}
                            </p>

                            @if(isset($data['url']))
                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-secondary btn-sm py-1 px-3 text-xs inline-flex items-center gap-1.5" style="color: rgb(129,140,248);">
                                        <span>Consulter</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
