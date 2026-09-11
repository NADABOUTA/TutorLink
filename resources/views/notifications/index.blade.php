<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
            <div>
                <h1 class="page-title text-2xl font-black text-white font-display">Notifications</h1>
                <p class="page-subtitle text-xs text-slate-400 mt-0.5">Alertes et activités récentes de votre compte</p>
            </div>
            @if(Auth::user()->unreadNotifications->isNotEmpty())
                <form method="POST" action="{{ route('notifications.markAllRead') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#101726] border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white text-xs font-bold transition cursor-pointer">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Tout marquer comme lu
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        @if($notifications->isEmpty())
            <div class="card p-16 rounded-3xl text-center shadow-xl">
                <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-[#090D16] border border-slate-800 flex items-center justify-center text-slate-500">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <h3 class="text-base font-bold text-white font-display">Aucune notification</h3>
                <p class="text-xs text-slate-400 mt-1">Vous recevrez des alertes lors de nouvelles activités (offres, validation, commentaires).</p>
                <div class="mt-4">
                    <a href="{{ route('demandes.index') }}" class="btn-primary text-xs py-2.5 px-6 inline-flex items-center gap-2">Explorer les demandes</a>
                </div>
            </div>
        @else
            <div class="space-y-3">
                @foreach($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $isUnread = is_null($notification->read_at);
                    @endphp
                    <div class="card p-5 rounded-2xl flex items-start gap-4 transition-all duration-200 hover:border-slate-700 {{ $isUnread ? 'border-amber-500/30 bg-gradient-to-r from-amber-500/[0.08] to-[#101726]' : 'border-slate-800' }}">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isUnread ? 'bg-amber-500/15 border border-amber-500/30 text-amber-400' : 'bg-[#090D16] border border-slate-800 text-slate-500' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                                    {{ $data['title'] ?? 'Notification' }}
                                    @if($isUnread)
                                        <span class="w-2 h-2 rounded-full bg-amber-400 inline-block shadow-sm shadow-amber-400"></span>
                                    @endif
                                </h3>
                                <span class="text-[11px] text-slate-400 flex-shrink-0">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-300 leading-relaxed mb-3">{{ $data['message'] ?? '' }}</p>
                            @if(isset($data['url']))
                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="inline-block">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs font-bold text-amber-400 hover:text-amber-300 inline-flex items-center gap-1 cursor-pointer">
                                        Consulter la demande <span class="text-[10px]">&rarr;</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $notifications->links() }}</div>
        @endif
    </div>
</x-app-layout>
