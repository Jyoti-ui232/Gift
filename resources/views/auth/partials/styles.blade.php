@push('styles')
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=Nunito:500,600,700,800|Fraunces:600,700" rel="stylesheet">
<style>
/* Auth top bar — only on auth pages (markup rendered conditionally in layout) */
.gb-auth-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 14px 28px;
    background: rgba(255, 252, 255, 0.88);
    border-bottom: 1px solid rgba(184, 164, 232, 0.28);
    backdrop-filter: blur(10px);
    position: relative;
    z-index: 20;
}

.gb-auth-topbar-logo.logo-section {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.gb-auth-topbar .logo-icon {
    width: 40px;
    height: 40px;
    font-size: 18px;
    background: #7d8531;
    color: #fff;
    border-radius: 50%;
    display: grid;
    place-items: center;
}

.gb-auth-topbar .logo-text {
    font-size: 1.35rem;
    font-weight: 700;
    color: #7d8531;
    letter-spacing: 0.01em;
}

.gb-auth-topbar-phone {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 999px;
    background: linear-gradient(135deg, #faf4ff, #fff0f5);
    border: 1px solid rgba(184, 164, 232, 0.45);
    color: #6b4fa0;
    text-decoration: none;
    font-family: "Nunito", system-ui, sans-serif;
    font-size: 0.92rem;
    font-weight: 700;
    white-space: nowrap;
    box-shadow: 0 4px 14px rgba(139, 111, 212, 0.1);
    transition: transform 0.2s, box-shadow 0.2s, background 0.2s;
}

.gb-auth-topbar-phone i {
    color: #8b6fd4;
    font-size: 0.88rem;
}

.gb-auth-topbar-phone:hover {
    color: #5c4a8a;
    transform: translateY(-1px);
    box-shadow: 0 8px 18px rgba(139, 111, 212, 0.16);
}

@media (max-width: 575px) {
    .gb-auth-topbar {
        padding: 12px 14px;
        gap: 10px;
    }

    .gb-auth-topbar .logo-icon {
        width: 36px;
        height: 36px;
        font-size: 16px;
    }

    .gb-auth-topbar .logo-text {
        font-size: 1.15rem;
    }

    .gb-auth-topbar-phone {
        padding: 8px 12px;
        font-size: 0.8rem;
        gap: 6px;
    }
}

/* GiftBloom Auth — scoped to .gb-auth only */
.gb-auth {
    --gb-lavender: #b8a4e8;
    --gb-lavender-deep: #8b6fd4;
    --gb-purple: #6b4fa0;
    --gb-blush: #f5b8c8;
    --gb-blush-soft: #fde8ef;
    --gb-cream: #fff7f2;
    --gb-cream-deep: #ffe8d6;
    --gb-ink: #3d2f5c;
    --gb-muted: #8a7a9e;
    --gb-card: #ffffff;
    --gb-line: #ebe3f5;
    --gb-success-bg: #eef8f0;
    --gb-success: #2f6b44;
    --gb-error-bg: #fff0f3;
    --gb-error: #c23b5a;
    --gb-radius-card: 28px;
    --gb-radius-pill: 999px;
    --gb-shadow: 0 22px 50px rgba(107, 79, 160, 0.16);
    --gb-shadow-soft: 0 10px 28px rgba(107, 79, 160, 0.1);
    font-family: "Nunito", system-ui, sans-serif;
    color: var(--gb-ink);
    width: 100%;
    max-width: 100%;
    overflow-x: clip;
}

.gb-auth *,
.gb-auth *::before,
.gb-auth *::after {
    box-sizing: border-box;
}

.gb-auth-stage {
    position: relative;
    min-height: calc(100vh - 180px);
    padding: 36px 20px 56px;
    background:
        radial-gradient(ellipse 60% 50% at 12% 18%, rgba(245, 184, 200, 0.55), transparent 55%),
        radial-gradient(ellipse 50% 45% at 88% 12%, rgba(184, 164, 232, 0.5), transparent 50%),
        radial-gradient(ellipse 70% 55% at 50% 100%, rgba(255, 232, 214, 0.8), transparent 55%),
        linear-gradient(165deg, #f3ecff 0%, #faf4ff 40%, #fff6f0 100%);
    overflow: hidden;
}

.gb-auth-deco {
    position: absolute;
    pointer-events: none;
    z-index: 0;
}

.gb-auth-deco--cloud {
    width: 120px;
    height: 48px;
    background: rgba(255, 255, 255, 0.72);
    border-radius: 40px;
    filter: blur(0.2px);
    box-shadow: 28px 8px 0 -6px rgba(255, 255, 255, 0.55);
}

.gb-auth-deco--cloud-1 { top: 8%; left: 6%; animation: gb-auth-float 7s ease-in-out infinite; }
.gb-auth-deco--cloud-2 { top: 14%; right: 8%; width: 90px; animation: gb-auth-float 8.5s ease-in-out infinite 1s; }

.gb-auth-deco--sparkle {
    color: var(--gb-lavender-deep);
    font-size: 14px;
    opacity: 0.7;
    animation: gb-auth-twinkle 3s ease-in-out infinite;
}

.gb-auth-deco--s1 { top: 22%; left: 18%; }
.gb-auth-deco--s2 { top: 30%; right: 16%; animation-delay: 0.8s; color: var(--gb-blush); }
.gb-auth-deco--s3 { bottom: 18%; left: 12%; animation-delay: 1.4s; }

.gb-auth-deco--heart {
    color: #e891b0;
    font-size: 16px;
    opacity: 0.55;
    animation: gb-auth-float 6s ease-in-out infinite;
}

.gb-auth-deco--h1 { top: 42%; left: 5%; }
.gb-auth-deco--h2 { bottom: 28%; right: 7%; animation-delay: 1.2s; }

.gb-auth-deco--gift {
    font-size: 22px;
    opacity: 0.45;
    animation: gb-auth-float 5.5s ease-in-out infinite;
}

.gb-auth-deco--g1 { top: 55%; right: 12%; color: var(--gb-lavender-deep); animation-delay: 0.4s; }
.gb-auth-deco--g2 { bottom: 14%; left: 22%; color: #d4789a; animation-delay: 1.6s; }

@keyframes gb-auth-float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@keyframes gb-auth-twinkle {
    0%, 100% { opacity: 0.35; transform: scale(0.9); }
    50% { opacity: 0.9; transform: scale(1.1); }
}

.gb-auth-layout {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 980px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1.05fr);
    gap: 28px 36px;
    align-items: center;
}

.gb-auth-visual {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 12px 8px;
}

.gb-auth-illus {
    position: relative;
    width: min(100%, 340px);
    aspect-ratio: 1;
    margin-bottom: 18px;
}

.gb-auth-illus-platform {
    position: absolute;
    left: 50%;
    bottom: 8%;
    transform: translateX(-50%);
    width: 78%;
    height: 18%;
    background: radial-gradient(ellipse at center, rgba(184, 164, 232, 0.55), transparent 70%);
    border-radius: 50%;
}

.gb-auth-illus-svg {
    position: relative;
    z-index: 1;
    width: 100%;
    height: 100%;
    display: block;
    filter: drop-shadow(0 18px 24px rgba(107, 79, 160, 0.18));
}

.gb-auth-visual-copy h2 {
    margin: 0 0 8px;
    font-family: "Fraunces", Georgia, serif;
    font-size: clamp(1.45rem, 2.4vw, 1.85rem);
    font-weight: 700;
    color: var(--gb-purple);
    line-height: 1.25;
}

.gb-auth-visual-copy p {
    margin: 0;
    max-width: 320px;
    margin-inline: auto;
    font-size: 0.95rem;
    line-height: 1.55;
    color: var(--gb-muted);
}

.gb-auth-card {
    width: 100%;
    max-width: 420px;
    margin-inline: auto;
    background: var(--gb-card);
    border-radius: var(--gb-radius-card);
    box-shadow: var(--gb-shadow);
    padding: 36px 32px 30px;
    border: 1px solid rgba(255, 255, 255, 0.8);
    position: relative;
}

.gb-auth-card::before {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: inherit;
    padding: 1px;
    background: linear-gradient(160deg, rgba(184, 164, 232, 0.45), rgba(245, 184, 200, 0.25), transparent 60%);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    pointer-events: none;
}

.gb-auth-badge {
    width: 64px;
    height: 64px;
    margin: 0 auto 18px;
    display: grid;
    place-items: center;
    border-radius: 20px;
    background: linear-gradient(145deg, #c4b0f0, #9b7ed9);
    box-shadow: 0 12px 24px rgba(139, 111, 212, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.35);
    color: #fff;
    font-size: 28px;
    position: relative;
}

.gb-auth-badge .gb-auth-star {
    position: absolute;
    font-size: 11px;
    line-height: 1;
}

.gb-auth-badge .gb-auth-star--1 {
    top: -4px;
    right: -6px;
    color: #ffd66b;
}

.gb-auth-badge .gb-auth-star--2 {
    bottom: 2px;
    left: -8px;
    color: #f5b8c8;
    font-size: 10px;
}

.gb-auth-card-head {
    text-align: center;
    margin-bottom: 22px;
}

.gb-auth-card-head h1 {
    margin: 0 0 6px;
    font-family: "Fraunces", Georgia, serif;
    font-size: clamp(1.45rem, 2.2vw, 1.75rem);
    font-weight: 700;
    color: var(--gb-ink);
    line-height: 1.2;
}

.gb-auth-card-head p {
    margin: 0;
    font-size: 0.92rem;
    color: var(--gb-muted);
    line-height: 1.45;
}

.gb-auth-alert {
    border-radius: 14px;
    padding: 11px 14px;
    font-size: 0.85rem;
    line-height: 1.45;
    margin-bottom: 16px;
}

.gb-auth-alert--success {
    background: var(--gb-success-bg);
    color: var(--gb-success);
    border: 1px solid #cfe8d5;
}

.gb-auth-alert--error {
    background: var(--gb-error-bg);
    color: var(--gb-error);
    border: 1px solid #f3cdd6;
}

.gb-auth-alert ul {
    margin: 0;
    padding-left: 1.1rem;
}

.gb-auth-field {
    margin-bottom: 14px;
}

.gb-auth-field label {
    display: block;
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--gb-purple);
    margin-bottom: 7px;
}

.gb-auth-field label small {
    font-weight: 500;
    color: var(--gb-muted);
}

.gb-auth-input {
    position: relative;
    display: flex;
    align-items: center;
}

.gb-auth-input > i:first-child {
    position: absolute;
    left: 16px;
    color: var(--gb-lavender-deep);
    font-size: 0.95rem;
    pointer-events: none;
    z-index: 1;
}

.gb-auth-input input {
    width: 100%;
    min-height: 50px;
    border: 1.5px solid var(--gb-line);
    border-radius: var(--gb-radius-pill);
    padding: 12px 44px 12px 44px;
    background: #faf7ff;
    color: var(--gb-ink);
    font-family: inherit;
    font-size: 0.95rem;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
}

.gb-auth-input input::placeholder {
    color: #b3a6c4;
}

.gb-auth-input input:focus {
    border-color: var(--gb-lavender-deep);
    background: #fff;
    box-shadow: 0 0 0 4px rgba(184, 164, 232, 0.28);
}

.gb-auth-input input.is-invalid,
.gb-auth-input input.gb-input-invalid {
    border-color: var(--gb-error);
}

.gb-auth-toggle-pass {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    width: 38px;
    height: 38px;
    border: 0;
    border-radius: 50%;
    background: transparent;
    color: var(--gb-muted);
    cursor: pointer;
    display: grid;
    place-items: center;
    padding: 0;
}

.gb-auth-toggle-pass:hover {
    color: var(--gb-lavender-deep);
    background: rgba(184, 164, 232, 0.15);
}

.gb-auth-field-error,
.gb-auth .invalid-feedback {
    display: block;
    margin-top: 6px;
    font-size: 0.78rem;
    color: var(--gb-error);
}

.gb-auth-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
    margin: 4px 0 18px;
    font-size: 0.85rem;
}

.gb-auth-remember {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--gb-muted);
    cursor: pointer;
    user-select: none;
}

.gb-auth-remember input {
    width: 16px;
    height: 16px;
    accent-color: var(--gb-lavender-deep);
}

.gb-auth-link {
    color: var(--gb-lavender-deep);
    font-weight: 700;
    text-decoration: none;
}

.gb-auth-link:hover {
    color: var(--gb-purple);
    text-decoration: underline;
}

.gb-auth-submit {
    width: 100%;
    min-height: 52px;
    border: 0;
    border-radius: var(--gb-radius-pill);
    background: linear-gradient(120deg, #a78bdb 0%, #8b6fd4 55%, #7a5cc7 100%);
    color: #fff;
    font-family: inherit;
    font-size: 1.05rem;
    font-weight: 800;
    letter-spacing: 0.02em;
    cursor: pointer;
    box-shadow: 0 12px 24px rgba(139, 111, 212, 0.32);
    transition: transform 0.2s, box-shadow 0.2s;
}

.gb-auth-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 28px rgba(139, 111, 212, 0.4);
}

.gb-auth-submit:active {
    transform: translateY(0);
}

.gb-auth-footer {
    text-align: center;
    margin-top: 22px;
    font-size: 0.9rem;
    color: var(--gb-muted);
}

.gb-auth-footer a {
    color: var(--gb-lavender-deep);
    font-weight: 800;
    text-decoration: none;
}

.gb-auth-footer a:hover {
    text-decoration: underline;
}

.gb-auth-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 14px;
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--gb-muted);
    text-decoration: none;
}

.gb-auth-back:hover {
    color: var(--gb-lavender-deep);
}

.gb-auth-register .gb-auth-card {
    max-width: 440px;
    padding-top: 30px;
}

.gb-auth-register .gb-auth-field {
    margin-bottom: 12px;
}

.gb-auth-register .gb-auth-input input {
    min-height: 46px;
}

@media (max-width: 900px) {
    .gb-auth-layout {
        grid-template-columns: 1fr;
        max-width: 460px;
        gap: 10px;
    }

    .gb-auth-visual {
        order: -1;
        padding-bottom: 0;
    }

    .gb-auth-illus {
        width: min(100%, 220px);
        margin-bottom: 8px;
    }

    .gb-auth-visual-copy h2 {
        font-size: 1.25rem;
    }

    .gb-auth-visual-copy p {
        font-size: 0.88rem;
        max-width: 280px;
    }

    .gb-auth-stage {
        padding: 22px 14px 40px;
        min-height: auto;
    }

    .gb-auth-card {
        padding: 28px 20px 24px;
        border-radius: 24px;
    }

    .gb-auth-deco--cloud-2,
    .gb-auth-deco--g1,
    .gb-auth-deco--h1 {
        display: none;
    }
}

@media (max-width: 380px) {
    .gb-auth-row {
        flex-direction: column;
        align-items: flex-start;
    }

    .gb-auth-card-head h1 {
        font-size: 1.35rem;
    }

    .gb-auth-badge {
        width: 56px;
        height: 56px;
        font-size: 24px;
    }
}
</style>
@endpush
