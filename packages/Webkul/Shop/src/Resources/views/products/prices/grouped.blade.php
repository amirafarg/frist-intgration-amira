<p class="price-label text-sm text-zinc-500 max-sm:leading-4">
    @lang('shop::app.products.prices.grouped.starting-at')
</p>

<p class="font-semibold max-sm:leading-4">
    {{ preg_replace('/([.,])0+(?=\D|$)/u', '', $prices['final']['formatted_price']) }}
</p>
