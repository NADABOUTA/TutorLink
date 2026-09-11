<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gradient-to-r from-rose-600 to-red-700 hover:from-rose-500 hover:to-red-600 border border-rose-500/30 rounded-xl font-bold text-xs text-white uppercase tracking-wider shadow-lg shadow-rose-600/20 focus:outline-none transition ease-in-out duration-150 cursor-pointer']) }}>
    {{ $slot }}
</button>
