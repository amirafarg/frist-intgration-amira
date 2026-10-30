@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

@php
    $productSlug = \Illuminate\Support\Str::slug($product->name);
    $isRoundBasRoglam = $product->url_key === 'round-bas-roglam' || $productSlug === 'round-bas-roglam';
    $isSlimTeeS24 = $product->url_key === 'slim-tee-s-24' || $productSlug === 'slim-tee-s-24';
    $normalizedProductUrl = strtolower(preg_replace('/[^a-z0-9]/i', '', $product->url_key));
    $normalizedProductName = strtolower(preg_replace('/[^a-z0-9]/i', '', $product->name));
    $isKnittedRoundW26 = $normalizedProductUrl === 'knittedroundw26'
        || $normalizedProductName === 'knittedroundw26';

    $roundBasRoglamDescription = 'Round Basic Reglam — A versatile everyday essential made from 100% cotton, featuring a classic regular fit and distinctive raglan sleeves. Designed to combine all-day comfort with a clean, sporty, and effortlessly stylish look.';
    $slimTeeS24Description = 'Slim Tee S24 A modern Round Slim Fit T-Shirt crafted from 100% cotton, designed to deliver a clean, stylish look with everyday comfort.';
    $knittedRoundW26Description = implode("\n", [
        'Knitted Fabric: Features a distinctive knitted texture that adds a refined touch to your look.',
        'Round Neck Design: A clean, classic neckline for a modern and versatile appearance.',
        'Modern Aesthetic: Combines simplicity and sophistication for an effortlessly stylish look.',
        'Comfortable Feel: Designed to provide lasting comfort throughout the day.',
        'Premium Texture: Adds depth and character, making it stand out from ordinary basic tops.',
        'Versatile Styling: Easily pairs with jeans, tailored trousers, or casual pants.',
        'Smart Casual Look: Perfect for casual outings and effortlessly polished everyday outfits.',
        'Wardrobe Essential: A versatile piece that brings a fresh touch to your everyday rotation.',
        'تعليمات العناية والغسيل لعمر أطول للقطعة:',
        'يُغسل بماء بارد فقط، ويُمنع استخدام الماء الساخن.',
        'لا يُنشر تحت أشعة الشمس المباشرة.',
        'يُغسل على الدورة الخفيفة في الغسالة.',
        'لا يُعرّض للعصر داخل الغسالة.',
        'لا يُعلّق أو يُنشر وهو مبلل.',
    ]);

    $avgRatings = $reviewHelper->getAverageRating($product);

    $percentageRatings = $reviewHelper->getPercentageRating($product);

    $customAttributeValues = $productViewHelper->getAdditionalData($product);

    $attributeData = collect($customAttributeValues)->filter(fn ($item) => ! empty($item['value']));

    $descriptionText = preg_replace(
        '/<\s*br\s*\/?\s*>|<\/\s*(?:p|div|li)\s*>/i',
        "\n",
        $isRoundBasRoglam
            ? $roundBasRoglamDescription
            : ($isSlimTeeS24
                ? $slimTeeS24Description
                : ($isKnittedRoundW26 ? $knittedRoundW26Description : $product->description))
    );

    $descriptionLines = collect(preg_split('/\R/u', html_entity_decode(strip_tags($descriptionText))))
        ->filter(fn ($line) => trim($line) !== '');

    $isProductSaleable = $isRoundBasRoglam || $isSlimTeeS24 || $isKnittedRoundW26 || $product->isSaleable(1)
        || (\Webkul\Product\Helpers\ProductType::hasVariants($product->type)
            && $product->variants->contains(fn ($variant) => $variant->isSaleable(1)));
@endphp

<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="{{ trim($product->meta_description) != "" ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '') }}"/>

    <meta name="keywords" content="{{ $product->meta_keywords }}"/>

    @if (core()->getConfigData('catalog.rich_snippets.products.enable'))
        <script type="application/ld+json">
            {!! app('Webkul\Product\Helpers\SEO')->getProductJsonLd($product) !!}
        </script>
    @endif

    <?php $productBaseImage = product_image()->getProductBaseImage($product); ?>

    <meta name="twitter:card" content="summary_large_image" />

    <meta name="twitter:title" content="{{ $product->name }}" />

    <meta name="twitter:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />

    <meta name="twitter:image:alt" content="" />

    <meta name="twitter:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:type" content="og:product" />

    <meta property="og:title" content="{{ $product->name }}" />

    <meta property="og:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />

    <meta property="og:url" content="{{ route('shop.product_or_category.index', $product->url_key) }}" />
@endPush

<!-- Page Layout -->
<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ trim($product->meta_title) != "" ? $product->meta_title : $product->name }}
    </x-slot>

    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        <div class="flex justify-center px-7 hidden">
            <x-shop::breadcrumbs
                name="product"
                :entity="$product"
            />
        </div>
    @endif

    <!-- Product Information Vue Component -->
    <v-product>
        <x-shop::shimmer.products.view />
    </v-product>

    <!-- Information Section -->
    <div class="1180:mt-20">
        <div class="max-1180:hidden flex flex-col gap-6 max-sm:gap-3">
            <x-shop::tabs
                position="left"
                ref="productTabs"
             >
                <!-- Description Tab -->
                {!! view_render_event('bagisto.shop.products.view.description.before', ['product' => $product]) !!}

                <x-shop::tabs.item
                    id="description-tab"
                    class="container mt-5 ml-5 !p-0"
                    :title="trans('shop::app.products.view.description')"
                    :is-selected="true"
                >
                    <div class="mt-5 ml-5 max-1180:px-5">
                        <ul class="space-y-1 px-8 text-base text-zinc-500 max-1180:text-sm">
                            @foreach ($descriptionLines as $descriptionLine)
                                <li class="flex gap-2">
                                    <span
                                        class="text-black text-xl"
                                        aria-hidden="true"
                                    >•</span>

                                    <span>{{ trim($descriptionLine) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </x-shop::tabs.item>

                {!! view_render_event('bagisto.shop.products.view.description.after', ['product' => $product]) !!}

                <!-- Additional Information Tab -->
                @if(count($attributeData))
                    <x-shop::tabs.item
                        id="information-tab"
                        class="container mt-[60px] !p-0"
                        :title="trans('shop::app.products.view.additional-information')"
                        :is-selected="false"
                    >
                        <div class="container mt-[60px] max-1180:px-5">
                            <div class="mt-8 grid max-w-max grid-cols-[auto_1fr] gap-4">
                                @foreach ($customAttributeValues as $customAttributeValue)
                                    @if (! empty($customAttributeValue['value']))
                                        <div class="grid">
                                            <p class="text-base text-black">
                                                {{ $customAttributeValue['label'] }}
                                            </p>
                                        </div>

                                        @if ($customAttributeValue['type'] == 'file')
                                            <a
                                                href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                                download="{{ $customAttributeValue['label'] }}"
                                            >
                                                <span class="icon-download text-2xl"></span>
                                            </a>
                                        @elseif ($customAttributeValue['type'] == 'image')
                                            <a
                                                href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                                download="{{ $customAttributeValue['label'] }}"
                                            >
                                                <img
                                                    class="min-h-5 min-w-5 h-5 w-5"
                                                    src="{{ Storage::url($customAttributeValue['value']) }}"
                                                />
                                            </a>
                                        @else
                                            <div class="grid">
                                                <p class="text-base text-zinc-500">
                                                    {{ $customAttributeValue['value'] }}
                                                </p>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </x-shop::tabs.item>
                @endif

                <!-- Reviews Tab -->
                <x-shop::tabs.item
                    id="review-tab"
                    class="container mt-5 !p-0"
                    :title="trans('shop::app.products.view.additional-information')"
                    :is-selected="false"
                >
                    <div class="mt-5 max-1180:px-5">
                        <div class="px-8 flex items-center gap-4">
                            <h2 class="text-lg font-medium text-black">Material</h2>

                            <p class="text-base text-zinc-600">Cotton</p>
                        </div>
                    </div>
                </x-shop::tabs.item>
            </x-shop::tabs>
        </div>
    </div>

    <!-- Information Section -->
    <div class="container mt-6 grid gap-3 !p-0 max-1180:px-5 1180:hidden">
        <!-- Description Accordion -->
        <x-shop::accordion
            class="max-md:border-none"
            :is-active="true"
        >
            <x-slot:header class="bg-gray-100 max-md:!py-3 max-sm:!py-2">
                <p class="text-base font-medium 1180:hidden">
                    @lang('shop::app.products.view.description')
                </p>
            </x-slot>

            <x-slot:content class="max-sm:px-0">
                <ul class="mb-5 space-y-1 text-lg text-zinc-500 max-1180:text-sm max-md:mb-1 max-md:px-4">
                    @foreach ($descriptionLines as $descriptionLine)
                        <li class="flex gap-2">
                            <span
                                class="text-black text-lg"
                                aria-hidden="true"
                            >•</span>

                            <span>{{ trim($descriptionLine) }}</span>
                        </li>
                    @endforeach
                </ul>
            </x-slot>
        </x-shop::accordion>

        <!-- Additional Information Accordion -->
        @if (count($attributeData))
            <x-shop::accordion
                class="max-md:border-none"
                :is-active="false"
            >
                <x-slot:header class="bg-gray-100 max-md:!py-3 max-sm:!py-2">
                    <p class="text-base font-medium 1180:hidden">
                        @lang('shop::app.products.view.additional-information')
                    </p>
                </x-slot>

                <x-slot:content class="max-sm:px-0">
                    <div class="container max-1180:px-5">
                        <div class="grid max-w-max grid-cols-[auto_1fr] gap-4 text-lg text-zinc-500 max-1180:text-sm">
                            @foreach ($customAttributeValues as $customAttributeValue)
                                @if (! empty($customAttributeValue['value']))
                                    <div class="grid">
                                        <p
                                            class="text-base text-black"
                                            v-pre
                                        >
                                            {{ $customAttributeValue['label'] }}
                                        </p>
                                    </div>

                                    @if ($customAttributeValue['type'] == 'file')
                                        <a
                                            href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                            download="{{ $customAttributeValue['label'] }}"
                                        >
                                            <span class="icon-download text-2xl"></span>
                                        </a>
                                    @elseif ($customAttributeValue['type'] == 'image')
                                        <a
                                            href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                            download="{{ $customAttributeValue['label'] }}"
                                        >
                                            <img
                                                class="min-h-5 min-w-5 h-5 w-5"
                                                src="{{ Storage::url($customAttributeValue['value']) }}"
                                                alt="Product Image"
                                            />
                                        </a>
                                    @else
                                        <div class="grid">
                                            <p
                                                class="text-base text-zinc-500"
                                                v-pre
                                            >
                                                {{ $customAttributeValue['value'] ?? '-' }}
                                            </p>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                </x-slot>
            </x-shop::accordion>
        @endif

        <!-- Material Accordion -->
        <x-shop::accordion
            class="max-md:border-none"
            :is-active="false"
        >
            <x-slot:header
                class="bg-gray-100 max-md:!py-3 max-sm:!py-2"
                id="review-accordian-button"
            >
                <p class="text-base font-medium">
                    @lang('shop::app.products.view.additional-information')
                </p>
            </x-slot>

            <x-slot:content class="max-sm:px-4">
                <div class="flex items-center gap-4 py-2">
                    <p class="text-base font-medium text-black">Material</p>

                    <p class="text-sm text-zinc-600">Cotton</p>
                </div>
            </x-slot>
        </x-shop::accordion>
    </div>

    <v-product-associations></v-product-associations>

    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-product-template"
        >
            <x-shop::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form
                    ref="formData"
                    @submit="handleSubmit($event, addToCart)"
                >
                    <input
                        type="hidden"
                        name="product_id"
                        value="{{ $product->id }}"
                    >

                    <input
                        type="hidden"
                        name="is_buy_now"
                        v-model="is_buy_now"
                    >

                    <div class="container px-[60px] max-1180:px-0">
                        <div class="mt-12 flex gap-9 max-1180:flex-wrap max-lg:mt-0 max-sm:gap-y-4">
                            <!-- Gallery Blade Inclusion -->
                            @include('shop::products.view.gallery')

                            <!-- Details -->
                            <div class="relative w-full max-1150:w-full max-1150:max-w-full max-1150:px-5 max-sm:px-4">
                                {!! view_render_event('bagisto.shop.products.name.before', ['product' => $product]) !!}

                                <div class="flex justify-between items-center gap-4">
                                    <h1 class="break-words text-3xl font-normal max-sm:text-xl" v-pre>
                                        {{ $product->name }}
                                    </h1>

                                    @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                                        <div
                                            class="max-sm:min-h-7 max-sm:min-w-7 flex max-h-[46px] min-h-[46px] min-w-[46px] cursor-pointer items-center justify-center rounded-full border bg-white text-2xl transition-all hover:opacity-[0.8] max-sm:max-h-7 max-sm:text-base"
                                            role="button"
                                            aria-label="@lang('shop::app.products.view.add-to-wishlist')"
                                            tabindex="0"
                                            :class="isWishlist ? 'icon-heart-fill text-red-600' : 'icon-heart'"
                                            @click="addToWishlist"
                                        >
                                        </div>
                                    @endif
                                </div>

                                {!! view_render_event('bagisto.shop.products.name.after', ['product' => $product]) !!}

                                <!-- Rating -->
                                {!! view_render_event('bagisto.shop.products.rating.before', ['product' => $product]) !!}

                                @if ($totalRatings = $reviewHelper->getTotalFeedback($product))
                                    <!-- Scroll To Reviews Section and Activate Reviews Tab -->
                                    <div
                                        class="mt-1 w-max cursor-pointer max-sm:mt-1.5"
                                        role="button"
                                        tabindex="0"
                                        @click="scrollToReview"
                                    >
                                        <x-shop::products.ratings
                                            class="transition-all hover:border-gray-400 max-sm:px-3 max-sm:py-1"
                                            :average="$avgRatings"
                                            :total="$totalRatings"
                                            ::rating="true"
                                        />
                                    </div>
                                @endif

                                {!! view_render_event('bagisto.shop.products.rating.after', ['product' => $product]) !!}

                                <!-- Pricing -->
                                {!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}

                                <div class="mt-6 flex items-center gap-2.5 text-2xl !font-medium max-sm:mt-2 max-sm:gap-x-2.5 max-sm:gap-y-0 max-sm:text-lg">
                                    <!-- {!! $product->getTypeInstance()->getPriceHtml() !!} -->

                                    @php
                                        $productPrices = $product->getTypeInstance()->getProductPrices();
                                        $finalPrice = core()->convertPrice($isRoundBasRoglam ? 275 : ($isSlimTeeS24 ? 299 : $productPrices['final']['price']));
                                        $regularPrice = core()->convertPrice($isRoundBasRoglam ? 550 : ($isSlimTeeS24 ? 600 : ($isKnittedRoundW26 ? 700 : $productPrices['regular']['price'])));
                                        $hasDiscount = $regularPrice > $finalPrice;
                                        $discountPercent = $hasDiscount ? round((($regularPrice - $finalPrice) / $regularPrice) * 100) : 0;
                                    @endphp

                                    <!-- current-price -->
                                    <p style="color: #AF8234 !important;" class="final-price text-3xl">
                                        {{ number_format($finalPrice, 0) }} <span style="color: #AF8234 !important;" class="text-2xl">EGP</span>
                                    </p>

                                    <!-- old-price -->
                                    <p style="color: #9CA3AF !important; {{ $hasDiscount ? '' : 'display: none;' }}" class="regular-price font-thin text-xl line-through max-sm:text-lg">
                                        {{ number_format($regularPrice, 0) }} <span class="">EGP</span>
                                    </p>

                                    <!-- discount -->
                                    <p style="background-color: #AF8234; color: #FFFFFF; {{ $hasDiscount ? '' : 'display: none;' }}" class="discount-badge rounded-full px-3 py-1 text-sm font-semibold max-sm:text-xs">
                                        {{ $discountPercent }}% OFF
                                    </p>
                                </div>

                                @if (\Webkul\Tax\Facades\Tax::isInclusiveTaxProductPrices())
                                    <span class="text-sm font-normal text-zinc-500 max-sm:text-xs">
                                        (@lang('shop::app.products.view.tax-inclusive'))
                                    </span>
                                @endif

                                @if (count($product->getTypeInstance()->getCustomerGroupPricingOffers()))
                                    <div class="mt-2.5 grid gap-1.5">
                                        @foreach ($product->getTypeInstance()->getCustomerGroupPricingOffers() as $offer)
                                            <p class="text-zinc-500 [&>*]:text-black">
                                                {!! $offer !!}
                                            </p>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- {{-- Color & Size --}} -->
                                @include('shop::products.view.types.configurable')

                                {!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}

                                {!! view_render_event('bagisto.shop.products.short_description.before', ['product' => $product]) !!}

                                @php
                                    $shortDescription = $isSlimTeeS24
                                        ? $slimTeeS24Description
                                        : ($isKnittedRoundW26
                                            ? $knittedRoundW26Description
                                            : trim(preg_replace('/\x{00A0}/u', ' ', strip_tags($product->short_description))));
                                @endphp

                                @if ($shortDescription !== '' && ! $isRoundBasRoglam && ! $isSlimTeeS24 && ! $isKnittedRoundW26)
                                    <div class="mt-6 text-lg text-zinc-500 max-sm:mt-1.5 max-sm:text-sm">
                                        {{ $shortDescription }}
                                    </div>
                                @endif

                                {!! view_render_event('bagisto.shop.products.short_description.after', ['product' => $product]) !!}

                                @include('shop::products.view.types.simple')



                                @include('shop::products.view.types.grouped')

                                @include('shop::products.view.types.bundle')

                                @include('shop::products.view.types.downloadable')

                                @include('shop::products.view.types.booking')

                                <!-- Product Actions and Quantity Box -->
                                <div class="mobile-product-actions mt-8 flex w-full gap-4
                                    max-1060:fixed max-1060:bottom-[65px] max-1060:left-0 max-1060:right-0 max-1060:z-[9999]
                                    max-1060:bg-white max-1060:mt-10 max-1060:p-4 max-1060:shadow-[0_-4px_16px_rgba(0,0,0,0.12)]
                                    max-lg:mt-4 max-lg:gap-3
                                    max-md:mt-4 max-sm:mt-4 max-1060:border-t max-1060:border-gray-300">

                                    {!! view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]) !!}

                                    @if ($product->getTypeInstance()->showQuantityBox())
                                        <x-shop::quantity-changer
                                            name="quantity"
                                            value="1"
                                            class="w-fit flex-none justify-center gap-x-4 px-4 py-2 max-md:py-2 max-sm:gap-x-4 max-sm:px-3 max-sm:py-1.5 max-sm:rounded-sm max-md:rounded-sm"
                                        />
                                    @endif

                                    {!! view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]) !!}

                                    @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                                        <!-- Add To Cart Button -->
                                        {!! view_render_event('bagisto.shop.products.view.add_to_cart.before', ['product' => $product]) !!}

                                        <x-shop::button
                                            type="submit"
                                            class="flex-1 bg-black py-2 text-sm font-semibold uppercase tracking-wider text-white transition hover:bg-zinc-800
                                            max-1060:text-xs max-md:rounded-sm max-sm:rounded-sm"
                                            button-type="secondary-button"
                                            :loading="false"
                                            :title="trans('shop::app.products.view.add-to-cart')"
                                            :disabled="! $isProductSaleable"
                                            ::loading="isStoring.addToCart"
                                            @click="is_buy_now=0;"
                                        />

                                        {!! view_render_event('bagisto.shop.products.view.add_to_cart.after', ['product' => $product]) !!}
                                    @else
                                        <button
                                            type="button"
                                            class="secondary-button w-full max-w-full max-md:py-3 max-sm:rounded-lg max-sm:py-1.5"
                                            @click="$refs.contactUsModal.open()"
                                        >
                                            @lang('shop::app.components.layouts.footer.contact-us')
                                        </button>
                                    @endif
                                </div>

                                <!-- Buy Now Button -->
                                @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                                    {!! view_render_event('bagisto.shop.products.view.buy_now.before', ['product' => $product]) !!}

                                    @if (core()->getConfigData('catalog.products.storefront.buy_now_button_display'))
                                        <x-shop::button
                                            type="submit"
                                            class="primary-button mt-5 w-full max-w-[470px] max-md:py-3 max-sm:mt-3 max-sm:rounded-sm max-sm:py-1.5"
                                            button-type="primary-button"
                                            :title="trans('shop::app.products.view.buy-now')"
                                            :disabled="! $isProductSaleable"
                                            ::loading="isStoring.buyNow"
                                            @click="is_buy_now=1;"
                                            ::disabled="isStoring.buyNow"
                                        />
                                    @endif

                                    {!! view_render_event('bagisto.shop.products.view.buy_now.after', ['product' => $product]) !!}
                                @endif

                                {!! view_render_event('bagisto.shop.products.view.additional_actions.before', ['product' => $product]) !!}

                                <!-- Share Buttons -->
                                <div class="mt-10 flex gap-9 max-md:mt-4 max-md:flex-wrap max-sm:justify-center max-sm:gap-3">
                                    {!! view_render_event('bagisto.shop.products.view.compare.before', ['product' => $product]) !!}

                                    <div
                                        class="flex cursor-pointer items-center justify-center gap-2.5 max-sm:gap-1.5 max-sm:text-base"
                                        role="button"
                                        tabindex="0"
                                        @click="is_buy_now=0; addToCompare({{ $product->id }})">

                                        <ul class="flex gap-5 flex-wrap max-sm:hidden">
                                            <li class="flex items-center gap-2">
                                                <svg class="h-4 w-4 shrink-0 text-grey" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M17 3l4 4-4 4" />
                                                    <path d="M3 7h18" />
                                                    <path d="M7 21l-4-4 4-4" />
                                                    <path d="M21 17H3" />
                                                </svg>

                                                <span class="text-grey text-base">Easy Exchange</span>
                                            </li>

                                            <li class="flex items-center gap-2">
                                                <svg class="h-4 w-4 shrink-0 text-grey" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="5" width="18" height="12" rx="2" />
                                                    <path d="M3 10h18" />
                                                    <path d="m15.5 15 1.5 1.5 3-3" />
                                                </svg>

                                                <span class="text-grey text-base">Secure Payment</span>
                                            </li>

                                            <li class="flex items-center gap-2">
                                                <svg class="h-4 w-4 shrink-0 text-grey" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                 <path d="M12 2.5l2.1 2.1 3-.2.2 3L19.5 10l-2.2 2.4.2 3-3 .2L12 18l-2.1-2.2-3-.2.2-3L4.5 10l2.2-2.4.2-3 3 .2L12 2.5z" />
                                                 <path d="m9 10.8 2 2 4-4" />
                                                 <path d="m10 18-1 3.5 3-1.2 3 1.2-1-3.5" />
                                                </svg>

                                                <span class="text-grey text-base">Quality Commitment</span>
                                            </li>
                                        </ul>
                                        <!-- @if (core()->getConfigData('catalog.products.settings.compare_option'))
                                            <span
                                                class="icon-compare text-2xl"
                                                role="presentation"
                                            ></span>

                                            @lang('shop::app.products.view.compare')
                                        @endif -->
                                    </div>

                                    {!! view_render_event('bagisto.shop.products.view.compare.after', ['product' => $product]) !!}
                                </div>

                                {!! view_render_event('bagisto.shop.products.view.additional_actions.after', ['product' => $product]) !!}
                            </div>
                        </div>
                    </div>
                </form>
            </x-shop::form>

            <!-- Contact Us Modal -->
            <x-shop::modal ref="contactUsModal">
                <x-slot:header>
                <h2 class="text-lg font-semibold max-md:text-base">
                        @lang('shop::app.products.view.contact-us.title')
                    </h2>
                </x-slot>

                <x-slot:content>
                    <x-shop::form :action="route('shop.home.contact_us.send_mail')">
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="required">
                                @lang('shop::app.products.view.contact-us.name')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="text"
                                name="name"
                                rules="required"
                                :value="old('name')"
                                :label="trans('shop::app.products.view.contact-us.name')"
                                :placeholder="trans('shop::app.products.view.contact-us.name')"
                                :aria-label="trans('shop::app.products.view.contact-us.name')"
                                aria-required="true"
                            />

                            <x-shop::form.control-group.error control-name="name" />
                        </x-shop::form.control-group>

                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="required">
                                @lang('shop::app.products.view.contact-us.email')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="email"
                                name="email"
                                rules="required|email"
                                :value="old('email')"
                                :label="trans('shop::app.products.view.contact-us.email')"
                                :placeholder="trans('shop::app.products.view.contact-us.email')"
                                :aria-label="trans('shop::app.products.view.contact-us.email')"
                                aria-required="true"
                            />

                            <x-shop::form.control-group.error control-name="email" />
                        </x-shop::form.control-group>

                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label>
                                @lang('shop::app.products.view.contact-us.phone-number')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="text"
                                name="contact"
                                rules="phone"
                                :value="old('contact')"
                                :label="trans('shop::app.products.view.contact-us.phone-number')"
                                :placeholder="trans('shop::app.products.view.contact-us.phone-number')"
                                :aria-label="trans('shop::app.products.view.contact-us.phone-number')"
                            />

                            <x-shop::form.control-group.error control-name="contact" />
                        </x-shop::form.control-group>

                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="required">
                                @lang('shop::app.products.view.contact-us.desc')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="textarea"
                                name="message"
                                rules="required"
                                :label="trans('shop::app.products.view.contact-us.message')"
                                :placeholder="trans('shop::app.products.view.contact-us.describe-here')"
                                :aria-label="trans('shop::app.products.view.contact-us.message')"
                                aria-required="true"
                                rows="6"
                            />

                            <x-shop::form.control-group.error control-name="message" />
                        </x-shop::form.control-group>

                        @if (core()->getConfigData('customer.captcha.credentials.status'))
                            <x-shop::form.control-group class="mt-5">
                                {!! \Webkul\Customer\Facades\Captcha::render() !!}

                                <x-shop::form.control-group.error control-name="recaptcha_token" />
                            </x-shop::form.control-group>
                        @endif

                        <div class="mt-6 flex justify-end">
                            <button
                                type="submit"
                                class="primary-button rounded-2xl px-8 py-3 max-sm:rounded-lg max-sm:px-6 max-sm:py-2"
                            >
                                @lang('shop::app.products.view.contact-us.submit')
                            </button>
                        </div>
                    </x-shop::form>
                </x-slot>
            </x-shop::modal>
        </script>

        <script type="module">
            app.component('v-product', {
                template: '#v-product-template',

                data() {
                    return {
                        isWishlist: false,

                        isCustomer: '{{ auth()->guard('customer')->check() }}',

                        is_buy_now: 0,

                        isStoring: {
                            addToCart: false,

                            buyNow: false,
                        },
                    }
                },

                mounted() {
                    this.checkWishlistStatus();
                },

                methods: {
                    addToCart(params) {
                        const operation = this.is_buy_now ? 'buyNow' : 'addToCart';

                        this.isStoring[operation] = true;

                        let formData = new FormData(this.$refs.formData);

                        this.ensureQuantity(formData);

                        this.$axios.post('{{ route("shop.api.checkout.cart.store") }}', formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then(response => {
                                if (response.data.message) {
                                    this.$emitter.emit('update-mini-cart', response.data.data);

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                    if (response.data.redirect) {
                                        window.location.href= response.data.redirect;
                                    }
                                } else {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                                }

                                this.isStoring[operation] = false;
                            })
                            .catch(error => {
                                this.isStoring[operation] = false;

                                this.$emitter.emit('add-flash', {
                                    type: 'warning',
                                    message: error.response?.data?.message || error.message || 'Unable to add this product to the cart.',
                                });
                            });
                    },

                    checkWishlistStatus() {
                        if (this.isCustomer) {
                            /**
                             * Fetches the wishlist items for the customer and checks whether the current
                             * product exists in the wishlist. If found, `isWishlist` is set to true;
                             * otherwise, it is set to false.
                             *
                             * This approach is used due to Full Page Cache (FPC) limitations. We cannot
                             * use a replacer here because `product_id` is dynamic, and the replacer
                             * cannot reliably detect it.
                             */
                            this.$axios.get('{{ route('shop.api.customers.account.wishlist.index') }}')
                                .then(response => {
                                    const wishlistItems = response.data.data || [];

                                    this.isWishlist = Boolean(wishlistItems.find(item => item.product.id == "{{ $product->id }}")?.product?.is_wishlist);
                                })
                                .catch(error => {});
                        }
                    },

                    addToWishlist() {
                        if (this.isCustomer) {
                            this.$axios.post('{{ route('shop.api.customers.account.wishlist.store') }}', {
                                    product_id: "{{ $product->id }}"
                                })
                                .then(response => {
                                    this.isWishlist = ! this.isWishlist;

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                                })
                            .catch(error => {});
                        } else {
                            window.location.href = "{{ route('shop.customer.session.index') }}";
                        }
                    },

                    addToCompare(productId) {
                        /**
                         * This will handle for customers.
                         */
                        if (this.isCustomer) {
                            this.$axios.post('{{ route("shop.api.compare.store") }}', {
                                    'product_id': productId
                                })
                                .then(response => {
                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                                })
                                .catch(error => {
                                    if ([400, 422].includes(error.response.status)) {
                                        this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.data.message });

                                        return;
                                    }

                                    this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message});
                                });

                            return;
                        }

                        /**
                         * This will handle for guests.
                         */
                        let existingItems = this.getStorageValue(this.getCompareItemsStorageKey()) ?? [];

                        if (existingItems.length) {
                            if (! existingItems.includes(productId)) {
                                existingItems.push(productId);

                                this.setStorageValue(this.getCompareItemsStorageKey(), existingItems);

                                this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.products.view.add-to-compare')" });
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('shop::app.products.view.already-in-compare')" });
                            }
                        } else {
                            this.setStorageValue(this.getCompareItemsStorageKey(), [productId]);

                            this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.products.view.add-to-compare')" });
                        }
                    },

                    updateQty(quantity, id) {
                        this.isLoading = true;

                        let qty = {};

                        qty[id] = quantity;

                        this.$axios.put('{{ route('shop.api.checkout.cart.update') }}', { qty })
                            .then(response => {
                                if (response.data.message) {
                                    this.cart = response.data.data;
                                } else {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                                }

                                this.isLoading = false;
                            }).catch(error => this.isLoading = false);
                    },

                    getCompareItemsStorageKey() {
                        return 'compare_items';
                    },

                    setStorageValue(key, value) {
                        localStorage.setItem(key, JSON.stringify(value));
                    },

                    getStorageValue(key) {
                        let value = localStorage.getItem(key);

                        if (value) {
                            value = JSON.parse(value);
                        }

                        return value;
                    },

                    scrollToReview() {
                        let accordianElement = document.querySelector('#review-accordian-button');

                        if (accordianElement) {
                            accordianElement.click();

                            accordianElement.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }

                        let tabElement = document.querySelector('#review-tab-button');

                        if (tabElement) {
                            tabElement.click();

                            tabElement.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }
                    },

                    ensureQuantity(formData) {
                        if (! formData.has('quantity')) {
                            formData.append('quantity', 1);
                        }
                    },
                },
            });
        </script>

        <script
            type="text/x-template"
            id="v-product-associations-template"
        >
            <div ref="carouselWrapper">
                <template v-if="isVisible">
                    <!-- Featured Products -->
                    <x-shop::products.carousel
                        :title="trans('shop::app.products.view.related-product-title')"
                        :src="route('shop.api.products.related.index', ['id' => $product->id])"
                    />

                    <!-- Up-sell Products -->
                    <x-shop::products.carousel
                        :title="trans('shop::app.products.view.up-sell-title')"
                        :src="route('shop.api.products.up-sell.index', ['id' => $product->id])"
                    />
                </template>
            </div>
        </script>

        <script type="module">
            app.component('v-product-associations', {
                template: '#v-product-associations-template',

                data() {
                    return {
                        isVisible: false,
                    };
                },

                mounted() {
                    const observer = new IntersectionObserver(
                        (entries) => {
                            entries.forEach((entry) => {
                                if (entry.isIntersecting) {
                                    this.isVisible = true;
                                    observer.unobserve(entry.target); // Stop observing
                                }
                            });
                        },
                        { threshold: 0.1 }
                    );

                    observer.observe(this.$refs.carouselWrapper);
                }
            });
        </script>

        @if (core()->getConfigData('customer.captcha.credentials.status'))
            {!! \Webkul\Customer\Facades\Captcha::renderJS() !!}
        @endif
    @endPushOnce
</x-shop::layouts>
