<!-- Mini Cart Vue Component -->
<v-mini-cart>
    <span
        class="icon-cart cursor-pointer text-2xl transition-colors hover:text-grey"
        role="button"
        aria-label="@lang('shop::app.checkout.cart.mini-cart.shopping-cart')"
    ></span>
</v-mini-cart>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-mini-cart-template"
    >
        {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.before') !!}

        @if (core()->getConfigData('sales.checkout.mini_cart.display_mini_cart'))
            <x-shop::drawer
                class="mini-cart-drawer"
                width="520px"
                v-on:open="onDrawerOpen"
                v-on:close="onDrawerClose"
            >
                <!-- Drawer Toggler -->
                <x-slot:toggle>
                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.toggle.before') !!}

                    <span class="relative">
                        <span
                            class="icon-cart cursor-pointer text-2xl transition-colors hover:text-grey"
                            role="button"
                            aria-label="@lang('shop::app.checkout.cart.mini-cart.shopping-cart')"
                            tabindex="0"
                            @click="getCart"
                        ></span>

                        @if (core()->getConfigData('sales.checkout.my_cart.summary') == 'display_item_quantity')
                            <span
                                class="absolute -top-3 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-semibold leading-none text-white ltr:-right-2 rtl:left-0"
                                v-if="cart?.items_qty"
                            >
                                @{{ cart.items_qty }}
                            </span>
                        @else
                            <span
                                class="absolute -top-3 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-semibold leading-none text-white ltr:-right-2 rtl:left-0"
                                v-if="cart?.items_count"
                            >
                                @{{ cart.items_count }}
                            </span>
                        @endif
                    </span>

                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.toggle.after') !!}
                </x-slot>

                <!-- Drawer Header -->
                <x-slot:header class="mini-cart-header">
                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.header.before') !!}

                    <div class="flex items-center justify-between">
                        <p class="mini-cart-title text-3xl font-medium max-md:text-2xl max-sm:text-xl">
                            @lang('shop::app.checkout.cart.mini-cart.shopping-cart')
                        </p>
                    </div>

                    <p class="mini-cart-subtitle text-base text-zinc-500 max-md:text-sm max-sm:text-xs">
                        Easy exchange within 14 days — Shop with confidence
                    </p>

                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.header.after') !!}
                </x-slot>

                <!-- Drawer Content -->
                <x-slot:content>
                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.before') !!}

                    <!-- Cart Item Listing -->
                    <div
                        class="mini-cart-items mt-9 grid gap-12 max-md:mt-2.5 max-md:gap-5"
                        v-if="cart?.items?.length"
                    >
                        <div
                            class="mini-cart-item flex gap-x-5 max-md:gap-x-4"
                            v-for="item in cart?.items"
                        >
                            <!-- Cart Item Image -->
                            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.image.before') !!}

                            <div class="shrink-0">
                                <a :href="'{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', item.product_url_key)">
                    <img
                                        :src="item.base_image.medium_image_url || item.base_image.small_image_url"
                                        class="mini-cart-product-image rounded-md object-cover"
                                    />
                                </a>
                            </div>

                            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.image.after') !!}

                        <!-- Cart Item Information -->
                        <div class="mini-cart-info grid flex-1 place-content-start justify-stretch gap-y-2.5">
                            <div class="flex justify-between gap-2 max-md:gap-0 max-sm:flex-wrap">

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.name.before') !!}

                                    <a
                                    class="max-w-4/5 max-md:w-full"
                                    :href="'{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', item.product_url_key)"
                                >
                                        <p class="mini-cart-product-name text-base font-semibold max-sm:text-sm">
                                            @{{ item.name }}
                                        </p>
                                    </a>

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.name.after') !!}

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.price.before') !!}

                                    <template v-if="displayTax.prices == 'including_tax'">
                                        <p class="mini-cart-product-price text-lg font-semibold max-sm:text-sm">
                                            @{{ formatCartPrice(item.formatted_price_incl_tax) }}
                                        </p>
                                    </template>

                                    <template v-else-if="displayTax.prices == 'both'">
                                        <p class="mini-cart-product-price flex flex-col text-lg font-semibold max-sm:text-sm">
                                            @{{ formatCartPrice(item.formatted_price_incl_tax) }}

                                            <span class="text-xs font-normal text-zinc-500">
                                                @lang('shop::app.checkout.cart.mini-cart.excl-tax')

                                                <span class="font-semibold text-black">@{{ formatCartPrice(item.formatted_price) }}</span>
                                            </span>
                                        </p>
                                    </template>

                                    <template v-else>
                                        <p class="mini-cart-product-price text-lg font-semibold max-sm:text-sm">
                                            @{{ formatCartPrice(item.formatted_price) }}
                                        </p>
                                    </template>

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.price.after') !!}
                                </div>

                                <!-- Cart Item Options Container -->
                                <div
                                    class="grid select-none gap-x-2.5 gap-y-1.5 max-sm:gap-y-0.5"
                                    v-if="item.options.length"
                                >

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.product_details.before') !!}

                                    <!-- Details Toggler -->
                                    <div class="hidden">
                                        <p
                                            class="flex cursor-pointer items-center gap-x-4 text-base max-md:gap-x-1.5 max-md:text-sm max-sm:text-xs"
                                            @click="item.option_show = ! item.option_show"
                                        >
                                            @lang('shop::app.checkout.cart.mini-cart.see-details')

                                            <span
                                                class="text-2xl max-md:text-xl max-sm:text-lg"
                                                :class="{'icon-arrow-up': item.option_show, 'icon-arrow-down': ! item.option_show}"
                                            ></span>
                                        </p>
                                    </div>

                                    <!-- Option Details -->
                                    <div
                                        class="flex flex-wrap gap-x-4 gap-y-1"
                                    >
                                        <template v-for="attribute in orderedOptions(item.options)">
                                            <div class="flex items-center gap-1">
                                                <p class="text-sm font-medium text-zinc-500 max-md:font-normal max-sm:text-xs">
                                                    @{{ attribute.attribute_name + ':' }}
                                                </p>

                                                <p class="text-sm max-sm:text-xs">
                                                    <template v-if="attribute?.attribute_type === 'file'">
                                                        <a
                                                            :href="attribute.file_url"
                                                            class="text-blue-700"
                                                            target="_blank"
                                                            :download="attribute.file_name"
                                                        >
                                                            @{{ attribute.file_name }}
                                                        </a>
                                                    </template>

                                                    <template v-else>
                                                        @{{ attribute.option_label }}
                                                    </template>
                                                </p>
                                            </div>
                                        </template>
                                    </div>

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.product_details.after') !!}
                                </div>

                                <div class="flex w-full flex-wrap items-center justify-between gap-5 max-md:gap-2.5">
                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.quantity_changer.before') !!}

                                <!-- Cart Item Quantity Changer -->
                                <x-shop::quantity-changer
                                    v-if="item.can_change_qty"
                                    ::key="'qty-' + item.id + '-' + refreshKey"
                                    class="max-h-11 max-w-[118px] gap-x-2 border-zinc-900 px-2 py-1 max-md:gap-x-2 max-md:px-2 max-md:py-1"
                                    name="quantity"
                                    ::value="item?.quantity"
                                    @change="updateItem($event, item)"
                                />

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.quantity_changer.after') !!}

                                {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.remove_button.before') !!}

                                <!-- Cart Item Remove Button -->
                                <button
                                    type="button"
                                    class="text-red-500 max-md:text-sm"
                                    aria-label="@lang('shop::app.checkout.cart.mini-cart.remove')"
                                    @click="removeItem(item.id)"
                                >
                                    <span class="icon-bin text-xl" aria-hidden="true"></span>
                                </button>

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.remove_button.after') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty Cart Section -->
                    <div
                        class="mini-cart-empty-state"
                        v-else
                    >
                        <div class="mini-cart-empty-content">
                            <svg class="mini-cart-empty-bag" viewBox="0 0 220 230" fill="none" aria-hidden="true">
                                <path d="M36 67h148l17 142c.7 6.6-4.4 12.4-11 12.4H30c-6.6 0-11.7-5.8-11-12.4L36 67Z" stroke="currentColor" stroke-width="6" stroke-linejoin="round" />
                                <path d="M70 69V43a40 40 0 0 1 80 0v26" stroke="currentColor" stroke-width="6" stroke-linecap="round" />
                                <circle cx="69" cy="81" r="7" fill="currentColor" />
                                <circle cx="151" cy="81" r="7" fill="currentColor" />
                                <text x="34" y="198" fill="currentColor" font-family="Arial, Helvetica, sans-serif" font-size="43" font-weight="700" letter-spacing="-2">Keswa</text>
                                <text x="145" y="164" fill="currentColor" font-family="Arial, Helvetica, sans-serif" font-size="9" font-weight="700">EST. 2012</text>
                            </svg>

                            <p role="heading">
                                @lang('shop::app.checkout.cart.mini-cart.empty-cart')
                            </p>
                        </div>
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.after') !!}
                </x-slot>

            <!-- Drawer Footer -->
            <x-slot:footer>
                <div
                    v-if="cart?.items?.length"
                    class="mini-cart-footer grid-col-1 grid gap-5 max-md:gap-2.5"
                >
                    <div
                        class="mini-cart-subtotal my-8 flex items-center justify-between border-b border-zinc-200 px-6 pb-2 max-md:my-0 max-md:border-t max-md:px-5 max-md:py-2"
                        :class="{'!justify-end': isLoading}"
                    >
                        {!! view_render_event('bagisto.shop.checkout.mini-cart.subtotal.before') !!}

                        <template v-if="! isLoading">
                            <p class="text-sm font-medium text-zinc-500">
                                @lang('shop::app.checkout.cart.mini-cart.subtotal')
                            </p>

                        <template v-if="displayTax.subtotal == 'including_tax'">
                            <p class="text-3xl font-semibold max-md:text-base">
                                @{{ formatCartPrice(cart.formatted_sub_total_incl_tax) }}
                            </p>
                        </template>

                        <template v-else-if="displayTax.subtotal == 'both'">
                            <p class="flex flex-col text-3xl font-semibold max-md:text-sm max-sm:text-right">
                                @{{ formatCartPrice(cart.formatted_sub_total_incl_tax) }}

                                <span class="text-sm font-normal text-zinc-500 max-sm:text-xs">
                                    @lang('shop::app.checkout.cart.mini-cart.excl-tax')

                                    <span class="font-semibold text-black">@{{ formatCartPrice(cart.formatted_sub_total) }}</span>
                                </span>
                            </p>
                        </template>

                        <template v-else>
                            <p class="text-3xl font-semibold max-md:text-base">
                                @{{ formatCartPrice(cart.formatted_sub_total) }}
                            </p>
                        </template>
                    </template>

                        <template v-else>
                            <!-- Spinner -->
                            <svg
                                class="text-blue h-8 w-8 animate-spin text-[5px] font-semibold max-md:h-7 max-md:w-7 max-sm:h-4 max-sm:w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                aria-hidden="true"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>

                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                        </template>

                            {!! view_render_event('bagisto.shop.checkout.mini-cart.subtotal.after') !!}
                        </div>

                        {!! view_render_event('bagisto.shop.checkout.mini-cart.action.before') !!}

                        <!-- Cart Action Container -->
                        <div class="mini-cart-actions grid gap-2.5 px-6 max-md:px-4 max-sm:gap-1.5">
                            {!! view_render_event('bagisto.shop.checkout.mini-cart.continue_to_checkout.before') !!}

                        <a
                            href="{{ route('shop.checkout.onepage.index') }}"
                            class="mx-auto block w-full cursor-pointer rounded-sm bg-black px-11 py-4 text-center text-base font-semibold text-white max-md:px-5 max-md:py-3"
                        >
                            @lang('shop::app.checkout.cart.mini-cart.continue-to-checkout')
                        </a>

                            {!! view_render_event('bagisto.shop.checkout.mini-cart.continue_to_checkout.after') !!}

                            <div class="block cursor-pointer border border-black py-3 text-center text-base font-medium max-md:py-3">
                                <a href="{{ route('shop.checkout.cart.index') }}">
                                    @lang('shop::app.checkout.cart.mini-cart.view-cart')
                                </a>
                            </div>
                        </div>

                        {!! view_render_event('bagisto.shop.checkout.mini-cart.action.after') !!}
                    </div>
                </x-slot>
            </x-shop::drawer>

        @else
            <a href="{{ route('shop.checkout.onepage.index') }}">
                {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.toggle.before') !!}

                    <span class="relative">
                        <span
                            class="icon-cart cursor-pointer text-2xl transition-colors hover:text-grey"
                            role="button"
                            aria-label="@lang('shop::app.checkout.cart.mini-cart.shopping-cart')"
                            tabindex="0"
                        ></span>

                        <span
                            class="absolute -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-semibold leading-none text-white ltr:right-0 rtl:left-0"
                            v-if="cart?.items_qty"
                        >
                            @{{ cart.items_qty }}
                        </span>
                    </span>

                {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.toggle.after') !!}
            </a>
        @endif

        {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.after') !!}
    </script>

    @php
        /**
         * When the cart is empty there is nothing to fetch, so the mini-cart is
         * seeded with an empty cart server-side. This avoids an `/api/checkout/cart`
         * request (and a `Cart::collectTotals()` recalculation) on every page view
         * for the common case of a guest with no cart.
         */
        $hasCartItems = (bool) \Webkul\Checkout\Facades\Cart::getCart()?->items->isNotEmpty();
    @endphp

    <script type="module">
        app.component("v-mini-cart", {
            template: '#v-mini-cart-template',

            data() {
                return  {
                    refreshKey: 0,

                    cart: {!! $hasCartItems ? 'null' : json_encode(['items_qty' => 0, 'items' => []]) !!},

                    isLoading:false,

                    displayTax: {
                        prices: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_prices') }}",
                        subtotal: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_subtotal') }}",
                    },
                };
            },

            mounted() {
                if (!this.cart) {
                    this.getCart();
                }

                /**
                 * Action.
                 */
                this.$emitter.on('update-mini-cart', (cart) => {
                    this.cart = cart;
                });
            },

            methods: {
                onDrawerOpen() {
                    document.body.classList.add('mini-cart-open');
                },

                onDrawerClose() {
                    document.body.classList.remove('mini-cart-open');
                },

                formatCartPrice(price) {
                    return String(price ?? '').replace(/([.,])00(?=\D*$)/, '');
                },

                orderedOptions(options) {
                    return [...(options || [])].sort((first, second) => {
                        const firstIsColor = /color|لون/i.test(first.attribute_name || '');
                        const secondIsColor = /color|لون/i.test(second.attribute_name || '');

                        return Number(secondIsColor) - Number(firstIsColor);
                    });
                },

                getCart() {
                    this.$axios.get('{{ route('shop.api.checkout.cart.index') }}')
                        .then(response => {
                            this.cart = response.data.data;
                        })
                        .catch(error => {});
                },

                updateItem(quantity, item) {
                    this.isLoading = true;

                    let qty = {};

                    qty[item.id] = quantity;

                    this.$axios.put('{{ route('shop.api.checkout.cart.update') }}', { qty })
                        .then(response => {
                            this.isLoading = false;

                            /**
                             * The update endpoint returns `{ data: CartResource, message }`
                             * on success and only `{ message }` on failure (e.g.
                             * inventory-warning). Only treat the payload as a cart when
                             * it has an `items` field — otherwise surface the server
                             * message as a warning flash.
                             */
                            const payload = response.data.data;

                            if (payload && payload.items !== undefined) {
                                this.cart = payload;
                            } else {
                                this.$emitter.emit('add-flash', {
                                    type: 'warning',
                                    message: payload?.message || response.data.message,
                                });
                            }

                            /**
                             * Bump the key so the quantity-changer remounts from the
                             * current server value even when the update was rejected
                             * (in which case `value` didn't change and the component's
                             * `value` watcher wouldn't fire).
                             */
                            this.refreshKey++;
                        })
                        .catch(error => {
                            this.isLoading = false;

                            this.$emitter.emit('add-flash', {
                                type: 'error',
                                message: error.response?.data?.message || error.message,
                            });

                            this.refreshKey++;
                        });
                },

                removeItem(itemId) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.isLoading = true;

                            this.$axios.post('{{ route('shop.api.checkout.cart.destroy') }}', {
                                '_method': 'DELETE',
                                'cart_item_id': itemId,
                            })
                            .then(response => {
                                this.cart = response.data.data;

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                this.isLoading = false;
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', { type: 'error', message: response.data.message });

                                this.isLoading = false;
                            });
                        }
                    });
                },
            },
        });
    </script>
@endpushOnce
