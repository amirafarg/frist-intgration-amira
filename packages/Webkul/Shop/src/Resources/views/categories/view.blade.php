<!-- SEO Meta Content -->
@push('meta')
    <meta
        name="description"
        content="{{ trim($category->meta_description) != "" ? $category->meta_description : \Illuminate\Support\Str::limit(strip_tags($category->description), 120, '') }}"
    />

    <meta
        name="keywords"
        content="{{ $category->meta_keywords }}"
    />

    @if (core()->getConfigData('catalog.rich_snippets.categories.enable'))
        <script type="application/ld+json">
            {!! app('Webkul\Product\Helpers\SEO')->getCategoryJsonLd($category) !!}
        </script>
    @endif
@endPush

<x-shop::layouts>
    @push('styles')
        <style>
            .category-hero {
                display: flex;
                min-height: 185px;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                background: linear-gradient(110deg, #fffdf0 0%, #fffbbd 52%, #fffef0 100%);
                text-align: center;
            }

            .category-hero h1 {
                margin: 0;
                color: #090909;
                font-size: 34px;
                font-weight: 500;
                line-height: 1.2;
            }

            .category-toolbar-row {
                position: relative;
                margin-top: 56px;
            }

            .category-toolbar-desktop {
                width: 100%;
                grid-template-columns: 1fr 1fr;
            }

            .category-toolbar-row .category-view-modes {
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
            }

            .category-toolbar-row .category-sort-control {
                grid-column: 2;
                grid-row: 1;
                justify-self: end;
            }

            .category-sort-control button { min-width: 220px; }

            .category-filter-trigger { min-width: 144px; }

            .category-product-grid > div > div:first-child {
                width: 100%;
                max-width: none;
                max-height: none;
            }

            .category-product-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }

            .category-mode-grid .category-product-grid > div > div:first-child > a {
                position: relative;
                display: block;
                aspect-ratio: 3 / 4;
                overflow: hidden;
            }

            .category-mode-grid .category-product-grid > div > div:first-child > a > * {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                min-width: 0;
            }

            .category-mode-grid .category-product-grid > div > div:first-child > a img {
                object-fit: cover;
                transform: scale(1);
                transition: transform 550ms cubic-bezier(0.2, 0.7, 0.2, 1);
                will-change: transform;
            }

            .category-mode-grid .category-product-grid > div > div:first-child > a:hover img {
                transform: scale(1.06);
            }

            .category-mode-grid .category-grid-card {
                width: 100%;
                max-width: none;
                overflow: visible;
                align-content: start;
            }

            .category-mode-grid .category-grid-image {
                width: 100%;
                max-width: none;
                max-height: none;
                aspect-ratio: 3 / 4;
                border-radius: 6px;
            }

            .category-mode-grid .category-grid-image > a {
                position: relative;
                display: block;
                width: 100%;
                height: 100%;
                aspect-ratio: 3 / 4;
                overflow: hidden;
            }

            .category-mode-grid .category-grid-info {
                width: 100%;
                max-width: none;
                margin: 0;
                padding: 12px 0 0;
                transform: none;
                gap: 8px;
                background: transparent !important;
                box-shadow: none !important;
                text-align: left;
            }

            .category-mode-grid .category-grid-info > p {
                margin: 0;
                text-align: left;
            }

            .category-mode-grid .category-grid-price {
                display: flex;
                align-items: baseline;
                justify-content: flex-start;
                gap: 8px;
                margin: 0;
                padding: 0;
                text-align: left;
            }

            .category-mode-grid .category-grid-price p {
                width: auto;
                margin: 0 !important;
                padding: 0 !important;
                text-align: left;
                white-space: nowrap;
            }

            .category-mode-grid .category-grid-price .line-through {
                color: #9ca3af;
                font-weight: 300;
                text-decoration-thickness: 1px;
            }

            .category-mode-grid .category-grid-actions {
                position: absolute;
                z-index: 5;
                bottom: 12px;
                left: 50%;
                display: flex;
                gap: 8px;
                transform: translateX(-50%);
                pointer-events: none;
            }

            .category-mode-grid .category-grid-action {
                display: flex;
                width: 44px;
                height: 44px;
                align-items: center;
                justify-content: center;
                border: 0;
                border-radius: 4px;
                background: #fff;
                color: #111;
                opacity: 0;
                transform: translateY(18px);
                transition: opacity 360ms ease, transform 520ms cubic-bezier(.2, .75, .25, 1), background-color 180ms ease, color 180ms ease;
                pointer-events: none;
                cursor: pointer;
            }

            .category-mode-grid .category-grid-action:nth-child(1) { transition-delay: 0ms; }
            .category-mode-grid .category-grid-action:nth-child(2) { transition-delay: 110ms; }
            .category-mode-grid .category-grid-action:nth-child(3) { transition-delay: 220ms; }

            .category-mode-grid .category-grid-action svg {
                width: 24px;
                height: 24px;
            }

            .category-mode-grid .category-grid-action:hover,
            .category-mode-grid .category-grid-action.is-active {
                background: #000;
                color: #fff;
            }

            .category-mode-grid .category-grid-image:hover .category-grid-action,
            .category-mode-grid .category-grid-image:focus-within .category-grid-action {
                opacity: 1;
                transform: translateY(0);
                pointer-events: auto;
            }

            .category-mode-list .category-product-list > div {
                display: grid;
                width: 100%;
                max-width: none;
                grid-template-columns: minmax(0, 250px) minmax(0, 1fr);
                align-items: start;
            }

            .category-mode-list .category-product-list > div > div:first-child {
                width: 100%;
                max-width: none;
                max-height: none;
            }

            .category-mode-list .category-product-list > div > div:first-child > a {
                position: relative;
                display: block;
                aspect-ratio: 3 / 4;
                overflow: hidden;
            }

            .category-mode-list .category-product-list > div > div:first-child > a > * {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                min-width: 0;
            }

            .category-mode-list .category-product-list > div > div:first-child > a img {
                object-fit: cover;
                transform: scale(1);
                transition: transform 550ms cubic-bezier(0.2, 0.7, 0.2, 1);
                will-change: transform;
            }

            .category-mode-list .category-product-list > div > div:first-child > a:hover img {
                transform: scale(1.06);
            }

            .category-mode-list .category-product-list > div > div:nth-child(2) {
                min-width: 0;
                align-self: start;
                justify-items: start;
                align-content: start;
                margin: 0;
                padding: 0;
                text-align: left;
            }

            .category-mode-list .category-product-list > div > div:nth-child(2) > p {
                margin: 0;
                text-align: left;
            }

            .category-mode-list .category-product-list > div > div:nth-child(2) > div.flex {
                justify-content: flex-start;
                text-align: left;
            }

            .category-mode-list .category-list-info {
                justify-items: start;
                text-align: left;
            }

            .category-mode-list .category-list-price {
                display: flex;
                align-items: baseline;
                justify-content: flex-start !important;
                gap: 8px;
                text-align: left;
            }

            .category-mode-list .category-list-price p {
                width: auto;
                margin: 0 !important;
                padding: 0 !important;
                text-indent: 0;
                text-align: left;
                white-space: nowrap;
            }

            .category-mode-list .category-list-heart {
                background-color: #fff !important;
                border-color: #111 !important;
                transition: color 180ms ease, border-color 180ms ease;
            }

            .category-mode-list .category-list-heart:hover {
                background-color: #e5e7eb !important;
            }

            .category-mode-list .category-list-actions .primary-button:hover {
                opacity: .88;
            }

            @media (max-width: 767px) {
                .category-hero { min-height: 135px; padding: 20px 16px; }
                .category-hero h1 { font-size: 27px; }
                .category-toolbar-row { margin-top: 24px; }
                .category-product-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
                .category-mode-grid .category-grid-image { max-height: none; }
                .category-mode-grid .category-grid-actions { bottom: 8px; gap: 6px; }
                .category-mode-grid .category-grid-action { width: 36px; height: 36px; opacity: 1; transform: none; pointer-events: auto; }
                .category-mode-grid .category-grid-action svg { width: 20px; height: 20px; }
            }

            @media (min-width: 768px) and (max-width: 1180px) {
                .category-product-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 24px; }
            }

            @media (min-width: 768px) and (max-width: 1060px) {
                .category-product-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            }
        </style>
    @endpush

    <!-- Page Title -->
    <x-slot:title>
        {{ trim($category->meta_title) != "" ? $category->meta_title : $category->name }}
    </x-slot>

    <section class="category-hero">
        <h1>{{ $category->name }}</h1>
    </section>

    {!! view_render_event('bagisto.shop.categories.view.banner_path.before') !!}

    <!-- Hero Image -->
    @if ($category->banner_path)
        <div class="container mt-8 px-[60px] max-lg:px-8 max-md:mt-4 max-md:px-4">
            <x-shop::media.images.lazy
                class="aspect-[4/1] max-h-full max-w-full rounded-xl"
                src="{{ $category->banner_url }}"
                alt="{{ $category->name }}"
                width="1320"
                height="300"
            />
        </div>
    @endif

    {!! view_render_event('bagisto.shop.categories.view.banner_path.after') !!}

    {!! view_render_event('bagisto.shop.categories.view.description.before') !!}

    {!! view_render_event('bagisto.shop.categories.view.description.after') !!}

    @if (in_array($category->display_mode, [null, 'products_only', 'products_and_description']))
        <!-- Category Vue Component -->
        <v-category>
            <!-- Category Shimmer Effect -->
            <x-shop::shimmer.categories.view />
        </v-category>
    @endif

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-category-template"
        >
            <div :class="(filters.toolbar.applied.mode ?? filters.toolbar.default.mode) === 'list' ? 'category-page category-mode-list' : 'category-page category-mode-grid'">
                <div class="container px-[60px] max-lg:px-8 max-md:px-4">
                    <div class="category-toolbar-row flex items-center gap-6">
                        @include('shop::categories.filters')

                        <div class="min-w-0 flex-1">
                        <!-- Desktop Product Listing Toolbar -->
                        <div class="max-md:hidden">
                            @include('shop::categories.toolbar')
                        </div>
                        </div>
                    </div>

                    <!-- Product Listing Container -->
                    <div class="flex-1">

                        <!-- Product List Card Container -->
                        <div
                            class="category-product-list mt-8 grid grid-cols-1 gap-6"
                            v-if="(filters.toolbar.applied.mode ?? filters.toolbar.default.mode) === 'list'"
                        >
                            <!-- Product Card Shimmer Effect -->
                            <template v-if="isLoading">
                                <x-shop::shimmer.products.cards.list count="12" />
                            </template>

                            <!-- Product Card Listing -->
                            {!! view_render_event('bagisto.shop.categories.view.list.product_card.before') !!}

                            <template v-else>
                                <template v-if="products.length">
                                    <x-shop::products.card
                                        ::mode="'list'"
                                        v-for="product in products"
                                    />
                                </template>

                                <!-- Empty Products Container -->
                                <template v-else>
                                    <div class="m-auto grid w-full place-content-center items-center justify-items-center py-32 text-center">
                                        <img
                                            class="max-md:h-[100px] max-md:w-[100px]"
                                            src="{{ bagisto_asset('images/thank-you.png') }}"
                                            alt="@lang('shop::app.categories.view.empty')"
                                            loading="lazy"
                                            decoding="async"
                                        />

                                        <p
                                            class="text-xl max-md:text-sm"
                                            role="heading"
                                        >
                                            @lang('shop::app.categories.view.empty')
                                        </p>
                                    </div>
                                </template>
                            </template>

                            {!! view_render_event('bagisto.shop.categories.view.list.product_card.after') !!}
                        </div>

                        <!-- Product Grid Card Container -->
                        <div v-else class="mt-8 max-md:mt-5">
                            <!-- Product Card Shimmer Effect -->
                            <template v-if="isLoading">
                                <div class="category-product-grid grid gap-8">
                                    <x-shop::shimmer.products.cards.grid count="12" />
                                </div>
                            </template>

                            {!! view_render_event('bagisto.shop.categories.view.grid.product_card.before') !!}

                            <!-- Product Card Listing -->
                            <template v-else>
                                <template v-if="products.length">
                                    <div class="category-product-grid grid gap-8">
                                        <x-shop::products.card
                                            ::mode="'grid'"
                                            v-for="product in products"
                                        />
                                    </div>
                                </template>

                                <!-- Empty Products Container -->
                                <template v-else>
                                    <div class="m-auto grid w-full place-content-center items-center justify-items-center py-32 text-center">
                                        <img
                                            class="max-md:h-[100px] max-md:w-[100px]"
                                            src="{{ bagisto_asset('images/thank-you.png') }}"
                                            alt="@lang('shop::app.categories.view.empty')"
                                            loading="lazy"
                                            decoding="async"
                                        />

                                        <p
                                            class="text-xl max-md:text-sm"
                                            role="heading"
                                        >
                                            @lang('shop::app.categories.view.empty')
                                        </p>
                                    </div>
                                </template>
                            </template>

                            {!! view_render_event('bagisto.shop.categories.view.grid.product_card.after') !!}
                        </div>

                        {!! view_render_event('bagisto.shop.categories.view.load_more_button.before') !!}

                        <!-- Load More Button -->
                        <button
                            class="secondary-button mx-auto mt-14 block w-max rounded-2xl px-11 py-3 text-center text-base max-md:rounded-lg max-sm:mt-6 max-sm:px-6 max-sm:py-1.5 max-sm:text-sm"
                            @click="loadMoreProducts"
                            v-if="links.next && ! loader"
                        >
                            @lang('shop::app.categories.view.load-more')
                        </button>

                        <button
                            v-else-if="links.next"
                            class="secondary-button mx-auto mt-14 block w-max rounded-2xl px-[74.5px] py-3.5 text-center text-base max-md:rounded-lg max-md:py-3 max-sm:mt-6 max-sm:px-[50.8px] max-sm:py-1.5"
                        >
                            <!-- Spinner -->
                            <img
                                class="h-5 w-5 animate-spin text-navyBlue"
                                src="{{ bagisto_asset('images/spinner.svg') }}"
                                alt="Loading"
                            />
                        </button>

                        {!! view_render_event('bagisto.shop.categories.view.grid.load_more_button.after') !!}
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-category', {
                template: '#v-category-template',

                data() {
                    return {
                        isMobile: window.innerWidth <= 767,

                        isLoading: true,

                        isDrawerActive: {
                            toolbar: false,

                            filter: false,
                        },

                        filters: {
                            toolbar: {
                                default: {},

                                applied: {},
                            },

                            filter: {},
                        },

                        products: [],

                        links: {},

                        loader: false,
                    }
                },

                computed: {
                    queryParams() {
                        let queryParams = Object.assign({}, this.filters.filter, this.filters.toolbar.applied);

                        queryParams.sort = queryParams.sort || 'created_at-desc';

                        return this.removeJsonEmptyValues(queryParams);
                    },

                    queryString() {
                        return this.jsonToQueryString(this.queryParams);
                    },
                },

                watch: {
                    queryParams() {
                        this.getProducts();
                    },

                    queryString() {
                        window.history.pushState({}, '', '?' + this.queryString);
                    },
                },

                methods: {
                    setFilters(type, filters) {
                        this.filters[type] = filters;
                    },

                    clearFilters(type, filters) {
                        this.filters[type] = {};
                    },

                    getProducts() {
                        this.isDrawerActive = {
                            toolbar: false,

                            filter: false,
                        };

                        document.body.style.overflow ='scroll';

                        this.isLoading = true;

                        this.$axios.get("{{ route('shop.api.products.index', ['category_id' => $category->id]) }}", {
                            params: this.queryParams
                        })
                            .then(response => {
                                this.isLoading = false;

                                this.products = response.data.data;

                                this.links = response.data.links;
                            }).catch(error => {
                                console.log(error);
                            });
                    },

                    loadMoreProducts() {
                        if (! this.links.next) {
                            return;
                        }

                        this.loader = true;

                        this.$axios.get(this.links.next)
                            .then(response => {
                                this.loader = false;

                                this.products = [...this.products, ...response.data.data];

                                this.links = response.data.links;
                            }).catch(error => {
                                console.log(error);
                            });
                    },

                    removeJsonEmptyValues(params) {
                        Object.keys(params).forEach(function (key) {
                            if ((! params[key] && params[key] !== undefined)) {
                                delete params[key];
                            }

                            if (Array.isArray(params[key])) {
                                params[key] = params[key].join(',');
                            }
                        });

                        return params;
                    },

                    jsonToQueryString(params) {
                        let parameters = new URLSearchParams();

                        for (const key in params) {
                            parameters.append(key, params[key]);
                        }

                        return parameters.toString();
                    }
                },
            });
        </script>
    @endPushOnce
</x-shop::layouts>
