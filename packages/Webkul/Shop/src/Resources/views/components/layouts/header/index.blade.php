{!! view_render_event('bagisto.shop.layout.header.before') !!}

<div class="max-lg:hidden">
    <x-shop::layouts.header.desktop.top />
</div>

<header class="shadow-gray sticky top-0 z-10 bg-white shadow-sm max-lg:shadow-none">
    <x-shop::layouts.header.desktop />

    <x-shop::layouts.header.mobile />
</header>

{!! view_render_event('bagisto.shop.layout.header.after') !!}
