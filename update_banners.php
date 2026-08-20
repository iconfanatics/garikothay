<?php

$home = file_get_contents('/home/sany/Desktop/mmm/e-commerce/resources/views/home.blade.php');

// Update Hero slide mapping
$oldSlideMap = <<<CODE
            'link' => \$banner->link ?? '#',
            'image' => \$banner->image ? asset('storage/' . \$banner->image) : '',
            'btn2' => null,
CODE;
$newSlideMap = <<<CODE
            'link' => \$banner->link ?? '#',
            'image' => \$banner->image ? asset('storage/' . \$banner->image) : '',
            'mobile_image' => \$banner->mobile_image ? asset('storage/' . \$banner->mobile_image) : null,
            'btn2' => null,
CODE;
$home = str_replace($oldSlideMap, $newSlideMap, $home);

// Update Promo mapping
$oldPromoMap = <<<CODE
            'title' => \$banner->title,
            'link' => \$banner->link ?? '#',
            'bg_start' => \$color[0],
            'bg_end' => \$color[1],
        ];
CODE;
$newPromoMap = <<<CODE
            'title' => \$banner->title,
            'link' => \$banner->link ?? '#',
            'bg_start' => \$color[0],
            'bg_end' => \$color[1],
            'image' => \$banner->image ? asset('storage/' . \$banner->image) : null,
        ];
CODE;
$home = str_replace($oldPromoMap, $newPromoMap, $home);

// Update Hero HTML
$oldHeroHtml = <<<CODE
                    <article class="gk-hero-slide" :class="{ 'is-active': current === {{ \$index }} }">
                        <img src="{{ \$slide['image'] }}" alt="{{ \$slide['title'] }}">
                        <div class="gk-hero-shade"></div>
CODE;
$newHeroHtml = <<<CODE
                    <article class="gk-hero-slide" :class="{ 'is-active': current === {{ \$index }} }">
                        <picture>
                            @if(!empty(\$slide['mobile_image']))
                                <source media="(max-width: 640px)" srcset="{{ \$slide['mobile_image'] }}">
                            @endif
                            <img src="{{ \$slide['image'] }}" alt="{{ \$slide['title'] }}">
                        </picture>
                        <div class="gk-hero-shade"></div>
CODE;
$home = str_replace($oldHeroHtml, $newHeroHtml, $home);

// Update gk-mini-promo css
$oldMiniPromoCss = <<<CODE
    .gk-mini-promo {
        min-height: 78px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border-radius: 8px;
        padding: 1rem;
        color: #ffffff;
        text-decoration: none;
    }
CODE;
$newMiniPromoCss = <<<CODE
    .gk-mini-promo {
        min-height: 78px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border-radius: 8px;
        padding: 1rem;
        color: #ffffff;
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }
CODE;
$home = str_replace($oldMiniPromoCss, $newMiniPromoCss, $home);

// Update Promo HTML
$oldPromoHtml = <<<CODE
            <div class="gk-promo-stack">
                @foreach(\$promos as \$promo)
                <a href="{{ \$promo['link'] ?? '#' }}" class="gk-mini-promo" style="background: linear-gradient(135deg, {{ \$promo['bg_start'] ?? '#e11d48' }}, {{ \$promo['bg_end'] ?? '#be123c' }});">
                    <span>{{ \$promo['kicker'] ?? '' }}</span>
                    <strong>{{ \$promo['title'] ?? '' }}</strong>
                </a>
                @endforeach
            </div>
CODE;
$newPromoHtml = <<<CODE
            <div class="gk-promo-stack">
                @foreach(\$promos as \$promo)
                <a href="{{ \$promo['link'] ?? '#' }}" class="gk-mini-promo">
                    @if(!empty(\$promo['image']))
                        <div style="position:absolute; inset:0; background-image:url('{{ \$promo['image'] }}'); background-size:cover; background-position:center; z-index:0; transition:transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'"></div>
                        <div style="position:absolute; inset:0; background:linear-gradient(to right, rgba(0,0,0,0.8), rgba(0,0,0,0.2)); z-index:1;"></div>
                    @else
                        <div style="position:absolute; inset:0; background:linear-gradient(135deg, {{ \$promo['bg_start'] ?? '#e11d48' }}, {{ \$promo['bg_end'] ?? '#be123c' }}); z-index:0;"></div>
                    @endif
                    <div style="position:relative; z-index:2; display:flex; flex-direction:column; justify-content:space-between; height:100%;">
                        <span>{{ \$promo['kicker'] ?? '' }}</span>
                        <strong>{{ \$promo['title'] ?? '' }}</strong>
                    </div>
                </a>
                @endforeach
            </div>
CODE;
$home = str_replace($oldPromoHtml, $newPromoHtml, $home);

file_put_contents('/home/sany/Desktop/mmm/e-commerce/resources/views/home.blade.php', $home);

$popup = file_get_contents('/home/sany/Desktop/mmm/e-commerce/resources/views/components/popup-banner.blade.php');

// Popup timeout
$popup = str_replace("2000); // 2 second delay", "500); // 0.5 second delay", $popup);

// Popup Image
$oldPopupImg = <<<CODE
            @if(\$popup->image)
                <div class="w-full relative aspect-video bg-gray-100">
                    <img src="{{ asset('storage/' . \$popup->image) }}" alt="{{ \$popup->title }}" class="w-full h-full object-cover">
                </div>
            @endif
CODE;
$newPopupImg = <<<CODE
            @if(\$popup->image)
                <div class="w-full relative aspect-video sm:aspect-[2/1] bg-gray-100">
                    <picture>
                        @if(\$popup->mobile_image)
                            <source media="(max-width: 640px)" srcset="{{ asset('storage/' . \$popup->mobile_image) }}">
                        @endif
                        <img src="{{ asset('storage/' . \$popup->image) }}" alt="{{ \$popup->title }}" class="w-full h-full object-cover">
                    </picture>
                </div>
            @endif
CODE;
$popup = str_replace($oldPopupImg, $newPopupImg, $popup);

// Popup Button text
$oldPopupButton = <<<CODE
{{ \$popup->getTranslation('button_text', app()->getLocale(), false) ?: __('general.explore') }}
CODE;
$newPopupButton = <<<CODE
{{ \$popup->getTranslation('button_text') ?: 'Explore Now' }}
CODE;
$popup = str_replace($oldPopupButton, $newPopupButton, $popup);

file_put_contents('/home/sany/Desktop/mmm/e-commerce/resources/views/components/popup-banner.blade.php', $popup);

echo "Updated banners.\n";
