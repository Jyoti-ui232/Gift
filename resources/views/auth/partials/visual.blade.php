@php
    $visualTitle = $visualTitle ?? 'Gifts that bloom with love';
    $visualText = $visualText ?? 'Thoughtful presents, sweet surprises, and moments worth wrapping.';
@endphp

<aside class="gb-auth-visual" aria-hidden="false">
    <div class="gb-auth-illus">
        <div class="gb-auth-illus-platform"></div>
        <svg class="gb-auth-illus-svg" viewBox="0 0 320 320" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Cute gift character">
            {{-- soft glow --}}
            <ellipse cx="160" cy="268" rx="92" ry="18" fill="#c4b5e8" opacity="0.35"/>

            {{-- floating gifts --}}
            <g transform="translate(42 58)">
                <rect x="0" y="10" width="36" height="30" rx="6" fill="#f5b8c8"/>
                <rect x="14" y="10" width="8" height="30" fill="#fff" opacity="0.55"/>
                <rect x="0" y="20" width="36" height="8" fill="#fff" opacity="0.45"/>
                <path d="M10 10 C10 2 18 2 18 10 C18 2 26 2 26 10" fill="none" stroke="#e891b0" stroke-width="3" stroke-linecap="round"/>
            </g>
            <g transform="translate(248 78) scale(0.85)">
                <rect x="0" y="10" width="36" height="30" rx="6" fill="#b8a4e8"/>
                <rect x="14" y="10" width="8" height="30" fill="#fff" opacity="0.55"/>
                <rect x="0" y="20" width="36" height="8" fill="#fff" opacity="0.45"/>
                <path d="M10 10 C10 2 18 2 18 10 C18 2 26 2 26 10" fill="none" stroke="#8b6fd4" stroke-width="3" stroke-linecap="round"/>
            </g>

            {{-- sparkles --}}
            <path d="M70 120 l3 8 8 3 -8 3 -3 8 -3 -8 -8 -3 8 -3 z" fill="#ffd66b" opacity="0.9"/>
            <path d="M250 150 l2 6 6 2 -6 2 -2 6 -2 -6 -6 -2 6 -2 z" fill="#f5b8c8"/>
            <path d="M95 210 l2 5 5 2 -5 2 -2 5 -2 -5 -5 -2 5 -2 z" fill="#c4b5e8"/>

            {{-- character body --}}
            <ellipse cx="160" cy="210" rx="48" ry="52" fill="#c4b5e8"/>
            {{-- hoodie shadow --}}
            <ellipse cx="160" cy="218" rx="40" ry="38" fill="#b39ae0"/>

            {{-- head --}}
            <circle cx="160" cy="128" r="42" fill="#f3d4c2"/>
            {{-- hair bun --}}
            <circle cx="160" cy="82" r="18" fill="#6b4a3a"/>
            <ellipse cx="160" cy="108" rx="40" ry="22" fill="#6b4a3a"/>
            <path d="M122 118 Q120 148 132 155" fill="#6b4a3a"/>
            <path d="M198 118 Q200 148 188 155" fill="#6b4a3a"/>

            {{-- face --}}
            <ellipse cx="146" cy="130" rx="5" ry="6" fill="#3d2f5c"/>
            <ellipse cx="174" cy="130" rx="5" ry="6" fill="#3d2f5c"/>
            <circle cx="148" cy="128" r="1.5" fill="#fff"/>
            <circle cx="176" cy="128" r="1.5" fill="#fff"/>
            <ellipse cx="140" cy="140" rx="7" ry="4" fill="#f5b8c8" opacity="0.55"/>
            <ellipse cx="180" cy="140" rx="7" ry="4" fill="#f5b8c8" opacity="0.55"/>
            <path d="M152 148 Q160 156 168 148" fill="none" stroke="#c47a8a" stroke-width="2.2" stroke-linecap="round"/>

            {{-- arms holding gift --}}
            <path d="M118 200 Q100 220 118 238" fill="none" stroke="#f3d4c2" stroke-width="14" stroke-linecap="round"/>
            <path d="M202 200 Q220 220 202 238" fill="none" stroke="#f3d4c2" stroke-width="14" stroke-linecap="round"/>

            {{-- big gift box in hands --}}
            <g transform="translate(128 198)">
                <rect x="0" y="16" width="64" height="48" rx="8" fill="#e891b0"/>
                <rect x="0" y="16" width="64" height="48" rx="8" fill="url(#gbGiftShine)" opacity="0.35"/>
                <rect x="26" y="16" width="12" height="48" fill="#fff8c8"/>
                <rect x="0" y="34" width="64" height="12" fill="#fff8c8"/>
                <rect x="-2" y="4" width="68" height="16" rx="5" fill="#d4789a"/>
                <path d="M22 4 C22 -8 32 -8 32 4 C32 -8 42 -8 42 4" fill="none" stroke="#fff8c8" stroke-width="5" stroke-linecap="round"/>
            </g>

            {{-- legs --}}
            <rect x="138" y="250" width="16" height="28" rx="8" fill="#f7f4ff"/>
            <rect x="166" y="250" width="16" height="28" rx="8" fill="#f7f4ff"/>
            <ellipse cx="146" cy="280" rx="14" ry="8" fill="#9b7ed9"/>
            <ellipse cx="174" cy="280" rx="14" ry="8" fill="#9b7ed9"/>

            {{-- tiny hearts --}}
            <path d="M230 210 C230 204 238 204 238 210 C238 204 246 204 246 210 C246 218 238 224 238 224 C238 224 230 218 230 210Z" fill="#f5b8c8" opacity="0.85"/>

            <defs>
                <linearGradient id="gbGiftShine" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0%" stop-color="#fff"/>
                    <stop offset="100%" stop-color="#e891b0" stop-opacity="0"/>
                </linearGradient>
            </defs>
        </svg>
    </div>

    <div class="gb-auth-visual-copy">
        <h2>{{ $visualTitle }}</h2>
        <p>{{ $visualText }}</p>
    </div>
</aside>
