<h1 class="checkout-summary-title text-2xl font-medium max-md:py-4 max-md:text-base">
    @lang('shop::app.checkout.onepage.summary.cart-summary')
</h1>

<div class="checkout-summary-items mt-10 grid border-b border-zinc-200 max-md:mt-3 max-sm:mt-0">
    <div
        class="checkout-summary-item flex gap-x-4 pb-5 max-md:gap-x-3 max-md:pb-4"
        v-for="item in cart.items"
        :key="item.id"
    >
        {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_image.before') !!}

        <img
            class="h-[90px] max-h-[90px] w-[90px] max-w-[90px] rounded-xl max-md:h-20 max-md:max-h-20 max-md:max-w-20 max-md:rounded-lg"
            :src="item.base_image.large_image_url || item.base_image.medium_image_url || item.base_image.small_image_url"
            :alt="item.name"
            width="110"
            height="110"
        />

        {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_image.after') !!}

        <div>
            {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_name.before') !!}

            <p class="checkout-summary-product-name text-base text-navyBlue max-md:text-sm max-md:font-medium">
                @{{ item.name }}
            </p>

            {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_name.after') !!}

            <p class="checkout-summary-product-price mt-2.5 flex flex-col text-lg font-medium max-md:mt-1 max-md:text-base max-md:font-normal max-sm:text-sm">
                <template v-if="displayTax.prices == 'including_tax'">
                    @lang('shop::app.checkout.onepage.summary.price_and_qty', ['price' => '@{{ formatCheckoutPrice(item.formatted_price_incl_tax) }}', 'qty' => '@{{ item.quantity }}'])
                </template>

                <template v-else-if="displayTax.prices == 'both'">
                    @lang('shop::app.checkout.onepage.summary.price_and_qty', ['price' => '@{{ formatCheckoutPrice(item.formatted_price_incl_tax) }}', 'qty' => '@{{ item.quantity }}'])

                    <span class="text-xs font-normal">
                        @lang('shop::app.checkout.onepage.summary.excl-tax')
                        <span class="font-medium">@{{ formatCheckoutPrice(item.formatted_total) }}</span>
                    </span>
                </template>

                <template v-else>
                    @lang('shop::app.checkout.onepage.summary.price_and_qty', ['price' => '@{{ formatCheckoutPrice(item.formatted_price) }}', 'qty' => '@{{ item.quantity }}'])
                </template>
            </p>
        </div>
    </div>
</div>

<div class="checkout-summary-totals mb-8 mt-6 grid gap-4 max-md:mb-0 max-sm:mt-4 max-sm:gap-2.5">
    {!! view_render_event('bagisto.shop.checkout.onepage.summary.sub_total.before') !!}

    <div class="checkout-summary-row flex justify-between text-right">
        <p class="text-base">
            @lang('shop::app.checkout.onepage.summary.sub-total')
        </p>

        <p class="font-semibold">
            @{{ formatCheckoutPrice(displayTax.subtotal == 'including_tax' || displayTax.subtotal == 'both' ? cart.formatted_sub_total_incl_tax : cart.formatted_sub_total) }}
        </p>
    </div>

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.sub_total.after') !!}

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.coupon.before') !!}

    <div class="checkout-summary-coupon">
        @include('shop::checkout.coupon', ['showInlineForm' => true])
    </div>

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.coupon.after') !!}

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.delivery_charges.before') !!}

    <div class="checkout-delivery-row flex items-start justify-between gap-4">
        <p>@lang('shop::app.checkout.onepage.summary.delivery-charges')</p>

        <p class="checkout-delivery-value text-right">
            <template v-if="Number(cart.shipping_amount || 0) > 0">
                @{{ formatCheckoutPrice(displayTax.shipping == 'including_tax' || displayTax.shipping == 'both' ? cart.formatted_shipping_amount_incl_tax : cart.formatted_shipping_amount) }}
            </template>
            <template v-else>
                Calculated after area selection
            </template>
        </p>
    </div>

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.delivery_charges.after') !!}

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.grand_total.before') !!}

    <div class="checkout-grand-total flex justify-between text-right">
        <p>@lang('shop::app.checkout.onepage.summary.grand-total')</p>
        <p>@{{ formatCheckoutPrice(cart.formatted_grand_total) }}</p>
    </div>

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.grand_total.after') !!}
</div>
