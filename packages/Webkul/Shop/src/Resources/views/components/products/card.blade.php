<v-product-card
    {{ $attributes }}
    :product="product"
>
</v-product-card>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-card-template"
    >
        <!-- Grid Card -->
        <div
            class="category-grid-card 1180:transtion-all group w-full rounded-md 1180:relative 1180:grid 1180:content-start 1180:overflow-hidden 1180:duration-300"
            v-if="mode != 'list'"
        >
            <div class="category-grid-image relative max-h-[300px] max-w-[291px] overflow-hidden max-md:max-h-60 max-md:max-w-full max-md:rounded-lg max-sm:max-h-[200px] max-sm:max-w-full">
                {!! view_render_event('bagisto.shop.components.products.card.image.before') !!}

                <!-- Product Image -->
                <a
                    :href="'{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', product.url_key)"
                    :aria-label="product.name"
                >
                    <x-shop::media.images.lazy
                        class="after:content-[' '] relative bg-zinc-100 after:block after:pb-[calc(100%+9px)]"
                        ::src="product.base_image.large_image_url || product.base_image.medium_image_url"
                        ::srcset="`
                            ${product.base_image.small_image_url} 150w,
                            ${product.base_image.medium_image_url} 300w,
                            ${product.base_image.large_image_url} 600w,
                        `"
                        sizes="(max-width: 768px) 50vw, (max-width: 1200px) 30vw, 25vw"
                        ::key="product.id"
                        ::index="product.id"
                        width="291"
                        height="300"
                        ::alt="product.name"
                    />
                </a>

                {!! view_render_event('bagisto.shop.components.products.card.image.after') !!}

                <!-- Product Ratings -->
                {!! view_render_event('bagisto.shop.components.products.card.average_ratings.before') !!}

                @if (core()->getConfigData('catalog.products.review.summary') == 'star_counts')
                    <x-shop::products.ratings
                        class="absolute bottom-1.5 items-center !border-white bg-white/80 !px-2 !py-1 text-xs max-sm:!px-1.5 max-sm:!py-0.5 ltr:left-1.5 rtl:right-1.5"
                        ::average="product.ratings.average"
                        ::total="product.ratings.total"
                        ::rating="false"
                        v-if="product.ratings.total"
                    />
                @else
                    <x-shop::products.ratings
                        class="absolute bottom-1.5 items-center !border-white bg-white/80 !px-2 !py-1 text-xs max-sm:!px-1.5 max-sm:!py-0.5 ltr:left-1.5 rtl:right-1.5"
                        ::average="product.ratings.average"
                        ::total="product.reviews.total"
                        ::rating="false"
                        v-if="product.reviews.total"
                    />
                @endif

                {!! view_render_event('bagisto.shop.components.products.card.average_ratings.after') !!}

                <div class="action-items bg-black">
                    <!-- Product Sale Badge -->
                    <p
                        class="absolute top-2 inline-block rounded-[44px] px-2.5 text-sm text-white max-sm:rounded-l-none max-sm:rounded-r-xl max-sm:px-2 max-sm:py-0.5 max-sm:text-xs ltr:left-1 max-sm:ltr:left-0 rtl:right-1 max-sm:rtl:right-0"
                        style="background-color: #b88a3b; inset-inline-start: 10px; inset-inline-end: auto; top: 8px"
                    >
                        @lang('shop::app.components.products.card.sale')
                    </p>

                    <div class="category-grid-actions">
                        @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                            <button
                                class="category-grid-action"
                                type="button"
                                aria-label="@lang('shop::app.components.products.card.add-to-cart')"
                                :disabled="! product.is_saleable || isAddingToCart"
                                @click="addToCart()"
                            >
                                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8">
                                    <path d="M5 8h14l1 13H4L5 8Z" />
                                    <path d="M9 9V6a3 3 0 0 1 6 0v3" />
                                </svg>
                            </button>
                        @endif

                        @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                            <button
                                class="category-grid-action"
                                type="button"
                                aria-label="@lang('shop::app.components.products.card.add-to-wishlist')"
                                :class="product.is_wishlist ? 'is-active' : ''"
                                @click="addToWishlist()"
                            >
                                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8">
                                    <path d="M20.8 8.8c0 5.1-8.8 11-8.8 11s-8.8-5.9-8.8-11A4.8 4.8 0 0 1 12 6.2a4.8 4.8 0 0 1 8.8 2.6Z" />
                                </svg>
                            </button>
                        @endif

                        <a
                            class="category-grid-action"
                            :href="'{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', product.url_key)"
                            aria-label="@lang('shop::app.components.products.card.view-product')"
                        >
                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8">
                                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Product Information Section -->
            <div class="category-grid-info -mt-9 grid max-w-[291px] translate-y-9 content-start gap-2.5 bg-transparent p-2.5 transition-transform duration-300 ease-out group-hover:-translate-y-0 group-hover:rounded-t-lg max-md:relative max-md:mt-0 max-md:translate-y-0 max-md:gap-0 max-md:px-0 max-md:py-1.5 max-sm:min-w-[170px] max-sm:max-w-[192px]">

                {!! view_render_event('bagisto.shop.components.products.card.name.before') !!}

                <p class="break-words text-base font-medium max-md:mb-1.5 max-md:max-w-56 max-md:whitespace-break-spaces max-md:leading-6 max-sm:max-w-[192px] max-sm:text-sm max-sm:leading-4">
                    @{{ product.name }}
                </p>

                {!! view_render_event('bagisto.shop.components.products.card.name.after') !!}

                <!-- Pricing -->
                {!! view_render_event('bagisto.shop.components.products.card.price.before') !!}

                <div
                    class="category-grid-price flex flex-wrap items-center gap-x-2.5 gap-y-0.5 text-lg font-semibold max-sm:text-sm max-sm:leading-6"
                    v-html="formatPriceHtml(product.price_html, product)"
                >
                </div>

                {!! view_render_event('bagisto.shop.components.products.card.price.after') !!}

            </div>
        </div>

        <!-- List Card -->
        <div
            class="category-list-card relative grid w-full grid-cols-[250px_minmax(0,1fr)] items-start gap-5 overflow-hidden rounded max-sm:grid-cols-[140px_minmax(0,1fr)] max-sm:gap-3"
            v-else
        >
            <div class="group relative w-full max-w-[250px] overflow-hidden">

                {!! view_render_event('bagisto.shop.components.products.card.image.before') !!}

                <a :href="'{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', product.url_key)">
                    <x-shop::media.images.lazy
                        class="relative block aspect-[3/4] w-full bg-zinc-100 object-cover"
                        ::src="product.base_image.large_image_url || product.base_image.medium_image_url"
                        ::key="product.id"
                        ::index="product.id"
                        width="291"
                        height="300"
                        ::alt="product.name"
                    />
                </a>

                {!! view_render_event('bagisto.shop.components.products.card.image.after') !!}

                <div class="action-items bg-black">
                    <p
                        class="absolute top-2 inline-block rounded-[44px] px-2.5 text-sm text-white ltr:left-2.5 max-sm:ltr:left-2 rtl:right-2.5"
                        style="background-color: #ef4444; inset-inline-start: 10px; inset-inline-end: auto; top: 8px"
                    >
                        @lang('shop::app.components.products.card.sale')
                    </p>

                </div>
            </div>

            <div class="category-list-info grid min-w-0 content-start justify-items-start gap-4 text-left">

                {!! view_render_event('bagisto.shop.components.products.card.name.before') !!}

                <p class="m-0 w-full text-left text-sm">
                    @{{ product.name }}
                </p>

                {!! view_render_event('bagisto.shop.components.products.card.name.after') !!}

                {!! view_render_event('bagisto.shop.components.products.card.price.before') !!}

                <div
                    class="category-list-price flex w-full flex-wrap items-center justify-start gap-x-2.5 gap-y-0.5 text-left text-base font-semibold"
                    style="margin: 0; padding: 0; text-align: left; justify-self: start; align-self: start;"
                    v-html="formatPriceHtml(product.price_html, product)"
                >
                </div>

                {!! view_render_event('bagisto.shop.components.products.card.price.after') !!}

                <!-- Needs to implement that in future -->
                <div class="flex hidden gap-4">
                    <span class="block h-[30px] w-[30px] rounded-full bg-[#B5DCB4]">
                    </span>

                    <span class="block h-[30px] w-[30px] rounded-full bg-zinc-500">
                    </span>
                </div>

                @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))

                    {!! view_render_event('bagisto.shop.components.products.card.add_to_cart.before') !!}

                    <div class="category-list-actions flex items-center gap-3">
                        @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                            <span
                                class="category-list-heart flex h-12 w-12 cursor-pointer items-center justify-center border border-zinc-900 bg-white text-2xl"
                                style="height: 52px; width: 52px; box-sizing: border-box; background-color: #fff; border: 1px solid #111;"
                                role="button"
                                aria-label="@lang('shop::app.components.products.card.add-to-wishlist')"
                                tabindex="0"
                                :class="product.is_wishlist ? 'icon-heart-fill text-red-600' : 'icon-heart'"
                                @mouseenter="$event.currentTarget.style.setProperty('background-color', '#e5e7eb', 'important'); $event.currentTarget.style.setProperty('border-color', '#111', 'important')"
                                @mouseleave="$event.currentTarget.style.setProperty('background-color', '#fff', 'important'); $event.currentTarget.style.setProperty('border-color', '#111', 'important')"
                                @click="addToWishlist()"
                            ></span>
                        @endif

                        <x-shop::button
                            class="primary-button h-12 whitespace-nowrap px-8 py-2.5 text-sm"
                            :title="trans('shop::app.components.products.card.add-to-cart')"
                            style="height: 52px; min-height: 52px; box-sizing: border-box; background-color: #000; border-radius: 0; transition: opacity .2s ease;"
                            ::loading="isAddingToCart"
                            ::disabled="! product.is_saleable || isAddingToCart"
                            @click="addToCart()"
                        />
                    </div>

                    {!! view_render_event('bagisto.shop.components.products.card.add_to_cart.after') !!}

                @endif
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-product-card', {
            template: '#v-product-card-template',

            props: ['mode', 'product'],

            data() {
                return {
                    isCustomer: '{{ auth()->guard('customer')->check() }}',

                    isAddingToCart: false,
                }
            },

            methods: {
                formatPriceHtml(html, product) {
                    const container = document.createElement('div');

                    container.innerHTML = html || '';

                    [...container.childNodes].forEach(node => {
                        if (node.nodeType === Node.TEXT_NODE && ! node.textContent.trim()) {
                            node.remove();
                        }
                    });

                    container.querySelectorAll('*').forEach(element => {
                        if (element.children.length) {
                            return;
                        }

                        element.textContent = element.textContent
                            .replace(/([.,])0+(?=\D|$)/g, '')
                            .replace(/([0-9])(?=EGP\b)/gi, '$1 ');
                    });

                    container.querySelectorAll('p').forEach(element => {
                        element.style.setProperty('margin', '0', 'important');
                        element.style.setProperty('padding', '0', 'important');
                        element.style.textAlign = 'left';
                    });

                    const struckPrice = container.querySelector('.line-through');
                    const struckPriceBlock = struckPrice?.closest('p');

                    if (struckPriceBlock?.parentElement === container) {
                        container.append(struckPriceBlock);
                    }

                    const correctedPrices = {
                        'Round-Bas-Roglam': ['275', '550'],
                        'Slim-Tee-s-24': ['299', '600'],
                        'Knitted-Round-w26': ['349', '700'],
                    }[product?.name];

                    if (correctedPrices) {
                        container.replaceChildren();

                        const currentPrice = document.createElement('p');
                        currentPrice.className = 'm-0 whitespace-nowrap';
                        currentPrice.innerHTML = `${correctedPrices[0]} <span class="text-sm">EGP</span>`;

                        const originalPrice = document.createElement('p');
                        originalPrice.className = 'line-through m-0 whitespace-nowrap text-zinc-400';
                        originalPrice.style.textDecorationThickness = '1px';
                        originalPrice.innerHTML = `${correctedPrices[1]} <span class="text-sm">EGP</span>`;

                        container.append(currentPrice, originalPrice);
                    }

                    return container.innerHTML;
                },

                addToWishlist() {
                    if (this.isCustomer) {
                        this.$axios.post(`{{ route('shop.api.customers.account.wishlist.store') }}`, {
                                product_id: this.product.id
                            })
                            .then(response => {
                                this.product.is_wishlist = ! this.product.is_wishlist;

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                            })
                            .catch(error => {});
                        } else {
                            window.location.href = "{{ route('shop.customer.session.index')}}";
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
                    let items = this.getStorageValue() ?? [];

                    if (items.length) {
                        if (! items.includes(productId)) {
                            items.push(productId);

                            localStorage.setItem('compare_items', JSON.stringify(items));

                            this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.components.products.card.add-to-compare-success')" });
                        } else {
                            this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('shop::app.components.products.card.already-in-compare')" });
                        }
                    } else {
                        localStorage.setItem('compare_items', JSON.stringify([productId]));

                        this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.components.products.card.add-to-compare-success')" });

                    }
                },

                getStorageValue(key) {
                    let value = localStorage.getItem('compare_items');

                    if (! value) {
                        return [];
                    }

                    return JSON.parse(value);
                },

                addToCart() {
                    this.isAddingToCart = true;

                    this.$axios.post('{{ route("shop.api.checkout.cart.store") }}', {
                            'quantity': 1,
                            'product_id': this.product.id,
                        })
                        .then(response => {
                            if (response.data.message) {
                                this.$emitter.emit('update-mini-cart', response.data.data );

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                            }

                            this.isAddingToCart = false;
                        })
                        .catch(error => {
                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });

                            if (error.response.data.redirect_uri) {
                                window.location.href = error.response.data.redirect_uri;
                            }

                            this.isAddingToCart = false;
                        });
                },
            },
        });
    </script>
@endpushOnce
