<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('shop::app.checkout.onepage.index.checkout')"/>

    <meta name="keywords" content="@lang('shop::app.checkout.onepage.index.checkout')"/>
@endPush

<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    @push('styles')
        <style>
            .checkout-page-header {
                min-height: 76px;
                padding: 12px max(4%, calc((100vw - 1760px) / 2));
                border-color: #e5e7eb;
            }

            .checkout-page-header img {
                width: 184px;
                height: auto;
            }

            .checkout-page-header a { font-size: 16px; }

            .checkout-page-header a + a,
            .checkout-page-header a[href*="login"] {
                color: #b8812b;
            }

            .checkout-page-content.container {
                width: 100%;
                max-width: 1760px;
                box-sizing: border-box;
                padding: 0 4%;
            }

            .checkout-page-grid {
                grid-template-columns: minmax(0, 1fr) 578px;
                gap: 48px;
                padding-top: 30px;
            }

            .checkout-page-content { font-size: 16px; }

            .checkout-steps-container {
                overflow: visible;
            }

            .checkout-address-accordion {
                margin-top: 0;
                margin-bottom: 0;
                border: 0;
                border-radius: 0;
            }

            .checkout-address-content {
                margin-top: 62px;
            }

            .checkout-address-content h2 {
                font-size: 22px;
                line-height: 1.3;
                font-weight: 600;
            }

            .checkout-address-accordion > h2 { font-size: 22px; }

            .checkout-address-content label {
                margin-bottom: 8px;
                color: #111;
                font-size: 16px;
            }

            .checkout-address-content input:not([type="checkbox"]),
            .checkout-address-content select {
                height: 54px;
                border: 1px solid #dfe2e8;
                border-radius: 7px;
                padding: 0 18px;
                color: #171717;
                font-size: 16px;
            }

            .checkout-address-content input::placeholder {
                color: #9ba3b2;
                opacity: 1;
            }

            .checkout-summary-shell {
                width: 578px;
                padding: 0;
            }

            .checkout-summary-card {
                border: 1px solid #dedfe3;
                border-radius: 5px;
                padding: 24px;
                background: #fff;
                height: max-content;
                overflow: hidden;
            }

            .checkout-summary-shell { padding-bottom: 48px; }

            .checkout-summary-title {
                font-size: 22px;
                line-height: 1.3;
                font-weight: 600;
            }

            .checkout-summary-items {
                margin-top: 24px;
            }

            .checkout-summary-item {
                gap: 16px;
                padding-bottom: 20px;
            }

            .checkout-summary-item > div { min-width: 0; flex: 1; }

            .checkout-summary-item img {
                width: 76px;
                height: 112px;
                max-width: 76px;
                max-height: 112px;
                border-radius: 5px;
                object-fit: cover;
                flex: 0 0 76px;
            }

            .checkout-summary-product-name {
                padding-top: 8px;
                color: #222;
                font-size: 16px;
                overflow-wrap: anywhere;
            }

            .checkout-summary-product-price {
                margin-top: 12px;
                font-size: 17px;
            }

            .checkout-summary-totals {
                gap: 18px;
                margin-top: 24px;
            }

            .checkout-summary-row,
            .checkout-delivery-row {
                font-size: 16px;
            }

            .cart-coupon-inline,
            .cart-coupon-form {
                display: flex;
                width: 100%;
                align-items: stretch;
                gap: 11px;
            }

            .cart-coupon-form input {
                min-width: 0;
                height: 54px;
                border-color: #dfe2e8;
                border-radius: 2px;
                padding: 0 16px;
                font-size: 16px;
            }

            .cart-coupon-apply {
                min-width: 112px;
                border-radius: 2px;
                background: #111 !important;
                opacity: 1 !important;
                padding: 0 24px;
                color: #fff;
                font-size: 16px;
                font-weight: 600;
            }

            .checkout-continue-payment { background: #111 !important; color: #fff !important; }

            .checkout-continue-payment { margin-bottom: 48px; }

            .checkout-street-fields { grid-template-columns: repeat(3, minmax(0, 1fr)); }

            .checkout-delivery-value {
                color: #555761;
                font-size: 14px;
            }

            .checkout-grand-total {
                font-size: 18px;
                font-weight: 700;
            }

            .checkout-page-content :is(p, label, button, input, select, textarea, a, span) {
                font-size: 14px !important;
            }

            .checkout-page-content :is(h1, h2, h3, h4, h5, h6) {
                font-size: 18px !important;
            }

            @media (max-width: 1200px) {
                .checkout-page-content.container { padding: 0 32px; }
                .checkout-page-grid { grid-template-columns: minmax(0, 1fr) 440px; gap: 32px; }
                .checkout-summary-shell { width: 440px; }
                .checkout-summary-card { padding: 24px; }
            }

            @media (min-width: 1201px) and (max-width: 1450px) {
                .checkout-page-content.container { padding-right: 4%; padding-left: 4%; }
                .checkout-page-grid { grid-template-columns: minmax(0, 1fr) minmax(390px, 40%); gap: 4%; }
                .checkout-summary-shell { width: 100%; }
            }

            @media (max-width: 767px) {
                .checkout-page-header { min-height: 64px; padding: 10px 16px; }
                .checkout-page-header img { width: 145px; }
                .checkout-page-header a { font-size: 14px; }
                .checkout-page-content.container { padding: 0 16px; }
                .checkout-page-grid { display: flex; flex-direction: column; gap: 18px; padding-top: 16px; }
                .checkout-summary-card { padding: 18px; }
                .checkout-address-content { margin-top: 20px; }
                .checkout-address-content h2 { font-size: 18px; }
                .checkout-address-content label { font-size: 14px; }
                .checkout-address-content input:not([type="checkbox"]),
                .checkout-address-content select { height: 48px; padding: 0 14px; font-size: 14px; }
                .checkout-summary-title { font-size: 18px; }
                .checkout-summary-product-name { padding-top: 4px; font-size: 14px; }
                .checkout-summary-product-price { margin-top: 8px; font-size: 14px; }
                .checkout-summary-row,
                .checkout-delivery-row { font-size: 15px; }
                .cart-coupon-apply { min-width: 84px; font-size: 14px; }
                .cart-coupon-form input { height: 48px; padding: 0 12px; font-size: 14px; }
                .checkout-grand-total { font-size: 16px; }
                .checkout-page-content :is(p, label, button, input, select, textarea, a, span) { font-size: 13px !important; }
                .checkout-page-content :is(h1, h2, h3, h4, h5, h6) { font-size: 16px !important; }
                .checkout-continue-payment { margin-bottom: 32px; }
            }
        </style>
    @endpush

    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.checkout.onepage.index.checkout')
    </x-slot>

    {!! view_render_event('bagisto.shop.checkout.onepage.header.before') !!}

    <!-- Page Header -->
    <div class="flex-wrap">
        <div class="checkout-page-header flex w-full justify-between border border-b border-l-0 border-r-0 border-t-0 px-[60px] py-4 max-lg:px-8 max-sm:px-4">
            <div class="flex items-center gap-x-14 max-[1180px]:gap-x-9">
                <a
                    href="{{ route('shop.home.index') }}"
                    class="flex min-h-[30px]"
                    aria-label="@lang('shop::checkout.onepage.index.bagisto')"
                >
                    <img
                        src="{{ bagisto_asset('images/logo.svg', 'shop') }}"
                        alt="Keswa"
                        width="184"
                        height="42"
                    >
                </a>
            </div>

            @guest('customer')
                @include('shop::checkout.login')
            @endguest
        </div>
    </div>

    {!! view_render_event('bagisto.shop.checkout.onepage.header.after') !!}

    <!-- Page Content -->
    <div class="checkout-page-content container px-[60px] max-lg:px-8 max-sm:px-4">

        <!-- Checkout Vue Component -->
        <v-checkout>
            <!-- Shimmer Effect -->
            <x-shop::shimmer.checkout.onepage />
        </v-checkout>
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-checkout-template"
        >
            <template v-if="! cart">
                <!-- Shimmer Effect -->
                <x-shop::shimmer.checkout.onepage />
            </template>

            <template v-else>
                <div class="checkout-page-grid grid grid-cols-[1fr_auto] gap-8 max-lg:grid-cols-[1fr] max-md:gap-5">
                    <!-- Included Checkout Summary Blade File For Mobile view -->
                    <div class="checkout-summary-card hidden max-md:block">
                        @include('shop::checkout.onepage.summary')
                    </div>

                    <div
                        class="checkout-steps-container overflow-y-auto max-md:grid max-md:gap-4"
                        id="steps-container"
                    >
                        <!-- Included Addresses Blade File -->
                        <template v-if="['address', 'shipping', 'payment', 'review'].includes(currentStep)">
                            @include('shop::checkout.onepage.address')
                        </template>

                        <!-- Included Shipping Methods Blade File -->
                        <template v-if="cart.have_stockable_items && ['shipping', 'payment', 'review'].includes(currentStep)">
                            @include('shop::checkout.onepage.shipping')
                        </template>

                        <!-- Included Payment Methods Blade File -->
                        <template v-if="['payment', 'review'].includes(currentStep)">
                            @include('shop::checkout.onepage.payment')
                        </template>
                    </div>

                    <!-- Included Checkout Summary Blade File For Desktop view -->
                    <div class="checkout-summary-shell sticky top-8 block h-max w-[442px] max-w-full max-lg:w-auto max-lg:max-w-[442px] ltr:pl-8 max-lg:ltr:pl-0 rtl:pr-8 max-lg:rtl:pr-0">
                        <div class="checkout-summary-card block max-md:hidden">
                            @include('shop::checkout.onepage.summary')
                        </div>

                        <div
                            class="flex justify-end"
                            v-if="canPlaceOrder"
                        >
                            <template v-if="(selectedPaymentMethod || cart.payment_method) == 'paypal_smart_button'">
                                {!! view_render_event('bagisto.shop.checkout.onepage.summary.paypal_smart_button.before') !!}

                                <!-- Paypal Smart Button Vue Component -->
                                <v-paypal-smart-button></v-paypal-smart-button>

                                {!! view_render_event('bagisto.shop.checkout.onepage.summary.paypal_smart_button.after') !!}
                            </template>

                            <template v-else>
                                <x-shop::button
                                    type="button"
                                    class="primary-button w-max rounded-2xl bg-navyBlue px-11 py-3 max-md:mb-4 max-md:w-full max-md:max-w-full max-md:rounded-lg max-sm:py-1.5"
                                    :title="trans('shop::app.checkout.onepage.summary.place-order')"
                                    ::disabled="isPlacingOrder"
                                    ::loading="isPlacingOrder"
                                    @click="placeOrder"
                                />
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </script>

        <script type="module">
            app.component('v-checkout', {
                template: '#v-checkout-template',

                data() {
                    return {
                        cart: null,

                        displayTax: {
                            prices: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_prices') }}",

                            subtotal: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_subtotal') }}",
                            
                            shipping: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_shipping_amount') }}",
                        },

                        isPlacingOrder: false,

                        currentStep: 'address',

                        shippingMethods: null,

                        paymentMethods: null,

                        selectedPaymentMethod: null,

                        canPlaceOrder: false,
                    }
                },

                mounted() {
                    this.getCart();
                },

                methods: {
                    formatCheckoutPrice(price) {
                        return String(price ?? '').replace(/([.,])00(?=\D*$)/, '');
                    },

                    getCart() {
                        this.$axios.get("{{ route('shop.checkout.onepage.summary') }}")
                            .then(response => {
                                this.cart = response.data.data;

                                this.scrollToCurrentStep();
                            })
                            .catch(error => {});
                    },

                    stepForward(step) {
                        this.currentStep = step;

                        if (step == 'review') {
                            this.canPlaceOrder = true;

                            return;
                        }

                        this.canPlaceOrder = false;

                        if (this.currentStep == 'shipping') {
                            this.shippingMethods = null;
                        } else if (this.currentStep == 'payment') {
                            this.paymentMethods = null;
                        }
                    },

                    stepProcessed(data) {
                        if (this.currentStep == 'shipping') {
                            this.shippingMethods = data;
                        } else if (this.currentStep == 'payment') {
                            this.paymentMethods = data;
                        }

                        this.getCart();
                    },

                    scrollToCurrentStep() {
                        let container = document.getElementById('steps-container');

                        if (! container) {
                            return;
                        }

                        container.scrollIntoView({
                            behavior: 'smooth',
                            block: 'end'
                        });
                    },

                    setSelectedPaymentMethod(method) {
                        this.selectedPaymentMethod = method;
                    },

                    placeOrder() {
                        if ((this.selectedPaymentMethod || this.cart.payment_method) == 'paypal_smart_button') {
                            return;
                        }

                        this.isPlacingOrder = true;

                        this.$axios.post('{{ route('shop.checkout.onepage.orders.store') }}')
                            .then(response => {
                                if (response.data.data.redirect) {
                                    window.location.href = response.data.data.redirect_url;
                                } else {
                                    window.location.href = '{{ route('shop.checkout.onepage.success') }}';
                                }

                                this.isPlacingOrder = false;
                            })
                            .catch(error => {
                                this.isPlacingOrder = false

                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                            });
                    }
                },
            });
        </script>
    @endPushOnce
<style>
    .checkout-page-header {
        min-height: 100px;
        align-items: center;
        padding-inline: clamp(20px, 4.1vw, 78px) !important;
    }

    .checkout-page-header img {
        display: block;
        width: 184px;
        height: auto;
        max-width: 42vw;
    }

    .checkout-page-content {
        width: 100%;
        max-width: 1744px !important;
        margin-inline: auto;
        padding: 44px 0 64px !important;
    }

    .checkout-page-grid {
        grid-template-columns: minmax(0, 1fr) minmax(420px, 578px) !important;
        align-items: start;
        gap: 90px !important;
    }

    .checkout-steps-container {
        min-width: 0;
        overflow: visible;
    }

    .checkout-summary-shell {
        position: sticky;
        top: 24px;
        width: 100%;
        max-width: none;
        padding-inline: 0 !important;
    }

    .checkout-summary-card {
        box-sizing: border-box;
        width: 100%;
        border: 1px solid #dedee2;
        border-radius: 4px;
        background: #fff;
        padding: 34px;
    }

    .checkout-summary-card .checkout-summary-title {
        color: #111;
        font-size: 28px;
        font-weight: 600;
        line-height: 1.25;
    }

    .checkout-summary-items {
        margin-top: 34px;
        border: 1px solid #dedee2;
        border-radius: 4px;
        padding: 18px;
    }

    .checkout-summary-item {
        min-height: 114px;
        gap: 20px;
        padding-bottom: 26px;
    }

    .checkout-summary-item + .checkout-summary-item {
        border-top: 1px solid #e7e7e9;
        padding-top: 18px;
    }

    .checkout-summary-item:last-child {
        padding-bottom: 0;
    }

    .checkout-summary-item img {
        flex: 0 0 82px;
        width: 82px;
        height: 114px;
        max-width: 82px;
        max-height: 114px;
        object-fit: cover;
        border-radius: 5px;
    }

    .checkout-summary-item > div {
        padding-top: 13px;
    }

    .checkout-summary-item .checkout-summary-product-name {
        color: #252525;
        font-size: 21px;
        font-weight: 600;
        line-height: 1.35;
    }

    .checkout-summary-item .checkout-summary-product-price {
        margin-top: 18px;
        color: #111;
        font-size: 23px;
        font-weight: 700;
        line-height: 1.25;
    }

    .checkout-summary-items + .checkout-summary-totals {
        margin-top: 0;
        padding-top: 24px;
    }

    .checkout-summary-totals {
        gap: 22px !important;
    }

    .checkout-summary-row {
        align-items: center;
        color: #111;
        font-size: 20px;
        font-weight: 600;
    }

    .checkout-summary-row p:last-child,
    .checkout-grand-total p:last-child {
        font-weight: 700;
    }

    .checkout-summary-coupon .cart-coupon-inline,
    .checkout-summary-coupon .cart-coupon-form {
        display: flex;
        width: 100%;
        align-items: stretch;
        gap: 12px;
    }

    .checkout-summary-coupon .cart-coupon-form input {
        min-width: 0;
        height: 68px;
        flex: 1 1 auto;
        border: 1px solid #dedee2;
        border-radius: 3px;
        background: #fff;
        padding: 0 28px;
        font-size: 19px;
    }

    .checkout-summary-coupon .cart-coupon-apply {
        min-width: 152px;
        width: 152px;
        flex: 0 0 152px;
        border: 1px solid #1b1b1b;
        border-radius: 3px;
        background: #1b1b1b !important;
        color: #fff !important;
        font-size: 20px;
        font-weight: 600;
    }

    .checkout-delivery-row {
        color: #111;
        font-size: 20px;
        line-height: 1.4;
    }

    .checkout-delivery-value {
        color: #71717a;
        font-size: 16px;
    }

    .checkout-grand-total {
        color: #111;
        font-size: 22px;
        font-weight: 700;
    }

    .checkout-address-accordion {
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        border: 0 !important;
        border-radius: 0 !important;
        overflow: visible !important;
    }

    .checkout-address-accordion h2 {
        color: #111;
        font-size: 28px;
        font-weight: 600;
    }

    .checkout-address-content {
        margin-top: 70px !important;
    }

    .checkout-page-content .cart-login-link {
        color: #b57f2b !important;
        font-size: 22px;
        font-weight: 500;
    }

    @media (max-width: 1200px) {
        .checkout-page-grid {
            grid-template-columns: minmax(0, 1fr) minmax(360px, 42vw) !important;
            gap: 48px !important;
        }

        .checkout-summary-card {
            padding: 26px;
        }

        .checkout-summary-coupon .cart-coupon-apply {
            min-width: 112px;
            width: 112px;
            flex-basis: 112px;
        }
    }

    @media (max-width: 900px) {
        .checkout-page-content {
            padding: 28px 24px 48px !important;
        }

        .checkout-page-grid {
            grid-template-columns: minmax(0, 1fr) !important;
            gap: 28px !important;
        }

        .checkout-summary-shell {
            display: none !important;
        }

        .checkout-summary-card.hidden[class~="max-md:block"] {
            display: block !important;
        }

        .checkout-address-content {
            margin-top: 36px !important;
        }
    }

    @media (max-width: 600px) {
        .checkout-page-header {
            min-height: 76px;
            padding-inline: 16px !important;
        }

        .checkout-page-header img {
            width: 148px;
        }

        .checkout-page-content {
            padding: 18px 16px 32px !important;
        }

        .checkout-summary-card {
            padding: 18px;
        }

        .checkout-summary-coupon .cart-coupon-form {
            gap: 8px;
        }

        .checkout-summary-coupon .cart-coupon-form input {
            height: 48px;
            padding-inline: 12px;
            font-size: 14px;
        }

        .checkout-summary-coupon .cart-coupon-apply {
            min-width: 80px;
            width: 80px;
            flex-basis: 80px;
            font-size: 15px;
        }

        .checkout-summary-row,
        .checkout-delivery-row {
            font-size: 16px;
        }

        .checkout-delivery-value {
            font-size: 12px;
        }

        .checkout-grand-total {
            font-size: 18px;
        }

        .checkout-summary-card .checkout-summary-title {
            font-size: 22px;
        }

        .checkout-summary-items {
            margin-top: 20px;
            padding: 14px;
        }

        .checkout-summary-item {
            min-height: 96px;
            gap: 14px;
            padding-bottom: 18px;
        }

        .checkout-summary-item + .checkout-summary-item {
            padding-top: 14px;
        }

        .checkout-summary-item img {
            flex-basis: 68px;
            width: 68px;
            height: 96px;
            max-width: 68px;
            max-height: 96px;
        }

        .checkout-summary-item > div {
            padding-top: 4px;
        }

        .checkout-summary-item .checkout-summary-product-name {
            font-size: 16px;
        }

        .checkout-summary-item .checkout-summary-product-price {
            margin-top: 8px;
            font-size: 17px;
        }

        .checkout-address-accordion h2 {
            font-size: 22px;
        }

        .checkout-address-content {
            margin-top: 26px !important;
        }
    }
</style>
</x-shop::layouts>
