@props(['banners'])

@push('styles')
<style>
    .hero-section {
        position: relative;
        min-height: 90vh;
        overflow: hidden;
        background: #0A0F1E;
    }

    .hero-slide {
        position: absolute;
        inset: 0;
        transition: opacity 0.8s ease, transform 0.8s ease;
        opacity: 0;
        transform: scale(1.05);
    }

    .hero-slide.active {
        opacity: 1;
        transform: scale(1);
    }

    .hero-bg-image {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            105deg,
            rgba(10, 15, 30, 0.92) 0%,
            rgba(10, 15, 30, 0.75) 50%,
            rgba(10, 15, 30, 0.3) 100%
        );
    }

    .hero-content {
        position: relative;
        z-index: 10;
        max-width: 80rem;
        margin: 0 auto;
        padding: 0 1.5rem;
        height: 100%;
        display: flex;
        align-items: center;
        min-height: 90vh;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(59, 130, 246, 0.15);
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: #60A5FA;
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        margin-bottom: 1.5rem;
        backdrop-filter: blur(10px);
        animation: fadeSlideDown 0.8s ease 0.2s both;
    }

    .hero-title {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(2.5rem, 6vw, 5rem);
        font-weight: 800;
        line-height: 1.1;
        color: #fff;
        margin-bottom: 1.5rem;
        animation: fadeSlideUp 0.8s ease 0.4s both;
    }

    .hero-title .highlight {
        background: linear-gradient(135deg, #3B82F6, #60A5FA, #F59E0B);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.15rem;
        color: rgba(255, 255, 255, 0.7);
        max-width: 36rem;
        line-height: 1.7;
        margin-bottom: 2.5rem;
        animation: fadeSlideUp 0.8s ease 0.6s both;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        animation: fadeSlideUp 0.8s ease 0.8s both;
    }

    .btn-primary-hero {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        background: linear-gradient(135deg, #2563EB, #3B82F6);
        color: white;
        padding: 0.9rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 24px rgba(59, 130, 246, 0.4);
    }

    .btn-primary-hero:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 32px rgba(59, 130, 246, 0.6);
        background: linear-gradient(135deg, #1D4ED8, #2563EB);
    }

    .btn-secondary-hero {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        padding: 0.9rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .btn-secondary-hero:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-2px);
    }

    .hero-stats {
        display: flex;
        gap: 2.5rem;
        margin-top: 3rem;
        animation: fadeSlideUp 0.8s ease 1s both;
    }

    .hero-stat-item {
        text-align: left;
    }

    .hero-stat-number {
        font-size: 1.75rem;
        font-weight: 800;
        color: #fff;
        line-height: 1;
    }

    .hero-stat-label {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.5);
        margin-top: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Dots */
    .slider-dots {
        position: absolute;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 0.5rem;
        z-index: 20;
    }

    .slider-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255,255,255,0.4);
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 0;
    }

    .slider-dot.active {
        width: 28px;
        border-radius: 4px;
        background: #3B82F6;
    }

    /* Scrolling arrow */
    .scroll-indicator {
        position: absolute;
        bottom: 2.5rem;
        right: 3rem;
        z-index: 20;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
        color: rgba(255,255,255,0.4);
        font-size: 0.7rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        animation: bounce 2s ease infinite;
    }

    .scroll-indicator svg {
        width: 20px;
        height: 20px;
    }

    /* Floating particles */
    .hero-glow {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        pointer-events: none;
        animation: pulseGlow 4s ease-in-out infinite;
    }

    .glow-blue {
        width: 400px;
        height: 400px;
        background: rgba(59, 130, 246, 0.12);
        top: -100px;
        right: 10%;
    }

    .glow-orange {
        width: 300px;
        height: 300px;
        background: rgba(245, 158, 11, 0.08);
        bottom: 50px;
        right: 30%;
        animation-delay: 2s;
    }

    @keyframes fadeSlideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(8px); }
    }

    @keyframes pulseGlow {
        0%, 100% { opacity: 0.6; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.1); }
    }

    @media (max-width: 768px) {
        .hero-section { min-height: 75vh; }
        .hero-content { min-height: 75vh; }
        .hero-stats { gap: 1.5rem; }
        .hero-stat-number { font-size: 1.4rem; }
        .scroll-indicator { display: none; }
    }
</style>
@endpush

<section class="hero-section" x-data="{
    current: 0,
    slides: {{ $banners->count() ?: 1 }},
    init() {
        if (this.slides > 1) {
            setInterval(() => { this.current = (this.current + 1) % this.slides; }, 6000);
        }
    }
}">
    <!-- Glow effects -->
    <div class="hero-glow glow-blue"></div>
    <div class="hero-glow glow-orange"></div>

    @forelse($banners as $index => $banner)
    <div class="hero-slide" :class="current === {{ $index }} ? 'active' : ''">
        <div class="hero-bg-image"
             style="background-image: url('{{ Storage::url($banner->image) }}');">
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div style="max-width: 42rem;">
                <div class="hero-badge">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                    বাংলাদেশের #১ যানবাহন মার্কেটপ্লেস
                </div>
                <h1 class="hero-title">
                    {{ $banner->translate()?->title ?? 'আপনার স্বপ্নের গাড়ি' }}<br>
                    <span class="highlight">{{ $banner->translate()?->subtitle ?? 'এখানেই পাবেন' }}</span>
                </h1>
                <p class="hero-subtitle">
                    গাড়ি কোথায় - আপনার সম্পূর্ণ অটোমোটিভ সমাধান। গাড়ি কেনা, ভাড়া, সার্ভিস, যন্ত্রাংশ ও আরও অনেক কিছু।
                </p>
                <div class="hero-actions">
                    <a href="{{ $banner->link ?? route('shop.index') }}" class="btn-primary-hero">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        {{ $banner->translate()?->button_text ?? 'পণ্য দেখুন' }}
                    </a>
                    <a href="#services" class="btn-secondary-hero">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        সার্ভিস সমূহ
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat-item">
                        <div class="hero-stat-number">৫০০+</div>
                        <div class="hero-stat-label">পণ্য</div>
                    </div>
                    <div class="hero-stat-item" style="border-left: 1px solid rgba(255,255,255,0.15); padding-left: 2.5rem;">
                        <div class="hero-stat-number">১০+</div>
                        <div class="hero-stat-label">সার্ভিস</div>
                    </div>
                    <div class="hero-stat-item" style="border-left: 1px solid rgba(255,255,255,0.15); padding-left: 2.5rem;">
                        <div class="hero-stat-number">১০K+</div>
                        <div class="hero-stat-label">সন্তুষ্ট গ্রাহক</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <!-- Default slide when no banners -->
    <div class="hero-slide active">
        <div class="hero-bg-image"
             style="background-image: url('{{ asset('images/hero-banner.png') }}');">
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div style="max-width: 42rem;">
                <div class="hero-badge">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    বাংলাদেশের #১ যানবাহন মার্কেটপ্লেস
                </div>
                <h1 class="hero-title">
                    আপনার স্বপ্নের গাড়ি<br>
                    <span class="highlight">গাড়ি কোথায়?</span>
                </h1>
                <p class="hero-subtitle">
                    গাড়ি কেনাবেচা, ভাড়া, GPS ট্র্যাকিং, ড্রাইভার সার্ভিস, জ্বালানি স্টেশন, গ্যারেজ এবং আরও অনেক কিছু — সব এক জায়গায়।
                </p>
                <div class="hero-actions">
                    <a href="{{ route('shop.index') }}" class="btn-primary-hero">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        পণ্য দেখুন
                    </a>
                    <a href="#vehicle-services" class="btn-secondary-hero">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
                        সার্ভিস দেখুন
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat-item">
                        <div class="hero-stat-number">৫০০+</div>
                        <div class="hero-stat-label">পণ্য</div>
                    </div>
                    <div class="hero-stat-item" style="border-left: 1px solid rgba(255,255,255,0.15); padding-left: 2.5rem;">
                        <div class="hero-stat-number">১০+</div>
                        <div class="hero-stat-label">সার্ভিস</div>
                    </div>
                    <div class="hero-stat-item" style="border-left: 1px solid rgba(255,255,255,0.15); padding-left: 2.5rem;">
                        <div class="hero-stat-number">১০K+</div>
                        <div class="hero-stat-label">সন্তুষ্ট গ্রাহক</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforelse

    <!-- Slider dots -->
    @if($banners->count() > 1)
    <div class="slider-dots">
        @foreach($banners as $index => $banner)
        <button @click="current = {{ $index }}"
                class="slider-dot"
                :class="current === {{ $index }} ? 'active' : ''">
        </button>
        @endforeach
    </div>
    @endif

    <!-- Scroll indicator -->
    <div class="scroll-indicator">
        <span>Scroll</span>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
</section>
