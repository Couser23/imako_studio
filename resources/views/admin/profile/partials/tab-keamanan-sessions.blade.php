@if(isset($sessions) && count($sessions) > 0)
    @foreach($sessions as $session)
    <div class="flex items-center gap-3 p-3 border border-gray-100 rounded-lg transition-all duration-500 ease-in-out">
        <div class="w-10 h-10 rounded-full {{ $session->is_current_device ? 'bg-blue-50' : 'bg-gray-50' }} flex items-center justify-center shrink-0">
            @if($session->agent['is_desktop'])
                <svg class="w-5 h-5 {{ $session->is_current_device ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            @else
                <svg class="w-5 h-5 {{ $session->is_current_device ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            @endif
        </div>
        <div class="w-full flex justify-between items-center">
            <div>
                <h6 class="text-[11px] font-extrabold {{ $session->is_current_device ? 'text-brand-dark' : 'text-gray-600' }} flex items-center gap-2">
                    {{ $session->agent['platform'] ? $session->agent['platform'] : 'Unknown' }} - {{ $session->agent['browser'] ? $session->agent['browser'] : 'Unknown' }}
                    @if($session->is_current_device)
                        <span class="px-2 py-0.5 bg-green-50 text-green-600 text-[8px] rounded-full uppercase tracking-wider">Perangkat Ini</span>
                    @endif
                </h6>
                <p class="text-[9px] font-bold text-gray-400">
                    {{ $session->ip_address }} · 
                    @if($session->is_current_device)
                        Aktif sekarang
                    @else
                        Aktif {{ $session->last_active }}
                    @endif
                </p>
            </div>
            
            @if(!$session->is_current_device)
            <button type="button" onclick="logoutSession('{{ $session->id }}')" class="shrink-0 p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition" title="Logout perangkat ini">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </button>
            @endif
        </div>
    </div>
    @endforeach
@endif
