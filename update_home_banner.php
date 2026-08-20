<?php

$content = file_get_contents('/home/sany/Desktop/mmm/e-commerce/resources/views/home.blade.php');

// 1. Remove eyebrow and add Button
$oldContentHtml = <<<CODE
                        <div class="gk-hero-content">
                            <span class="gk-eyebrow">{{ \$slide['tag'] }}</span>
                            <h1 class="gk-hero-title">{{ \$slide['title'] }}</h1>
                            <p class="gk-hero-copy">{{ \$slide['subtitle'] }}</p>

                        </div>
CODE;
$newContentHtml = <<<CODE
                        <div class="gk-hero-content">
                            <h1 class="gk-hero-title">{{ \$slide['title'] }}</h1>
                            <p class="gk-hero-copy">{{ \$slide['subtitle'] }}</p>
                            @if(!empty(\$slide['link']) && \$slide['link'] !== '#')
                            <div style="margin-top: 1.5rem;">
                                <a href="{{ \$slide['link'] }}" class="gk-hero-btn" style="display:inline-flex; align-items:center; gap:0.5rem; background:var(--gk-red); color:white; padding:0.8rem 1.75rem; border-radius:6px; font-weight:800; font-size:1.05rem; text-decoration:none; transition:transform 0.2s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                    {{ \$slide['button'] }} <span aria-hidden="true">→</span>
                                </a>
                            </div>
                            @endif
                        </div>
CODE;
$content = str_replace($oldContentHtml, $newContentHtml, $content);

// 2. Center align .gk-hero-content
$oldHeroContentCss = <<<CODE
    .gk-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        min-height: 340px;
        flex-direction: column;
        justify-content: center;
        padding: 2rem;
    }
CODE;
$newHeroContentCss = <<<CODE
    .gk-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        min-height: 340px;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 2rem;
    }
CODE;
$content = str_replace($oldHeroContentCss, $newHeroContentCss, $content);

// 3. Fix sizes for .gk-hero-title
$oldTitleCss = <<<CODE
    .gk-hero-title {
        max-width: 720px;
        margin-top: 1rem;
        font-size: clamp(2rem, 4.2vw, 3.6rem);
        line-height: 1.02;
        font-weight: 900;
    }
CODE;
$newTitleCss = <<<CODE
    .gk-hero-title {
        max-width: 720px;
        margin-top: 1rem;
        font-size: clamp(1.75rem, 3.5vw, 2.75rem);
        line-height: 1.2;
        font-weight: 800;
    }
CODE;
$content = str_replace($oldTitleCss, $newTitleCss, $content);

// 4. Fix sizes for .gk-hero-copy
$oldCopyCss = <<<CODE
    .gk-hero-copy {
        max-width: 500px;
        margin-top: 0.9rem;
        color: rgba(255,255,255,0.82);
        line-height: 1.7;
    }
CODE;
$newCopyCss = <<<CODE
    .gk-hero-copy {
        max-width: 600px;
        margin-top: 0.9rem;
        color: rgba(255,255,255,0.9);
        font-size: clamp(1rem, 1.3vw, 1.15rem);
        line-height: 1.7;
    }
CODE;
$content = str_replace($oldCopyCss, $newCopyCss, $content);

file_put_contents('/home/sany/Desktop/mmm/e-commerce/resources/views/home.blade.php', $content);
echo "Updated home.blade.php banner styling.\n";
