<div class="cart-summary-card w-[420px] max-w-full max-md:w-full">
    {!! view_render_event('bagisto.shop.checkout.cart.summary.title.before') !!}

    <p
        class="cart-summary-title text-2xl font-medium max-md:text-base"
        role="heading"
        aria-level="1"
    >
        @lang('shop::app.checkout.cart.summary.cart-summary')
    </p>

    {!! view_render_event('bagisto.shop.checkout.cart.summary.title.after') !!}

    <!-- Cart Totals -->
    <div class="mt-6 grid gap-4 max-md:mt-2 max-md:gap-2.5">
        <!-- Sub Total -->
        {!! view_render_event('bagisto.shop.checkout.cart.summary.sub_total.before') !!}

        <template v-if="displayTax.subtotal == 'including_tax'">
            <div class="flex justify-between text-right">
                <p class="text-base max-sm:text-sm">
                    @lang('shop::app.checkout.cart.summary.sub-total')
                </p>

                <p class="text-base font-medium max-sm:text-sm">
                    @{{ formatCartPrice(cart.formatted_sub_total_incl_tax) }}
                </p>
            </div>
        </template>

        <template v-else-if="displayTax.subtotal == 'both'">
            <div class="flex justify-between text-right">
                <p class="text-base max-sm:text-sm">
                    @lang('shop::app.checkout.cart.summary.sub-total')
                </p>

                <div>
                    <p class="text-base font-medium max-sm:text-sm">
                        @{{ formatCartPrice(cart.formatted_sub_total_incl_tax) }}
                    </p>

                    <p class="text-xs italic text-gray-500 dark:text-gray-400">
                        @lang('shop::app.checkout.cart.summary.excl-tax') @{{ formatCartPrice(cart.formatted_sub_total) }}
                    </p>
                </div>
            </div>
        </template>

        <template v-else>
            <div class="flex justify-between text-right">
                <p class="text-base max-sm:text-sm">
                    @lang('shop::app.checkout.cart.summary.sub-total')
                </p>

                <p class="text-base font-medium max-sm:text-sm">
                    @{{ formatCartPrice(cart.formatted_sub_total) }}
                </p>
            </div>
        </template>

        {!! view_render_event('bagisto.shop.checkout.cart.summary.sub_total.after') !!}

        <!-- Discount -->
        {!! view_render_event('bagisto.shop.checkout.cart.summary.discount_amount.before') !!}

        <template v-if="cart.discount_amount && parseFloat(cart.discount_amount) > 0">
            <!-- Single Source: Simple line. -->
            <div
                class="flex justify-between text-right"
                v-if="parseFloat(cart.items_discount_amount || 0) <= 0 || parseFloat(cart.shipping_discount_amount || 0) <= 0"
            >
                <p class="text-base text-red-600 max-sm:text-sm">
                    @lang('shop::app.checkout.cart.summary.discount-amount')
                </p>

                <p class="text-base font-medium text-red-600 max-sm:text-sm">
                    - @{{ cart.formatted_discount_amount }}
                </p>
            </div>

            <!-- Multi Source: Expandable breakdown. -->
            <div
                class="flex flex-col gap-2 border-y py-2"
                v-else
            >
                <div
                    class="flex cursor-pointer justify-between text-right"
                    @click="cart.show_discount_breakdown = ! cart.show_discount_breakdown"
                >
                    <p class="text-base text-red-600 max-sm:text-sm">
                        @lang('shop::app.checkout.cart.summary.discount-amount')
                    </p>

                    <p class="flex items-center gap-1 text-base font-medium text-red-600 max-sm:text-sm">
                        - @{{ cart.formatted_discount_amount }}

                        <span
                            class="text-xl"
                            :class="{'icon-arrow-up': cart.show_discount_breakdown, 'icon-arrow-down': ! cart.show_discount_breakdown}"
                        ></span>
                    </p>
                </div>

                <div
                    class="flex flex-col gap-1"
                    v-show="cart.show_discount_breakdown"
                >
                    <div class="flex justify-between gap-1 text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            @lang('shop::app.checkout.cart.summary.items-discount')
                        </p>

                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            - @{{ cart.formatted_items_discount_amount }}
                        </p>
                    </div>

                    <div class="flex justify-between gap-1 text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            @lang('shop::app.checkout.cart.summary.shipping-discount')
                        </p>

                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            - @{{ cart.formatted_shipping_discount_amount }}
                        </p>
                    </div>
                </div>
            </div>
        </template>

        {!! view_render_event('bagisto.shop.checkout.cart.summary.discount_amount.after') !!}

        <!-- Apply Coupon -->
        {!! view_render_event('bagisto.shop.checkout.cart.summary.coupon.before') !!}

        @include('shop::checkout.coupon', ['showInlineForm' => true])

        {!! view_render_event('bagisto.shop.checkout.cart.summary.coupon.after') !!}

        <div class="cart-delivery-note flex items-center justify-between gap-4">
            <p>Delivery Charges</p>
            <p class="text-right text-zinc-500">Calculated after area selection</p>
        </div>

        <!-- Cart Grand Total -->
        {!! view_render_event('bagisto.shop.checkout.cart.summary.grand_total.before') !!}

        <div class="flex justify-between text-right">
            <p class="text-lg font-semibold max-md:text-base">
                @lang('shop::app.checkout.cart.summary.grand-total')
            </p>

            <p class="text-lg font-semibold max-md:text-base">
                @{{ formatCartPrice(cart.formatted_grand_total) }}
            </p>
        </div>

        {!! view_render_event('bagisto.shop.checkout.cart.summary.grand_total.after') !!}

        {!! view_render_event('bagisto.shop.checkout.cart.summary.proceed_to_checkout.before') !!}

        <a
            href="{{ route('shop.checkout.onepage.index') }}"
            class="primary-button cart-proceed-button mt-4 place-self-end px-11 py-3 max-md:my-4 max-md:max-w-full max-md:py-3 max-md:text-sm max-sm:w-full max-sm:py-2"
        >
            @lang('shop::app.checkout.cart.summary.proceed-to-checkout')
        </a>

        {!! view_render_event('bagisto.shop.checkout.cart.summary.proceed_to_checkout.after') !!}
    </div>
</div>
