@props(['class' => 'h-9 w-auto'])

<div class="inline-flex items-center gap-3 select-none">
    <svg class="{{ $class }}" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path d="M32 6C18.2 6 7 17.2 7 31c0 13.8 11.2 25 25 25s25-11.2 25-25C57 17.2 45.8 6 32 6Z" fill="url(#g)"/>
        <path d="M21 34.5c7.5-10 18.5-12 22-12 0 0-2.3 10.7-10.7 18.4-4.8 4.4-10.7 5.6-15.3 5.1 1.4-4.2 2.6-7.8 4-11.5Z" fill="white" fill-opacity=".9"/>
        <defs>
            <linearGradient id="g" x1="10" y1="12" x2="56" y2="54" gradientUnits="userSpaceOnUse">
                <stop stop-color="#6366F1"/>
                <stop offset="1" stop-color="#22C55E"/>
            </linearGradient>
        </defs>
    </svg>

    <div class="leading-tight">
        <div class="text-base font-extrabold text-slate-900">TIXORA</div>
        <div class="text-xs text-slate-500 -mt-0.5">event ticketing</div>
    </div>
</div>
