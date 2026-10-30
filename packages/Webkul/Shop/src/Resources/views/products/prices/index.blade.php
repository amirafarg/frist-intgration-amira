@if ($prices['final']['price'] < $prices['regular']['price'])
    <p
        class="font-semibold max-sm:leading-4"
    >
        {{ preg_replace('/([.,])0+(?=\D|$)/u', '', $prices['final']['formatted_price']) }}
    </p>

    <p
        class="final-price font-medium text-zinc-500 line-through max-sm:leading-4"
        aria-label="{{ $prices['regular']['formatted_price'] }}"
    >
        {{ preg_replace('/([.,])0+(?=\D|$)/u', '', $prices['regular']['formatted_price']) }}
    </p>
@else
    <p class="final-price font-semibold max-sm:leading-4">
        {{ preg_replace('/([.,])0+(?=\D|$)/u', '', $prices['regular']['formatted_price']) }}
    </p>
@endif
