@php
    $channel = core()->getCurrentChannel();
@endphp

<!-- SEO Meta Content -->
@push ('meta')
    <meta
        name="title"
        content="{{ $channel->home_seo['meta_title'] ?? '' }}"
    />

    <meta
        name="description"
        content="{{ $channel->home_seo['meta_description'] ?? '' }}"
    />

    <meta
        name="keywords"
        content="{{ $channel->home_seo['meta_keywords'] ?? '' }}"
    />
@endPush

@push('scripts')
    @if(! empty($categories))
        <script>
            localStorage.setItem('categories', JSON.stringify(@json($categories)));
        </script>
    @endif
@endpush

@push('styles')
    <style>
        .keswa-home-hero {
            position: relative;
            height: min(58vw, 560px);
            min-height: 380px;
            overflow: hidden;
            background: #f8f5f0;
        }

        @media (min-width: 1061px) {
            body:has(.keswa-home-hero) > #app > header {
                background: transparent;
                border-bottom: 0;
                box-shadow: none;
            }

            body:has(.keswa-home-hero) > #app > header.is-scrolled {
                background: #fff;
                box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
            }

            body:has(.keswa-home-hero) > #app > header [class*="min-h-[78px]"] {
                border-color: transparent;
            }

            .keswa-home-hero { margin-top: -78px; }
        }

        .keswa-home-hero-track {
            display: flex;
            width: 100%;
            height: 100%;
            direction: ltr;
            transition: transform 1000ms cubic-bezier(.22,.65,.25,1);
            will-change: transform;
        }

        .keswa-home-hero-slide {
            position: relative;
            flex: 0 0 100%;
            min-width: 100%;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .keswa-home-hero-slide img {
            display: block;
            width: 100%;
            height: calc(100% + 28px);
            object-fit: cover;
            object-position: center 64%;
            transform: translateY(-28px);
        }

        .keswa-home-hero-sale {
            position: absolute;
            left: 6.5%;
            bottom: 27%;
            display: inline-flex;
            min-width: 130px;
            align-items: center;
            justify-content: center;
            gap: 14px;
            padding: 15px 30px;
            background: #171717;
            color: #fff;
            font-size: 20px;
            font-weight: 600;
            opacity: 0;
            transform: translateY(70px);
            transition: none;
        }

        .keswa-home-hero-slide.is-active .keswa-home-hero-sale {
            opacity: 1;
            transform: translateY(0);
            animation: keswa-sale-rise 900ms 550ms cubic-bezier(.2,.75,.25,1) both;
        }

        @keyframes keswa-sale-rise {
            from { opacity: 0; transform: translateY(70px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .keswa-home-hero-dots {
            position: absolute;
            z-index: 10;
            right: 0;
            bottom: 62px;
            left: 0;
            display: flex;
            justify-content: center;
            gap: 6px;
        }

        .keswa-home-hero-dot {
            display: grid;
            width: 22px;
            height: 22px;
            place-items: center;
            padding: 0;
            border: 0;
            border-radius: 50%;
            background: #fff;
            cursor: pointer;
        }

        .keswa-home-hero-dot::after {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #171717;
            content: '';
        }

        .keswa-home-hero-dot.is-active { background: #9ca3af; }

        .keswa-home-marquee {
            overflow: hidden;
            background: #171717;
            color: #fff;
            white-space: nowrap;
        }

        .keswa-home-hero > .keswa-home-marquee {
            position: absolute;
            z-index: 9;
            right: 0;
            bottom: 0;
            left: 0;
            height: 48px;
        }

        .keswa-home-marquee-track {
            display: flex;
            width: max-content;
            animation: keswa-marquee 28s linear infinite;
        }

        .keswa-home-marquee-track span {
            padding: 12px 30px;
            font-size: 17px;
            font-weight: 500;
        }

        .keswa-home-categories {
            padding: 72px 2% 56px;
        }

        .keswa-home-categories-heading {
            display: flex;
            align-items: center;
            gap: 28px;
            margin-bottom: 28px;
        }

        .keswa-home-categories-controls { display: flex; gap: 16px; }

        .keswa-home-categories-arrow,
        .keswa-home-discover-arrow {
            display: grid;
            width: 44px;
            height: 44px;
            flex: 0 0 auto;
            place-items: center;
            border: 1px solid #111;
            border-radius: 50%;
            background: #fff;
            color: #111;
            cursor: pointer;
        }

        .keswa-home-categories-arrow svg { width: 22px; height: 22px; }

        .keswa-home-categories-title {
            margin: 0;
            font-size: 22px;
            font-weight: 500;
            line-height: 1.2;
        }

        .keswa-home-categories-track {
            display: grid;
            grid-auto-columns: calc((100% - 102px) / 4);
            grid-auto-flow: column;
            gap: 34px;
            overflow-x: auto;
            scroll-behavior: smooth;
            scrollbar-width: none;
        }

        .keswa-home-categories-track::-webkit-scrollbar { display: none; }

        .keswa-home-category-card,
        .keswa-home-discover-card {
            position: relative;
            display: flex;
            min-width: 0;
            aspect-ratio: .83;
            align-items: flex-end;
            overflow: hidden;
            border-radius: 12px;
            scroll-snap-align: start;
        }

        .keswa-home-category-card {
            background: #dedede;
            color: #fff;
        }

        .keswa-home-category-card::after {
            position: absolute;
            inset: 45% 0 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, .58));
            content: '';
        }

        .keswa-home-category-label {
            position: relative;
            z-index: 1;
            padding: 0 22px 22px;
            font-size: 20px;
            font-weight: 400;
        }

        .keswa-home-discover-card {
            align-items: center;
            padding: 30px;
            border: 1px solid #111;
            color: #111;
        }

        .keswa-home-discover-content { display: block; max-width: 270px; }

        .keswa-home-discover-title {
            display: block;
            margin: 0 0 24px;
            font-size: clamp(23px, 2vw, 36px);
            font-weight: 300;
            line-height: 1.2;
        }

        .keswa-home-discover-arrow { width: 58px; height: 58px; }
        .keswa-home-discover-arrow svg { width: 23px; height: 23px; stroke-width: 1.2; }

        .keswa-home-bestsellers { padding: 48px 6.2% 72px; }
        .keswa-home-bestsellers-heading { margin: 0 0 42px; text-align: center; }
        .keswa-home-bestsellers-title { margin: 0 0 8px; font-size: clamp(26px, 2.4vw, 40px); font-weight: 400; line-height: 1.2; }
        .keswa-home-bestsellers-subtitle { margin: 0; font-size: 16px; font-weight: 400; }
        .keswa-home-bestsellers-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); column-gap: 34px; row-gap: 38px; }
        .keswa-home-bestsellers .category-grid-card { width: 100%; max-width: none; overflow: visible; align-content: start; border-radius: 0; box-shadow: none; }
        .keswa-home-bestsellers .category-grid-image { position: relative; width: 100%; max-width: none; max-height: none; aspect-ratio: 3 / 4; overflow: hidden; border-radius: 8px; }
        .keswa-home-bestsellers .category-grid-image > a { position: relative; display: block; width: 100%; height: 100%; overflow: hidden; }
        .keswa-home-bestsellers .category-grid-image > a img { width: 100%; height: 100%; object-fit: cover; transition: transform 550ms cubic-bezier(.2, .7, .2, 1); }
        .keswa-home-bestsellers .category-grid-image > a:hover img { transform: scale(1.055); }
        .keswa-home-bestsellers .category-grid-info { width: 100%; max-width: none; margin: 0; padding: 12px 0 0; transform: none; gap: 8px; background: transparent !important; box-shadow: none !important; text-align: left; }
        .keswa-home-bestsellers .category-grid-info > p { margin: 0; text-align: left; }
        .keswa-home-bestsellers .category-grid-price { display: flex; align-items: baseline; justify-content: flex-start; gap: 8px; margin: 0; padding: 0; text-align: left; }
        .keswa-home-bestsellers .category-grid-price p { width: auto; margin: 0 !important; padding: 0 !important; text-align: left; white-space: nowrap; }
        .keswa-home-bestsellers .category-grid-price .line-through { color: #9ca3af; font-weight: 300; text-decoration-thickness: 1px; }
        .keswa-home-bestsellers .category-grid-actions { position: absolute; z-index: 5; bottom: 12px; left: 50%; display: flex; gap: 8px; transform: translateX(-50%); pointer-events: none; }
        .keswa-home-bestsellers .category-grid-action { display: flex; width: 44px; height: 44px; align-items: center; justify-content: center; border: 0; border-radius: 4px; background: #fff; color: #111; opacity: 0; transform: translateY(18px); transition: opacity 360ms ease, transform 520ms cubic-bezier(.2, .75, .25, 1), background-color 180ms ease, color 180ms ease; pointer-events: none; cursor: pointer; }
        .keswa-home-bestsellers .category-grid-action:nth-child(1) { transition-delay: 0ms; }
        .keswa-home-bestsellers .category-grid-action:nth-child(2) { transition-delay: 100ms; }
        .keswa-home-bestsellers .category-grid-action:nth-child(3) { transition-delay: 200ms; }
        .keswa-home-bestsellers .category-grid-action svg { width: 23px; height: 23px; }
        .keswa-home-bestsellers .category-grid-action:hover,
        .keswa-home-bestsellers .category-grid-action.is-active { background: #000; color: #fff; }
        .keswa-home-bestsellers .category-grid-image:hover .category-grid-action,
        .keswa-home-bestsellers .category-grid-image:focus-within .category-grid-action { opacity: 1; transform: translateY(0); pointer-events: auto; }
        .keswa-home-bestsellers-view-all { display: block; min-width: 132px; margin: 40px auto 0; padding: 12px 28px; border: 1px solid #111; background: #fff; color: #111; font-size: 15px; text-align: center; transition: background-color 180ms ease, color 180ms ease; cursor: pointer; }
        .keswa-home-bestsellers-view-all:hover { background: #e5e7eb; }

        .keswa-home-gram { padding: 8px 6.2% 62px; }
        .keswa-home-gram-heading { margin: 0 0 38px; text-align: center; }
        .keswa-home-gram-title { margin: 0 0 8px; font-size: clamp(26px, 2.4vw, 40px); font-weight: 400; line-height: 1.2; }
        .keswa-home-gram-subtitle { margin: 0; font-size: 16px; }
        .keswa-home-gram-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 12px; }
        .keswa-home-gram-item { position: relative; display: grid; aspect-ratio: 1; place-items: center; overflow: hidden; border-radius: 8px; background: #f3f3f3; }
        .keswa-home-gram-item::after { position: absolute; inset: 0; background: rgba(0, 0, 0, .32); content: ''; opacity: 0; transition: opacity 250ms ease; }
        .keswa-home-gram-item img { display: block; width: 100%; height: 100%; object-fit: cover; transition: transform 500ms cubic-bezier(.2, .7, .2, 1); }
        .keswa-home-gram-item img.gram-focus-tshirt { object-position: 92% center; }
        .keswa-home-gram-item img.gram-focus-shirt { object-position: 92% center; }
        .keswa-home-gram-item:hover::after,
        .keswa-home-gram-item:focus-visible::after { opacity: 1; }
        .keswa-home-gram-item:hover img,
        .keswa-home-gram-item:focus-visible img { transform: scale(1.08); }
        .keswa-home-gram-actions { position: absolute; z-index: 1; inset: 0; display: flex; align-items: center; justify-content: center; gap: 12px; opacity: 0; transform: translateY(8px); transition: opacity 220ms ease, transform 220ms ease; }
        .keswa-home-gram-item:hover .keswa-home-gram-actions,
        .keswa-home-gram-item:focus-visible .keswa-home-gram-actions { opacity: 1; transform: translateY(0); }
        .keswa-home-gram-action { display: grid; width: 46px; height: 46px; place-items: center; border-radius: 50%; background: #fff; color: #111; transition: background-color 180ms ease, color 180ms ease; }
        .keswa-home-gram-action svg { width: 21px; height: 21px; }
        .keswa-home-gram-action:hover { background: #000; color: #fff; }

        .keswa-home-promises { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 34px; padding: 48px 4% 8px; }
        .keswa-home-promise { display: flex; min-height: 287px; flex-direction: column; align-items: center; justify-content: center; padding: 30px 22px; border: 1px solid #dedee2; border-radius: 8px; text-align: center; }
        .keswa-home-promise svg { width: 64px; height: 64px; margin-bottom: 38px; }
        .keswa-home-promise h3 { margin: 0 0 16px; font-size: 20px; font-weight: 500; }
        .keswa-home-promise p { display: -webkit-box; max-width: 320px; margin: 0; overflow: hidden; font-size: 16px; line-height: 1.7; -webkit-box-orient: vertical; -webkit-line-clamp: 2; }

        @keyframes keswa-marquee {
            to { transform: translateX(-50%); }
        }

        @media (max-width: 767px) {
            .keswa-home-hero { height: 52vh; min-height: 340px; max-height: 480px; }
            .keswa-home-hero-slide img { height: 100%; object-position: 58% center; transform: none; }
            .keswa-home-hero-sale { left: 5%; bottom: 22%; min-width: 104px; padding: 12px 22px; font-size: 16px; }
            .keswa-home-hero-dots { bottom: 52px; }
            .keswa-home-hero > .keswa-home-marquee { height: 40px; }
            .keswa-home-marquee-track span { padding: 8px 22px; font-size: 13px; }
            .keswa-home-categories { padding: 42px 16px; }
            .keswa-home-categories-heading { gap: 14px; margin-bottom: 20px; }
            .keswa-home-categories-controls { gap: 8px; }
            .keswa-home-categories-arrow { width: 38px; height: 38px; }
            .keswa-home-categories-title { font-size: 18px; }
            .keswa-home-categories-track { grid-auto-columns: 78%; gap: 14px; }
            .keswa-home-category-label { padding: 0 15px 16px; font-size: 18px; }
            .keswa-home-discover-card { padding: 20px; }
            .keswa-home-discover-arrow { width: 48px; height: 48px; }
            .keswa-home-bestsellers { padding: 40px 16px 52px; }
            .keswa-home-bestsellers-heading { margin-bottom: 26px; }
            .keswa-home-bestsellers-subtitle { font-size: 14px; }
            .keswa-home-bestsellers-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 24px 14px; }
            .keswa-home-bestsellers .category-grid-action { width: 36px; height: 36px; opacity: 1; transform: none; pointer-events: auto; }
            .keswa-home-bestsellers .category-grid-action svg { width: 20px; height: 20px; }
            .keswa-home-gram { padding: 0 16px 36px; }
            .keswa-home-gram-heading { margin-bottom: 24px; }
            .keswa-home-gram-subtitle { font-size: 14px; }
            .keswa-home-gram-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
            .keswa-home-promises { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; padding: 30px 16px 8px; }
            .keswa-home-promise { min-height: 190px; padding: 20px 12px; }
            .keswa-home-promise svg { width: 40px; height: 40px; margin-bottom: 18px; }
            .keswa-home-promise h3 { font-size: 16px; }
            .keswa-home-promise p { font-size: 13px; }
        }

        @media (max-width: 420px) {
            .keswa-home-bestsellers-grid { grid-template-columns: minmax(0, 1fr); }
            .keswa-home-gram-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .keswa-home-promises { grid-template-columns: minmax(0, 1fr); }
        }

        @media (prefers-reduced-motion: reduce) {
            .keswa-home-hero-track, .keswa-home-hero-sale { transition: none; }
            .keswa-home-marquee-track { animation-duration: 80s; }
        }
    </style>
@endpush

<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{  $channel->home_seo['meta_title'] ?? '' }}
    </x-slot>

    @php ($hideNextHomeDescription = false) @endphp
    @php ($homeHeroRendered = false) @endphp

    <!-- Loop over the theme customization -->
    @foreach ($customizations as $customization)
        @php ($data = $customization->options) @endphp

        @if (! $homeHeroRendered)
        <!-- Static Content -->
        @switch ($customization->type)
            @case ($customization::IMAGE_CAROUSEL)
                <section class="keswa-home-hero" aria-label="{{ trans('shop::app.home.index.image-carousel') }}" data-keswa-home-hero>
                    <div class="keswa-home-hero-track" data-hero-track>
                    @foreach ([
                        ['image' => 'hero-1.webp', 'product' => 't-round-relaxed'],
                        ['image' => 'hero-2.webp', 'product' => 'round-bas-roglam'],
                        ['image' => 'hero-3.webp', 'product' => 'knitted-polo-w26'],
                        ['image' => 'hero-4.webp', 'product' => 't-round-relaxed'],
                    ] as $index => $slide)
                        <div class="keswa-home-hero-slide {{ $index === 0 ? 'is-active' : '' }}" data-hero-slide>
                            <img
                                src="{{ asset('images/home-banners/'.$slide['image']) }}"
                                alt="Keswa Sale collection {{ $index + 1 }}"
                                loading="eager"
                                fetchpriority="{{ $index === 0 ? 'high' : 'auto' }}"
                            >
                            <a class="keswa-home-hero-sale" href="{{ route('shop.product_or_category.index', $slide['product']) }}">Sale <span aria-hidden="true">›</span></a>
                        </div>
                    @endforeach
                    </div>

                    <div class="keswa-home-hero-dots" aria-label="Choose banner">
                        @foreach (range(0, 3) as $index)
                            <button class="keswa-home-hero-dot {{ $index === 0 ? 'is-active' : '' }}" type="button" aria-label="Show banner {{ $index + 1 }}" data-hero-dot="{{ $index }}"></button>
                        @endforeach
                    </div>

                    <div class="keswa-home-marquee" aria-label="Since 2012 – We Begin the Journey Together">
                        <div class="keswa-home-marquee-track" aria-hidden="true">
                            @foreach (range(1, 8) as $item)
                                <span>Since 2012 – We Begin the Journey Together.</span>
                            @endforeach
                        </div>
                    </div>
                </section>

                @php ($hideNextHomeDescription = true) @endphp
                @php ($homeHeroRendered = true) @endphp

                <script>
                    (() => {
                        const initializeHero = () => setTimeout(() => {
                            const hero = document.querySelector('[data-keswa-home-hero]');

                            if (! hero) return;

                            const slides = [...hero.querySelectorAll('[data-hero-slide]')];
                            const dots = [...hero.querySelectorAll('[data-hero-dot]')];
                            const track = hero.querySelector('[data-hero-track]');
                            const header = document.querySelector('#app > header');
                            let current = 0;
                            let timer;

                            if (! track || ! slides.length || ! dots.length) return;

                            const show = (index) => {
                                current = (index + slides.length) % slides.length;
                                slides.forEach((slide, slideIndex) => slide.classList.toggle('is-active', slideIndex === current));
                                dots.forEach((dot, dotIndex) => {
                                    const active = dotIndex === current;
                                    dot.classList.toggle('is-active', active);
                                    dot.setAttribute('aria-pressed', String(active));
                                });
                                track.style.transform = `translate3d(-${current * 100}%, 0, 0)`;
                            };

                            const play = () => {
                                clearInterval(timer);
                                timer = setInterval(() => show(current + 1), 5000);
                            };

                            dots.forEach((dot) => dot.addEventListener('click', () => {
                                show(Number(dot.dataset.heroDot));
                                play();
                            }));

                            const updateHeader = () => header?.classList.toggle('is-scrolled', window.scrollY > 40);

                            updateHeader();
                            window.addEventListener('scroll', updateHeader, { passive: true });

                            show(0);
                            play();
                        }, 0);

                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', initializeHero, { once: true });
                        } else {
                            initializeHero();
                        }
                    })();
                </script>

                @break
            @case ($customization::STATIC_CONTENT)
                <!-- Push Style -->
                @if (! $hideNextHomeDescription && ! empty($data['css']))
                    @push ('styles')
                        <style>
                            {!! $data['css'] !!}
                        </style>
                    @endpush
                @endif

                <!-- Render HTML -->
                @if (! $hideNextHomeDescription && ! empty($data['html']))
                    {!! $data['html'] !!}
                @endif

                @php ($hideNextHomeDescription = false) @endphp

                @break
            @case ($customization::CATEGORY_CAROUSEL)
                <!-- Categories carousel -->
                <x-shop::categories.carousel
                    :title="$data['title'] ?? ''"
                    :src="route('shop.api.categories.index', $data['filters'] ?? [])"
                    :navigation-link="route('shop.home.index')"
                    aria-label="{{ trans('shop::app.home.index.categories-carousel') }}"
                />

                @break
            @case ($customization::PRODUCT_CAROUSEL)
                <!-- Product Carousel -->
                <x-shop::products.carousel
                    :title="$data['title'] ?? ''"
                    :src="route('shop.api.products.index', $data['filters'] ?? [])"
                    :navigation-link="route('shop.search.index', $data['filters'] ?? [])"
                    aria-label="{{ trans('shop::app.home.index.product-carousel') }}"
                />

                @break
        @endswitch
        @endif
    @endforeach

    <section class="keswa-home-categories" aria-labelledby="keswa-home-categories-title">
        <div class="keswa-home-categories-heading">
            <div class="keswa-home-categories-controls">
                <button class="keswa-home-categories-arrow" type="button" aria-label="Previous categories" onclick="this.closest('.keswa-home-categories').querySelector('.keswa-home-categories-track').scrollBy({ left: -this.closest('.keswa-home-categories').querySelector('.keswa-home-categories-track').clientWidth, behavior: 'smooth' })">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M19 12H5m0 0 7-7m-7 7 7 7" /></svg>
                </button>
                <button class="keswa-home-categories-arrow" type="button" aria-label="Next categories" onclick="this.closest('.keswa-home-categories').querySelector('.keswa-home-categories-track').scrollBy({ left: this.closest('.keswa-home-categories').querySelector('.keswa-home-categories-track').clientWidth, behavior: 'smooth' })">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M5 12h14m0 0-7-7m7 7-7 7" /></svg>
                </button>
            </div>
            <h2 class="keswa-home-categories-title" id="keswa-home-categories-title">SHOP BY CATEGORIES</h2>
        </div>

        <div class="keswa-home-categories-track">
            <a class="keswa-home-category-card" href="{{ route('shop.product_or_category.index', 'jackets') }}" aria-label="Jackets">
                <!-- Add the Jackets image here when ready. -->
                <span class="keswa-home-category-label">Jackets →</span>
            </a>
            <a class="keswa-home-category-card" href="{{ route('shop.product_or_category.index', 'jeans') }}" aria-label="Jeans">
                <!-- Add the Jeans image here when ready. -->
                <span class="keswa-home-category-label">Jeans →</span>
            </a>
            <a class="keswa-home-category-card" href="{{ route('shop.product_or_category.index', 'winter-sale1') }}" aria-label="Winter Sale">
                <!-- Add the Winter Sale image here when ready. -->
                <span class="keswa-home-category-label">Winter-SALE →</span>
            </a>
            <a class="keswa-home-discover-card" href="{{ route('shop.search.index') }}">
                <span class="keswa-home-discover-content">
                    <span class="keswa-home-discover-title">Discover all<br>new items</span>
                    <span class="keswa-home-discover-arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M7 17 17 7M8 7h9v9" /></svg>
                    </span>
                </span>
            </a>
        </div>
    </section>

    <v-home-bestsellers
        src="{{ route('shop.api.products.index', ['limit' => 12]) }}"
        title="Best Seller"
        subtitle="Inspire and let yourself be inspired, from one unique fashion to another."
        :initial-count="4"
        class="block"
    ></v-home-bestsellers>

    <v-home-bestsellers
        src="{{ route('shop.api.products.index', ['limit' => 24, 'sort' => 'created_at-desc']) }}"
        title="New Products"
        subtitle="Inspire and let yourself be inspired, from one unique fashion to another."
        :initial-count="8"
        class="block"
    ></v-home-bestsellers>

    <section class="keswa-home-gram" aria-labelledby="keswa-home-gram-title">
        <div class="keswa-home-gram-heading">
            <h2 class="keswa-home-gram-title" id="keswa-home-gram-title">Shop Gram</h2>
            <p class="keswa-home-gram-subtitle">Inspire and let yourself be inspired, from one unique fashion to another.</p>
        </div>

        <div class="keswa-home-gram-grid">
            @foreach ([
                'storage/product/118/u8UZl0WaOeldaqD80FI5fqEspDiigD1nqg0sXjVt.webp',
                'storage/product/30/A6ZVxrSV88yJl7QGGODhdbK1U3G6PE6npuuByzsx.webp',
                null,
                null,
                null,
            ] as $index => $image)
                <a class="keswa-home-gram-item" href="https://instagram.com/keswa.stores" target="_blank" rel="noopener noreferrer" aria-label="Visit Keswa on Instagram, photo {{ $index + 1 }}">
                    @if ($image)
                        <img class="{{ $index === 0 ? 'gram-focus-tshirt' : 'gram-focus-shirt' }}" src="{{ asset($image) }}" alt="{{ $index === 0 ? 'Keswa T-Shirt' : 'Keswa Shirt' }}" loading="lazy">
                    @else
                        <!-- Add a Shop Gram photo here. -->
                    @endif
                    <span class="keswa-home-gram-actions" aria-hidden="true">
                        <span class="keswa-home-gram-action"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg></span>
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="keswa-home-promises" aria-label="Keswa shopping services">
        <article class="keswa-home-promise">
            <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 15h25v21H4zM29 22h8l7 8v6H29z"/><circle cx="13" cy="37" r="4"/><circle cx="36" cy="37" r="4"/><path d="M8 21h12M8 26h9"/></svg>
            <h3>Shipping</h3>
            <p>We deliver to all governorates and cities across Egypt through reliable partners.</p>
        </article>

        <article class="keswa-home-promise">
            <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 12 7 20l9 8M7 20h26M32 36l9-8-9-8M41 28H15"/></svg>
            <h3>Easy Exchange</h3>
            <p>Product replacement is available within 14 days under our exchange policy.</p>
        </article>

        <article class="keswa-home-promise">
            <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="8" width="38" height="29" rx="3"/><path d="M5 16h38M11 30h8"/><path d="m33 24 8 3v6c0 5-4 8-8 10-4-2-8-5-8-10v-6z"/><path d="m30 33 2 2 4-4"/></svg>
            <h3>Secure Payment</h3>
            <p>All payments are processed securely through trusted and encrypted gateways.</p>
        </article>

        <article class="keswa-home-promise">
            <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m24 4 5 4 7-1 1 7 6 4-3 6 1 7-7 2-4 6-6-3-7 2-2-7-6-4 3-6-1-7 7-2z"/><circle cx="24" cy="23" r="9"/><path d="m20 23 3 3 6-6M20 34l-2 10 6-3 6 3-2-10"/></svg>
            <h3>Quality Commitment</h3>
            <p>Crafted with care for lasting quality and everyday wear.</p>
        </article>
    </section>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-home-bestsellers-template">
            <section class="keswa-home-bestsellers" :aria-labelledby="'keswa-home-section-' + title.toLowerCase().replaceAll(' ', '-')" v-if="products.length">
                <div class="keswa-home-bestsellers-heading">
                    <h2 class="keswa-home-bestsellers-title" :id="'keswa-home-section-' + title.toLowerCase().replaceAll(' ', '-')">@{{ title }}</h2>
                    <p class="keswa-home-bestsellers-subtitle">@{{ subtitle }}</p>
                </div>

                <div class="keswa-home-bestsellers-grid">
                    <x-shop::products.card
                        v-for="product in visibleProducts"
                        ::key="product.id"
                    />
                </div>

                <button
                    class="keswa-home-bestsellers-view-all"
                    type="button"
                    @click="showAll = ! showAll"
                    v-if="products.length > initialCount"
                >
                    @{{ showAll ? 'Show Less' : 'View All' }}
                </button>
            </section>
        </script>

        <script type="module">
            app.component('v-home-bestsellers', {
                template: '#v-home-bestsellers-template',

                props: ['src', 'title', 'subtitle', 'initialCount'],

                data() {
                    return {
                        products: [],
                        showAll: false,
                    };
                },

                computed: {
                    visibleProducts() {
                        return this.showAll ? this.products : this.products.slice(0, this.initialCount);
                    },
                },

                mounted() {
                    this.$axios.get(this.src)
                        .then(response => {
                            this.products = response.data.data || [];
                        })
                        .catch(error => console.error(error));
                },
            });
        </script>
    @endPushOnce
</x-shop::layouts>
