<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="page-title">Notifications</h1>
                <p class="page-subtitle">Suivez l'activité de vos demandes, propositions et échanges pédagogiques.</p>
            </div>
            @if(Auth::user()->unreadNotifications->isNotEmpty())
                <form method="POST" action="{{ route('notifications.markAllRead') }}">
                    @csrf
                    <button type="submit" class="btn-secondary btn-sm">
                        <svg class="w-4 h-4 mr-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Tout marquer comme lu
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        @if($notifications->isEmpty())
            <div class="card empty-state py-16">
                <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <h3 class="empty-state-title">Aucune notification pour le moment</h3>
                <p class="empty-state-desc">Vous recevrez des alertes dès qu'un tuteur postule à votre demande ou dès qu'une offre est acceptée.</p>
                <a href="{{ route('demandes.index') }}" class="btn-primary mt-4">Explorer les demandes</a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $isUnread = is_null($notification->read_at);
                    @endphp
                    <div class="card p-4 transition-all duration-200 flex items-start gap-4 {{ $isUnread ? 'bg-blue-50/40 border-blue-200 shadow-xs' : 'bg-white hover:bg-slate-50' }}">
                        
                        {{-- Icône SVG --}}
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isUnread ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-500' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>

                        {{-- Contenu --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                    {{ $data['title'] ?? 'Notification' }}
                                    @if($isUnread)
                                        <span class="w-2 h-2 rounded-full bg-blue-600 inline-block"></span>
                                    @endif
                                </h3>
                                <span class="text-xs text-slate-400 flex-shrink-0">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <p class="text-xs text-slate-600 leading-relaxed mb-3">
                                {{ $data['message'] ?? '' }}
                            </p>

                            @if(isset($data['url']))
                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-secondary btn-sm py-1 px-3 text-xs inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-700 font-bold">
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
