<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-[#101726] border border-slate-700 hover:border-slate-600 rounded-xl font-bold text-xs text-slate-300 hover:text-white uppercase tracking-wider transition duration-150 cursor-pointer']) }}>
    {{ $slot }}
</button>
