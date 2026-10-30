@props([
    'hasHeader'  => true,
    'hasFeature' => true,
    'hasFooter'  => true,
])

<!DOCTYPE html>

<html
    lang="{{ app()->getLocale() }}"
    dir="{{ core()->getCurrentLocale()->direction }}"
>
    <head>

        {!! view_render_event('bagisto.shop.layout.head.before') !!}

        <title>{{ $title ?? '' }}</title>

        <meta charset="UTF-8">

        <meta
            http-equiv="X-UA-Compatible"
            content="IE=edge"
        >
        <meta
            http-equiv="content-language"
            content="{{ app()->getLocale() }}"
        >

        <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
        >
        <meta
            name="base-url"
            content="{{ url()->to('/') }}"
        >
        <meta
            name="currency"
            content="{{ core()->getCurrentCurrency()->toJson() }}"
        >
        <meta 
            name="generator" 
            content="Bagisto"
        >

        @stack('meta')

        <link
            rel="icon"
            sizes="16x16"
            href="{{ core()->getCurrentChannel()->favicon_url ?? bagisto_asset('images/favicon.ico') }}"
        />

        @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])

        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        >

        <link
            rel="preconnect"
            href="https://fonts.googleapis.com"
            crossorigin
        />

        <link
            rel="preconnect"
            href="https://fonts.gstatic.com"
            crossorigin
        />

        <link
            rel="preload" as="style"
            href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=DM+Serif+Display&display=swap"
        />

        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=DM+Serif+Display&display=swap"
        />

        @stack('styles')

        <style>
            {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}

            input[type="email"],
            input[type="password"] {
                background-color: #fff !important;
            }

            input[type="email"]::placeholder,
            input[type="password"]::placeholder {
                color: #9ca3af !important;
                font-size: 14px !important;
                opacity: 1;
            }

            input[type="email"]:-webkit-autofill,
            input[type="password"]:-webkit-autofill {
                -webkit-box-shadow: 0 0 0 1000px #fff inset !important;
                -webkit-text-fill-color: #111827 !important;
            }

            .whatsapp-floating-button {
                z-index: 15 !important;
                transition: transform 0.3s ease-in-out, background-color 0.3s ease-in-out, color 0.3s ease-in-out;
            }

            body.mini-cart-open .whatsapp-floating-button {
                display: none !important;
            }

            body.mobile-menu-open .whatsapp-floating-button,
            body.mobile-menu-open .mobile-bottom-navigation,
            body.mobile-menu-open .mobile-product-actions,
            body.mini-cart-open .mobile-product-actions,
            body.mobile-search-open .whatsapp-floating-button,
            body.mobile-search-open .mobile-bottom-navigation,
            body.mobile-search-open .mobile-product-actions {
                display: none !important;
            }

            /* Keep the mobile mini-cart drawer mounted: it lives inside the bottom navigation. */
            body.mini-cart-open .mobile-bottom-navigation {
                background: transparent;
                pointer-events: none;
            }

            body.mini-cart-open .mobile-bottom-navigation > li:not(.mobile-bottom-cart-item),
            body.mini-cart-open .mobile-bottom-cart-item .mini-cart-drawer > div:first-child {
                visibility: hidden;
            }

            body.mini-cart-open .mobile-bottom-cart-item {
                pointer-events: auto;
            }

            .whatsapp-floating-button:hover {
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
                background-color: white;
                color: black;
            }

            .account-menu-dropdown .account-menu-panel {
                width: 370px !important;
                max-width: calc(100vw - 24px) !important;
                border-radius: 6px !important;
            }

            .account-menu-dropdown .account-menu-card {
                width: 100% !important;
                max-width: 100% !important;
                padding: 24px !important;
            }

            .account-menu-dropdown .account-menu-copy {
                display: grid;
                gap: 15px;
            }

            .account-menu-dropdown .account-menu-title {
                margin: 0;
                font-size: 26px;
                font-weight: 500;
                line-height: 1.2;
            }

            .account-menu-dropdown .account-menu-description {
                margin: 0;
                font-size: 18px;
                line-height: 1.3;
            }

            .account-menu-dropdown .account-menu-divider {
                margin: 18px 0 24px;
                border-top: 1px solid #d4d4d8;
            }

            .account-menu-dropdown .account-menu-actions {
                display: flex;
                gap: 18px;
            }

            .account-menu-dropdown .account-menu-actions a {
                display: flex;
                height: 64px;
                flex: 1 1 0;
                align-items: center;
                justify-content: center;
                border-radius: 2px;
                padding: 0 12px;
                font-size: 20px;
                font-weight: 600;
                line-height: 1;
                white-space: nowrap;
            }

            .mini-cart-drawer > div > div[style*="width"] {
                max-width: 100vw;
            }

            .mini-cart-drawer .mini-cart-header {
                padding: 20px 24px 16px;
                color: #000;
            }

            .mini-cart-drawer .mini-cart-header > div:first-child p {
                color: #000;
                font-size: 20px;
                line-height: 1.25;
            }

            .mini-cart-drawer .mini-cart-title {
                color: #000 !important;
            }

            .mini-cart-drawer .mini-cart-subtitle {
                color: #000 !important;
                font-size: 14px;
                line-height: 1.4;
            }

            .mini-cart-drawer .mini-cart-items {
                margin-top: 20px;
                gap: 0;
            }

            .mini-cart-drawer .mini-cart-empty-state {
                display: grid;
                min-height: max(420px, calc(100vh - 260px));
                place-items: center;
                padding: 20px 24px 40px;
            }

            .mini-cart-drawer .mini-cart-empty-content {
                display: grid;
                justify-items: center;
                gap: 24px;
                color: #000;
                text-align: center;
            }

            .mini-cart-drawer .mini-cart-empty-bag {
                display: block;
                width: 170px;
                height: 178px;
            }

            .mini-cart-drawer .mini-cart-empty-content p {
                margin: 0;
                font-size: 19px;
                line-height: 1.3;
            }

            .mini-cart-drawer .mini-cart-item {
                position: relative;
                gap: 18px;
                padding: 16px 0;
            }

            .mini-cart-drawer .mini-cart-item + .mini-cart-item::before {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                border-top: 1px solid #d4d4d8;
                content: '';
            }

            .mini-cart-drawer .mini-cart-product-image {
                display: block;
                width: 96px;
                min-width: 96px;
                height: 136px;
                object-fit: cover;
            }

            .mini-cart-drawer .mini-cart-info {
                min-height: 136px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                gap: 8px;
            }

            .mini-cart-drawer .mini-cart-product-name,
            .mini-cart-drawer .mini-cart-product-price {
                color: #000;
                font-weight: 600;
            }

            .mini-cart-drawer .mini-cart-product-name { font-size: 14px; }
            .mini-cart-drawer .mini-cart-product-price { font-size: 16px; }

            .mini-cart-drawer .mini-cart-footer {
                padding-bottom: 14px;
            }

            .mini-cart-drawer .mini-cart-subtotal {
                margin: 0 0 14px;
                border-top: 1px solid #e4e4e7;
                border-bottom: 1px solid #e4e4e7;
                padding: 10px 24px;
            }

            .mini-cart-drawer .mini-cart-subtotal > p:first-child {
                font-size: 16px;
            }

            .mini-cart-drawer .mini-cart-subtotal > p:last-child {
                font-size: 24px;
            }

            .mini-cart-drawer .mini-cart-actions {
                gap: 8px;
                padding: 0 24px;
            }

            .mini-cart-drawer .mini-cart-actions > a,
            .mini-cart-drawer .mini-cart-actions > div {
                min-height: 54px;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 10px 16px;
                font-size: 16px;
            }

            .mini-cart-drawer .mini-cart-actions > a {
                border-radius: 2px;
            }

            .cart-page-content {
                align-items: flex-start;
                gap: 90px;
            }

            .cart-items-column {
                min-width: 0;
            }

            .cart-page-item-image {
                display: block;
                width: 110px !important;
                max-width: 110px !important;
                height: 110px !important;
                object-fit: cover;
            }

            .cart-page-control-button {
                display: inline-flex;
                min-height: 56px;
                align-items: center;
                justify-content: center;
                border: 1px solid #1b1b1b !important;
                border-radius: 2px !important;
                background: #fff !important;
                color: #000 !important;
                font-weight: 600;
            }

            .cart-page-quantity {
                border-color: #1b1b1b !important;
                border-radius: 2px;
                gap: 0.625rem !important;
            }

            .cart-page-item-info {
                align-self: stretch;
            }

            .cart-summary-card {
                flex: 0 0 420px;
                min-height: 475px;
                border: 1px solid #d4d4d8;
                border-radius: 4px;
                padding: 28px;
            }

            .cart-login-link {
                color: #af8234 !important;
            }

            .cart-summary-card .cart-summary-title {
                color: #000;
                font-size: 24px;
                font-weight: 600;
            }

            .cart-delivery-note > p {
                white-space: nowrap;
            }

            .cart-delivery-note > p:first-child {
                font-size: 17px;
                font-weight: 500;
            }

            .cart-delivery-note > p:last-child {
                color: #71717a;
                font-size: 12px;
            }

            .cart-summary-card .cart-coupon-form {
                display: flex;
                width: 100%;
                gap: 10px;
            }

            .cart-summary-card .cart-coupon-form input {
                height: 56px;
                border-radius: 3px;
            }

            .cart-summary-card .cart-coupon-apply {
                min-width: 120px;
                width: 120px;
                flex: 0 0 120px;
                border-radius: 3px;
                background: #000 !important;
                color: #fff !important;
                font-weight: 600;
            }

            .cart-summary-card .cart-coupon-form .cart-coupon-apply {
                width: 120px !important;
                flex: 0 0 120px;
            }

            .cart-summary-card .cart-proceed-button {
                display: flex;
                width: 100% !important;
                max-width: none !important;
                align-items: center;
                justify-content: center;
                border-radius: 2px;
                background: #1b1b1b;
                color: #fff;
                justify-self: stretch;
                box-sizing: border-box;
            }

            .mobile-drawer-account-hero {
                padding: 12px !important;
            }

            .mobile-drawer-account-card {
                gap: 12px !important;
                padding: 8px !important;
                background: rgb(250 250 250) !important;
                transition: background-color 150ms ease;
            }

            .mobile-drawer-account-card:hover {
                background: rgb(240 240 240) !important;
            }

            .mobile-drawer-account-avatar {
                width: 48px !important;
                height: 48px !important;
            }

            .mobile-drawer-login-link {
                display: flex;
                width: 100%;
                align-items: center;
                justify-content: space-between;
                font-size: 15px;
                font-weight: 700;
            }

            .mobile-drawer-login-link .icon-double-arrow {
                flex: none;
                margin: 0 !important;
                font-size: 22px;
            }

            @media (max-width: 1060px) {
                #mobile-search-panel {
                    position: relative !important;
                    z-index: 11 !important;
                    min-height: 76px;
                    padding: 12px 16px !important;
                    background-color: #fff !important;
                    opacity: 1 !important;
                    isolation: isolate;
                }

                #mobile-search-panel input {
                    min-height: 48px;
                    padding: 12px 14px 12px 56px !important;
                    border-color: #e3e3e3;
                    background: #fafafa;
                    font-size: 14px;
                }

                #mobile-search-panel .icon-search {
                    top: 50% !important;
                    left: 16px !important;
                    right: auto !important;
                    z-index: 1;
                    transform: translateY(-50%);
                    font-size: 20px !important;
                }

                [dir="rtl"] #mobile-search-panel input {
                    padding-right: 56px !important;
                    padding-left: 14px !important;
                }

                [dir="rtl"] #mobile-search-panel .icon-search {
                    right: 16px !important;
                    left: auto !important;
                }
            }

            .mobile-drawer-category-row,
            .mobile-drawer-subcategory-row {
                border-radius: 4px;
                transition: background-color 150ms ease;
            }

            .mobile-drawer-category-row {
                padding-top: 7px !important;
                padding-bottom: 7px !important;
            }

            .mobile-drawer-subcategory-row {
                padding-top: 5px !important;
                padding-bottom: 5px !important;
            }

            .mobile-drawer-category-name {
                font-weight: 600 !important;
            }

            .mobile-drawer-subcategory-name {
                font-weight: 600 !important;
            }

            @media (max-width: 1060px) {
                .cart-page-content {
                    align-items: stretch;
                    gap: 28px;
                }

                .cart-summary-card {
                    width: 100%;
                    min-height: 0;
                    flex-basis: auto;
                }

                .cart-delivery-note > p {
                    white-space: normal;
                }
            }

            @media (max-width: 767px) {
                .mini-cart-drawer .mini-cart-header {
                    padding: 16px 20px;
                }

                .mini-cart-drawer .mini-cart-subtitle {
                    font-size: 14px;
                }

                .mini-cart-drawer .mini-cart-items {
                    margin-top: 14px;
                    gap: 0;
                }

                .mini-cart-drawer .mini-cart-empty-state {
                    min-height: calc(100vh - 210px);
                    padding: 12px 16px 28px;
                }

                .mini-cart-drawer .mini-cart-empty-bag {
                    width: 140px;
                    height: 146px;
                }

                .mini-cart-drawer .mini-cart-empty-content p {
                    font-size: 16px;
                }

                .mini-cart-drawer .mini-cart-item {
                    gap: 16px;
                    padding: 12px 0;
                }

                .mini-cart-drawer .mini-cart-product-image {
                    width: 88px;
                    min-width: 88px;
                    height: 124px;
                }

                .mini-cart-drawer .mini-cart-info {
                    min-height: 124px;
                }

                .mini-cart-drawer .mini-cart-footer {
                    padding-bottom: 8px;
                }

                .mini-cart-drawer .mini-cart-subtotal {
                    margin-bottom: 8px;
                    padding: 8px 20px;
                }

                .mini-cart-drawer .mini-cart-subtotal > p:first-child {
                    font-size: 14px;
                }

                .mini-cart-drawer .mini-cart-subtotal > p:last-child {
                    font-size: 16px;
                }

                .mini-cart-drawer .mini-cart-actions {
                    gap: 6px;
                    padding: 0 16px;
                }

                .mini-cart-drawer .mini-cart-actions > a,
                .mini-cart-drawer .mini-cart-actions > div {
                    min-height: 48px;
                    padding: 8px 12px;
                    font-size: 16px;
                }

                .cart-page-content {
                    gap: 24px;
                }

                .cart-page-item-image {
                    width: 80px !important;
                    max-width: 80px !important;
                    height: 80px !important;
                }

                .cart-page-control-button {
                    min-height: 48px;
                    border-radius: 2px !important;
                }

                .cart-summary-card {
                    padding: 18px;
                }

                .cart-summary-card .cart-summary-title {
                    font-size: 20px;
                }

                .cart-summary-card .cart-coupon-form {
                    gap: 8px;
                }

                .cart-summary-card .cart-coupon-form input {
                    height: 48px;
                }

                .cart-summary-card .cart-coupon-apply {
                    min-width: 82px;
                    width: 82px;
                    padding: 8px 12px;
                }

                .cart-summary-card .cart-coupon-form .cart-coupon-apply {
                    width: 82px !important;
                    min-width: 82px;
                    flex-basis: 82px;
                }

                .cart-delivery-note {
                    align-items: flex-start;
                    flex-direction: column;
                    gap: 2px;
                }

                .cart-delivery-note > p:first-child {
                    font-size: 16px;
                }

                .cart-delivery-note > p:last-child {
                    font-size: 12px;
                }
            }

            .account-menu-dropdown .account-menu-sign-in {
                background: #1b1b1b;
                color: #fff;
            }

            html body a.account-menu-sign-up {
                border: 2px solid #1b1b1b !important;
                background: rgb(250 250 250) !important;
                color: #1b1b1b !important;
                transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
            }

            html body a.account-menu-sign-up:hover {
                border-color: #1b1b1b !important;
                background: rgb(220 220 220) !important;
                color: #1b1b1b !important;
            }

            @media (max-width: 1060px) {
                .mobile-bottom-cart-item .icon-cart {
                    font-size: 2rem !important;
                }
            }

            .cart-sign-in-panel {
                max-width: 680px !important;
                border-radius: 10px !important;
                background: #fff !important;
            }

            .cart-sign-in-modal-header {
                padding: 24px 32px !important;
                color: #000;
            }

            .cart-sign-in-modal-content {
                padding: 28px 32px !important;
            }

            .cart-sign-in-modal-footer {
                margin-top: 0 !important;
                border-top: 1px solid #e4e4e7;
                padding: 18px 32px !important;
            }

            .cart-sign-in-modal-footer > div,
            .cart-sign-in-modal-footer button {
                width: 100%;
            }

            .cart-sign-in-modal-footer button {
                min-height: 56px;
                border-radius: 2px !important;
                background: #1b1b1b !important;
                color: #fff !important;
            }

            @media (max-width: 767px) {
                .cart-sign-in-modal-header {
                    padding: 18px 20px !important;
                }

                .cart-sign-in-modal-content {
                    padding: 20px !important;
                }

                .cart-sign-in-modal-footer {
                    padding: 16px 20px !important;
                }
            }

            @media (max-width: 525px) {
                .account-menu-dropdown .account-menu-card {
                    padding: 18px !important;
                }

                .account-menu-dropdown .account-menu-title {
                    font-size: 22px;
                }

                .account-menu-dropdown .account-menu-description {
                    font-size: 16px;
                }

                .account-menu-dropdown .account-menu-divider {
                    margin: 16px 0 24px;
                }

                .account-menu-dropdown .account-menu-actions {
                    gap: 12px;
                }

                .account-menu-dropdown .account-menu-actions a {
                    height: 56px;
                    font-size: 16px;
                }
            }

            .desktop-search-dialog {
                position: fixed !important;
                inset: 0 !important;
                width: 100% !important;
                height: 100% !important;
                max-width: none !important;
                max-height: none !important;
                margin: 0 !important;
                overflow: visible;
                border: 0 !important;
                padding: 0 !important;
                background: transparent !important;
            }

            .desktop-search-dialog::backdrop {
                background: rgb(0 0 0 / 25%);
            }

            .desktop-search-dialog-panel {
                position: relative;
                width: 100%;
                height: 180px;
                background: #fff;
            }

            .desktop-search-dialog-close {
                position: absolute;
                display: flex;
                top: 20px;
                right: 24px;
                width: 33px;
                height: 33px;
                align-items: center;
                justify-content: center;
                padding: 0;
                border: 0;
                background: transparent;
                color: #000;
            }

            .desktop-search-dialog-form {
                position: absolute;
                top: 86px;
                left: 50%;
                display: flex;
                width: min(64vw, 1228px);
                box-sizing: border-box;
                height: 46px !important;
                align-items: center;
                transform: translateX(-50%);
                border-radius: 10px;
                background: #f4f4f5;
                padding: 0 20px;
                transition: border-color 150ms ease, box-shadow 150ms ease;
                border: 1px solid transparent;
            }

            .desktop-search-dialog-form:hover,
            .desktop-search-dialog-form:focus-within {
                border-color: #a1a1aa;
                box-shadow: 0 0 0 1px rgb(161 161 170 / 20%);
            }

            .desktop-search-dialog-icon {
                flex: none;
                margin-right: 20px;
                font-size: 24px;
            }

            .desktop-search-dialog-input {
                display: block;
                width: 100%;
                border: 0;
                outline: 0;
                background: transparent;
                font-size: 16px;
                font-weight: 500;
            }

            .desktop-search-dialog-input::placeholder {
                color: #9ca3af;
                font-size: 14px;
                opacity: 1;
            }

            .desktop-search-dialog-form v-image-search,
            .desktop-search-dialog-form .icon-camera {
                display: none !important;
            }

            @media (max-width: 767px) {
                .desktop-search-dialog-form {
                    width: 90vw;
                }

                .desktop-search-dialog-close {
                    right: 16px;
                }
            }
        </style>

        @if(core()->getConfigData('general.content.speculation_rules.enabled'))
            <script type="speculationrules">
                @json(core()->getSpeculationRules(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            </script>
        @endif

        {!! view_render_event('bagisto.shop.layout.head.after') !!}

    </head>

    <body>
        {!! view_render_event('bagisto.shop.layout.body.before') !!}

        <a
            href="#main"
            class="skip-to-main-content-link"
        >
            Skip to main content
        </a>

        <!-- Built With Bagisto -->
        <div id="app">
            <!-- Flash Message Blade Component -->
            <x-shop::flash-group />

            <!-- Confirm Modal Blade Component -->
            <x-shop::modal.confirm />

            <!-- Page Header Blade Component -->
            @if ($hasHeader)
                <x-shop::layouts.header />
            @endif

            <div
                id="mobile-search-panel"
                class="hidden w-full border-b border-zinc-200 bg-white p-4 shadow-lg"
            >
                <div class="mx-auto flex max-w-3xl items-center gap-3">
                    <form action="{{ route('shop.search.index') }}" class="relative flex-1">
                        <label for="mobile-search-input" class="sr-only">
                            @lang('shop::app.components.layouts.header.mobile.search')
                        </label>

                        <span class="icon-search pointer-events-none absolute top-3 text-2xl ltr:left-3 rtl:right-3"></span>

                        <input
                            id="mobile-search-input"
                            type="search"
                            name="query"
                            value="{{ request('query') }}"
                            placeholder="@lang('shop::app.components.layouts.header.mobile.search-text')"
                            class="w-full rounded-lg border border-zinc-300 py-3 ltr:pl-14 ltr:pr-3 rtl:pr-14 rtl:pl-3"
                            required
                        >
                    </form>
                </div>
            </div>

            @if(
                core()->getConfigData('general.gdpr.settings.enabled')
                && core()->getConfigData('general.gdpr.cookie.enabled')
            )
                <x-shop::layouts.cookie />
            @endif

            {!! view_render_event('bagisto.shop.layout.content.before') !!}

            <!-- Page Content Blade Component -->
            <main id="main" class="bg-white">
                {{ $slot }}
            </main>

            {!! view_render_event('bagisto.shop.layout.content.after') !!}


            <!-- Page Services Blade Component -->
            @if ($hasFeature)
                <x-shop::layouts.services />
            @endif

            <!-- Page Footer Blade Component -->
            @if ($hasFooter)
                <x-shop::layouts.footer />
            @endif
            @if (request()->routeIs('shop.home.index', 'shop.product_or_category.index'))
                <!-- واتس اب -->
        <a
            href="https://wa.me/201011387451"
            class="whatsapp-floating-button fixed bottom-[70px] z-[9999] flex items-center
            justify-center rounded-full bg-black text-white shadow-lg max-1060:bottom-[150px]"
            style="right: 20px; width: 55px; height: 55px;"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="تواصل معنا عبر واتساب"
        >
            <svg
                width="38"
                height="38"
                viewBox="0 0 448 512"
                fill="currentColor"
                aria-hidden="true"
            >
                <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157Zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6Zm101.5-138c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8s-14.4 18-17.6 21.8c-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 13.2 5.7 23.5 9.1 31.5 11.7 13.2 4.2 25.2 3.6 34.7 2.2 10.6-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6Z" />
            </svg>
        </a>
            @endif

        <!-- Mobile bottom navigation -->
        @if (request()->routeIs('shop.home.index', 'shop.product_or_category.index'))
        <ul class="mobile-bottom-navigation fixed bottom-0 z-[9999] hidden w-full items-center justify-between bg-white px-5 py-3 text-lg max-1060:flex">
            <li><a href="{{ route('shop.home.index') }}" class="flex p-2" aria-label="Home"><i class="text-2xl hover:text-grey fa-solid fa-house" style="-webkit-text-fill-color: white; -webkit-text-stroke: 1.5px currentColor;"></i></a></li>
            <li>
                <button
                    type="button"
                    class="flex p-2"
                    aria-label="Search"
                    aria-controls="mobile-search-panel"
                    onclick="const panel = document.getElementById('mobile-search-panel'); const opening = panel.classList.contains('hidden'); panel.classList.toggle('hidden', ! opening); document.body.classList.toggle('mobile-search-open', opening); if (opening) document.getElementById('mobile-search-input').focus()"
                >
                    <i class="text-2xl hover:text-grey fa-solid fa-magnifying-glass"></i>
                </button>
            </li>

            <li class="mobile-bottom-cart-item">
                @if(core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                    @include('shop::checkout.cart.mini-cart')
                @endif
            </li>

            <li><a href="{{ route('shop.customers.account.wishlist.index') }}" class="flex p-2" aria-label="Wishlist"><i class="text-2xl hover:text-grey fa-regular fa-heart"></i></a></li>

            <li>
                <a
                    href="{{ auth()->guard('customer')->check() ? route('shop.customers.account.index') : route('shop.customer.session.index') }}"
                    class="flex p-2"
                    aria-label="@lang('shop::app.components.layouts.header.mobile.account')"
                >
                    <i class="text-3xl hover:text-grey icon-users"></i>
                </a>
            </li>
        </ul>
        @endif

        </div>

        <script type="text/x-template" id="v-mobile-cart-badge-template">
            <span
                v-if="quantity > 0"
                v-text="quantity"
                class="absolute top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-center text-[10px] font-semibold leading-none text-white ltr:right-0 rtl:left-0"
            ></span>
        </script>

        <script type="module">
            app.component('v-mobile-cart-badge', {
                template: '#v-mobile-cart-badge-template',

                data() {
                    return {
                        quantity: {{ \Webkul\Checkout\Facades\Cart::getCart()?->items->count() ?? 0 }},
                    };
                },

                mounted() {
                    this.$emitter.on('update-mini-cart', cart => {
                        this.quantity = cart?.items_count ?? cart?.items?.length ?? cart?.items_qty ?? 0;
                    });
                },
            });
        </script>

        {!! view_render_event('bagisto.shop.layout.body.after') !!}

        <!-- WebMCP Tool Registration For AI Agents -->
        <x-shop::layouts.webmcp />

        @stack('scripts')

        {!! view_render_event('bagisto.shop.layout.vue-app-mount.before') !!}
        <script>
            /**
             * Mount the application as soon as the DOM is ready instead of waiting
             * for the `load` event. All `Vue` components are registered through
             * deferred `type="module"` scripts, which always finish executing
             * before `DOMContentLoaded` fires, so every component is available
             * by the time `app.mount()` runs. Mounting on `DOMContentLoaded`
             * avoids blocking the storefront behind every image/font download.
             */
            function mountApp() {
                app.mount("#app");
            }

            if (document.readyState === "loading") {
                document.addEventListener("DOMContentLoaded", mountApp);
            } else {
                mountApp();
            }
        </script>

        {!! view_render_event('bagisto.shop.layout.vue-app-mount.after') !!}

        <script type="text/javascript">
            {!! core()->getConfigData('general.content.custom_scripts.custom_javascript') !!}
        </script>
    </body>
</html>
