{!! view_render_event('bagisto.shop.layout.footer.before') !!}

@php
    $footerLinkColumns = [
        'Help' => [
            'Privacy Policy'     => 'https://keswastore.com/page/privacy-policy',
            'Payment Policy'     => 'https://keswastore.com/page/payment-policy',
            'Shipping Policy'    => 'https://keswastore.com/page/shipping-policy',
            'Return Policy'      => 'https://keswastore.com/page/return-policy',
            'Terms & Conditions' => 'https://keswastore.com/page/terms-conditions',
            'FAQs'               => 'https://keswastore.com/faqs',
        ],
        'About us' => [
            'Our Store'  => 'https://keswastore.com/our-store',
            'Contact Us' => 'https://keswastore.com/contact-us',
            'About Us'   => 'https://keswastore.com/about-us',
        ],
    ];
@endphp

<footer class="mt-5 w-full px-10 max-1060:px-0 max-1060:pb-[160px] max-sm:mt-10">
    <div class="px-[20px] max-lg:px-[20px]">
        <div style="border-top: 1px solid #d4d4d8;"></div>
    </div>

    <div class="container mx-auto flex justify-between gap-x-6 gap-y-8 px-[20px] py-[60px] max-1060:!mx-0 max-1060:!max-w-none max-1060:flex-col max-md:gap-5 max-lg:px-[20px] max-sm:py-5">

        <div class="flex flex-col gap-2.5">
            <img
                src="{{ bagisto_asset('images/logo.svg', 'shop') }}"
                class="w-40"
                alt="keswa"
            >

            <p class="mt-4 max-w-[288px] text-sm font-medium text-zinc-500 max-md:text-base max-sm:text-xs">
                Address: Benha – Al Ahram District, Amn El-
                Dawla Street, <br>
                in front of Al-Fayoumy Street, Egypt.
            </p>

            <p class="max-w-[288px] text-sm font-medium text-zinc-500 max-md:text-base max-sm:text-xs">
                Email: <span class="text-zinc-700">info@keswa.com</span>
            </p>

            <p class="max-w-[288px] text-sm font-medium text-zinc-500 max-md:text-base max-sm:text-xs">
                Phone / WhatsApp: <a href="https://api.whatsapp.com/send/?phone=201011387451&text&type=phone_number&app_absent=0" class="text-zinc cursor-pointer"
                style="transition: color 0.2s;"
                onmouseover="this.style.color='#AF8234'"
                onmouseout="this.style.color=''">+20 101 138 7451</a>
            </p>

            <nav class="mt-3 flex items-center gap-5" aria-label="Social media links">
                <a href="https://facebook.com/keswa.stores" target="_blank" rel="noopener noreferrer" class="flex size-10 items-center justify-center rounded-full border border-zinc-500 text-black transition-colors hover:bg-grey/15" aria-label="Facebook">
                    <svg class="size-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M13.8 21v-8h2.7l.4-3.1h-3.1V8c0-.9.3-1.5 1.6-1.5H17V3.7c-.3 0-1.3-.1-2.4-.1-2.4 0-4.1 1.5-4.1 4.2v2.1H7.8V13h2.7v8h3.3Z" />
                    </svg>
                </a>

                <a href="https://twitter.com/keswa.stores" target="_blank" rel="noopener noreferrer" class="flex size-10 items-center justify-center rounded-full border border-zinc-500 text-black transition-colors hover:bg-grey/15" aria-label="X">
                    <svg class="size-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M18.9 2H22l-6.8 7.8L23.2 22h-6.3l-4.9-7.1L5.8 22H2.7l7.2-8.3L2.2 2h6.4l4.4 6.5L18.9 2Zm-1.1 18h1.7L7.7 3.9H5.9L17.8 20Z" />
                    </svg>
                </a>

                <a href="https://instagram.com/keswa.stores" target="_blank" rel="noopener noreferrer" class="flex size-10 items-center justify-center rounded-full border border-zinc-500 text-black transition-colors hover:bg-grey/15" aria-label="Instagram">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <rect x="3" y="3" width="18" height="18" rx="5" />
                        <circle cx="12" cy="12" r="4" />
                        <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none" />
                    </svg>
                </a>

                <a href="https://tiktok.com/@keswa_wear" target="_blank" rel="noopener noreferrer" class="flex size-10 items-center justify-center rounded-full border border-zinc-500 text-black transition-colors hover:bg-grey/15" aria-label="TikTok">
                    <svg class="size-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M16.7 3c.4 2.3 1.7 3.7 4 3.9v3.1c-1.5 0-2.9-.5-4-1.3v6.5c0 4.1-4.5 6.5-7.9 4.3-2.2-1.5-2.8-4.5-1.4-6.7 1.3-2.1 4-2.8 6.1-1.7v3.2c-.8-.5-1.8-.5-2.5.1-.9.7-1 2.1-.3 3 .8 1 2.4.9 3.1-.1.3-.4.4-.8.4-1.3V3h2.5Z" />
                    </svg>
                </a>
            </nav>
        </div>

        <!-- For Desktop View -->
        <div
            class="flex flex-wrap items-start gap-24 max-1180:gap-5 max-1060:hidden"
            v-pre
         >
            @foreach ($footerLinkColumns as $heading => $links)
                <ul class="grid gap-5 text-sm text-grey">
                    <li class="font-semibold text-black">{{ $heading }}</li>

                    @foreach ($links as $title => $url)
                        <li class="hover:text-black"><a href="{{ $url }}">{{ $title }}</a></li>
                    @endforeach
                </ul>
            @endforeach
        </div>

        <!-- For Mobile view -->
        <div class="hidden w-full max-1060:block">
            @foreach ($footerLinkColumns as $heading => $links)
                <x-shop::accordion :is-active="false" class="!border-b-0">
                    <x-slot:header class="!px-0 !py-4 text-base font-semibold text-black">
                        {{ $heading }}
                    </x-slot>

                    <x-slot:content class="!bg-transparent !p-0 !pb-4">
                        <ul class="grid gap-3 text-sm text-grey">
                            @foreach ($links as $title => $url)
                                <li>
                                    <a class="transition-colors hover:text-black focus:text-black" href="{{ $url }}">
                                        {{ $title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </x-slot:content>
                </x-shop::accordion>
            @endforeach

            @if (core()->getConfigData('customer.settings.newsletter.subscription'))
                <x-shop::accordion :is-active="false"
                 class="!border-b-0">
                    <x-slot:header class="!px-0 !py-4 text-base font-semibold text-black">
                        Sign Up for Email
                    </x-slot>

                    <x-slot:content class="!bg-transparent !p-0 !pb-4">
                        <p class="text-sm text-grey">
                            Sign up to get first dibs on new arrivals, sales, exclusive content, events and more!
                        </p>

                        <x-shop::form
                            :action="route('shop.subscription.store')"
                            class="mt-3 rounded"
                            toolname="subscribe_to_newsletter"
                            tooldescription="{{ trans('shop::app.components.layouts.webmcp.subscribe-newsletter') }}"
                            toolautosubmit
                        >
                            <div class="relative w-full">
                                <x-shop::form.control-group.control
                                    type="email"
                                    class="block w-full rounded-sm border px-4 py-3 text-sm"
                                    name="email"
                                    rules="required|email"
                                    label="Email"
                                    :aria-label="trans('shop::app.components.layouts.footer.email')"
                                    placeholder="email@example.com"
                                    toolparamdescription="{{ trans('shop::app.components.layouts.webmcp.subscribe-newsletter-email') }}"
                                />

                                <x-shop::form.control-group.error control-name="email" />

                                <button
                                    type="submit"
                                    class="absolute top-1.5 flex w-max items-center rounded-sm bg-black px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 ltr:right-1.5 rtl:left-1.5"
                                >
                                    @lang('shop::app.components.layouts.footer.subscribe')
                                </button>
                            </div>
                        </x-shop::form>
                    </x-slot>
                </x-shop::accordion>
            @endif
        </div>

        {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.before') !!}

        <!-- News Letter subscription -->
        @if (core()->getConfigData('customer.settings.newsletter.subscription'))
            <div class="flex flex-col gap-3 max-1060:hidden max-md:mt-5 max-sm:mt-0">
                <p
                    class="text-grey text-sm font-semibold !text-black max-md:text-2xl max-sm:text-lg"
                 >
                    Sign Up for Email
                </p>

                <p class="text-sm text-grey">
                    Sign up to get first dibs on new arrivals, sales, exclusive <br>
                    content, events and more!
                </p>

                <div>
                    <x-shop::form
                        :action="route('shop.subscription.store')"
                        class="mt-2.5 rounded max-sm:mt-0"
                        toolname="subscribe_to_newsletter"
                        tooldescription="{{ trans('shop::app.components.layouts.webmcp.subscribe-newsletter') }}"
                        toolautosubmit
                    >
                        <div class="relative w-full">
                            <x-shop::form.control-group.control
                                type="email"
                                class="block w-[420px] max-w-full rounded-sm border px-5 py-4 text-base max-1060:w-full max-md:p-3.5 max-sm:mb-0 max-sm:rounded-lg max-sm:border-2 max-sm:p-2 max-sm:text-sm"
                                name="email"
                                rules="required|email"
                                label="Email"
                                :aria-label="trans('shop::app.components.layouts.footer.email')"
                                placeholder="email@example.com"
                                toolparamdescription="{{ trans('shop::app.components.layouts.webmcp.subscribe-newsletter-email') }}"
                            />
    
                            <x-shop::form.control-group.error control-name="email" />
    
                            <button
                                type="submit"
                                class="absolute top-1.5 flex w-max items-center rounded-sm bg-black text-white px-4 py-2.5 font-medium hover:bg-zinc-800 ltr:right-2 rtl:left-2 max-md:top-1 max-md:px-4 max-md:text-xs max-sm:mt-0 max-sm:rounded-sm max-sm:px-4 max-sm:py-2"
                            >
                                @lang('shop::app.components.layouts.footer.subscribe')
                            </button>
                        </div>
                    </x-shop::form>
                </div>
            </div>
        @endif

        {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.after') !!}
    </div>

    <div class="px-[20px] max-lg:px-[55px]">
        <div style="border-top: 1px solid #d4d4d8;"></div>
    </div>

    <div class="flex justify-between px-[60px] py-3.5 max-1060:flex-col max-1060:items-center max-lg:px-[55px]">
        {!! view_render_event('bagisto.shop.layout.footer.footer_text.before') !!}

        <p class="text-sm text-zinc-600 max-1060:text-center">
            © Copyright 2010 - 2026, Keswa Store. All rights reserved.
        </p>

        <div class="flex items-center gap-3 max-1060:mt-3">
            <img
                src="{{ bagisto_asset('images/paymob.png', 'shop') }}"
                class="w-10 object-contain"
                alt="Paymob"
            >

            <img
                src="{{ bagisto_asset('images/instapay.png', 'shop') }}"
                class="w-10 object-contain"
                alt="InstaPay"
            >

            <svg class="w-10" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" id="mastercard"><path fill="#FFB600" d="M63.5 32c0 10.4-8.4 18.9-18.9 18.9-10.4 0-18.9-8.5-18.9-18.9 0-10.4 8.4-18.9 18.8-18.9 10.6 0 19 8.5 19 18.9z"></path><path fill="#F7981D" d="M44.6 13.1c10.4 0 18.9 8.5 18.9 18.9 0 10.4-8.4 18.9-18.9 18.9-10.4 0-18.9-8.5-18.9-18.9"></path><path fill="#FF8500" d="M44.6 13.1c10.4 0 18.9 8.5 18.9 18.9 0 10.4-8.4 18.9-18.9 18.9"></path><path fill="#FF5050" d="M19.2 13.1C8.9 13.2.5 21.6.5 32c0 10.4 8.4 18.9 18.9 18.9 4.9 0 9.3-1.9 12.7-4.9.7-.6 1.3-1.3 1.9-2h-3.9c-.5-.6-1-1.3-1.4-1.9h6.7c.4-.6.8-1.3 1.1-2h-8.9c-.3-.6-.6-1.3-.8-2h10.4c.6-1.9 1-3.9 1-6 0-1.4-.2-2.7-.4-4H26.2c.1-.7.3-1.3.5-2h10.4c-.2-.7-.5-1.4-.8-2h-8.8c.3-.7.7-1.3 1.1-2h6.7c-.4-.7-.9-1.4-1.5-2h-3.7c.6-.7 1.2-1.3 1.9-1.9-3.3-3.1-7.8-4.9-12.7-4.9 0-.2 0-.2-.1-.2z"></path><path fill="#E52836" d="M.5 32c0 10.4 8.4 18.9 18.9 18.9 4.9 0 9.3-1.9 12.7-4.9.7-.6 1.3-1.3 1.9-2h-3.9c-.5-.6-1-1.3-1.4-1.9h6.7c.4-.6.8-1.3 1.1-2h-8.9c-.3-.6-.6-1.3-.8-2h10.4c.6-1.9 1-3.9 1-6 0-1.4-.2-2.7-.4-4H26.2c.1-.7.3-1.3.5-2h10.4c-.2-.7-.5-1.4-.8-2h-8.8c.3-.7.7-1.3 1.1-2h6.7c-.4-.7-.9-1.4-1.5-2h-3.7c.6-.7 1.2-1.3 1.9-1.9-3.3-3.1-7.8-4.9-12.7-4.9h-.1"></path><path fill="#CB2026" d="M19.4 50.9c4.9 0 9.3-1.9 12.7-4.9.7-.6 1.3-1.3 1.9-2h-3.9c-.5-.6-1-1.3-1.4-1.9h6.7c.4-.6.8-1.3 1.1-2h-8.9c-.3-.6-.6-1.3-.8-2h10.4c.6-1.9 1-3.9 1-6 0-1.4-.2-2.7-.4-4H26.2c.1-.7.3-1.3.5-2h10.4c-.2-.7-.5-1.4-.8-2h-8.8c.3-.7.7-1.3 1.1-2h6.7c-.4-.7-.9-1.4-1.5-2h-3.7c.6-.7 1.2-1.3 1.9-1.9-3.3-3.1-7.8-4.9-12.7-4.9h-.1"></path><path fill="#FFF" d="m26.1 36.8.3-1.7c-.1 0-.3.1-.5.1-.7 0-.8-.4-.7-.6l.6-3.5h1.1l.3-1.9h-1l.2-1.2h-2s-1.2 6.6-1.2 7.4c0 1.2.7 1.7 1.6 1.7.6 0 1.1-.2 1.3-.3zM26.8 33.6c0 2.8 1.9 3.5 3.5 3.5 1.5 0 2.1-.3 2.1-.3l.4-1.9s-1.1.5-2.1.5c-2.2 0-1.8-1.6-1.8-1.6H33s.3-1.3.3-1.8c0-1.3-.7-2.9-2.9-2.9-2.1-.2-3.6 2-3.6 4.5zm3.5-2.9c1.1 0 .9 1.3.9 1.4H29c0-.1.2-1.4 1.3-1.4zM43 36.8l.4-2.2s-1 .5-1.7.5c-1.4 0-2-1.1-2-2.3 0-2.4 1.2-3.7 2.6-3.7 1 0 1.8.6 1.8.6l.3-2.1s-1.2-.5-2.3-.5c-2.3 0-4.6 2-4.6 5.8 0 2.5 1.2 4.2 3.6 4.2.8 0 1.9-.3 1.9-.3zM15.1 28.9c-1.4 0-2.4.4-2.4.4l-.3 1.7s.9-.4 2.2-.4c.7 0 1.3.1 1.3.7 0 .4-.1.5-.1.5h-.9c-1.7 0-3.6.7-3.6 3 0 1.8 1.2 2.2 1.9 2.2 1.4 0 2-.9 2.1-.9l-.1.8H17l.8-5.5c0-2.4-2-2.5-2.7-2.5zm.4 4.5c0 .3-.2 1.9-1.4 1.9-.6 0-.8-.5-.8-.8 0-.5.3-1.2 1.8-1.2.3.1.4.1.4.1zM19.7 37c.5 0 3 .1 3-2.6 0-2.5-2.4-2-2.4-3 0-.5.4-.7 1.1-.7.3 0 1.4.1 1.4.1l.3-1.8s-.7-.2-1.9-.2c-1.5 0-3 .6-3 2.6 0 2.3 2.5 2.1 2.5 3 0 .6-.7.7-1.2.7-.9 0-1.8-.3-1.8-.3l-.3 1.8c.1.2.6.4 2.3.4zM59.6 27.3l-.4 2.7s-.8-1-1.9-1c-1.8 0-3.4 2.2-3.4 4.8 0 1.6.8 3.3 2.5 3.3 1.2 0 1.9-.8 1.9-.8l-.1.7h2l1.5-9.6-2.1-.1zm-.9 5.3c0 1.1-.5 2.5-1.6 2.5-.7 0-1.1-.6-1.1-1.6 0-1.6.7-2.6 1.6-2.6.7 0 1.1.5 1.1 1.7zM4.2 36.9l1.2-7.2.2 7.2H7l2.6-7.2-1.1 7.2h2.1l1.6-9.6H8.9l-2 5.9-.1-5.9H3.9l-1.6 9.6h1.9zM35.2 36.9c.6-3.3.7-6 2.1-5.5.2-1.3.5-1.8.7-2.3h-.4c-.9 0-1.6 1.2-1.6 1.2l.2-1.1h-1.9L33 37h2.2zM47.6 28.9c-1.4 0-2.4.4-2.4.4l-.3 1.7s.9-.4 2.2-.4c.7 0 1.3.1 1.3.7 0 .4-.1.5-.1.5h-.9c-1.7 0-3.6.7-3.6 3 0 1.8 1.2 2.2 1.9 2.2 1.4 0 2-.9 2.1-.9l-.1.8h1.8l.8-5.5c.1-2.4-2-2.5-2.7-2.5zm.5 4.5c0 .3-.2 1.9-1.4 1.9-.6 0-.8-.5-.8-.8 0-.5.3-1.2 1.8-1.2.3.1.3.1.4.1zM52 36.9c.6-3.3.7-6 2.1-5.5.2-1.3.5-1.8.7-2.3h-.4c-.9 0-1.6 1.2-1.6 1.2l.2-1.1h-1.9L49.8 37H52z"></path><g fill="#DCE5E5"><path d="M23 35.4c0 1.2.7 1.7 1.6 1.7.7 0 1.3-.2 1.5-.3l.3-1.7c-.1 0-.3.1-.5.1-.7 0-.8-.4-.7-.6l.6-3.5h1.1l.3-1.9h-1l.2-1.2M27.8 33.6c0 2.8.9 3.5 2.5 3.5 1.5 0 2.1-.3 2.1-.3l.4-1.9s-1.1.5-2.1.5c-2.2 0-1.8-1.6-1.8-1.6H33s.3-1.3.3-1.8c0-1.3-.7-2.9-2.9-2.9-2.1-.2-2.6 2-2.6 4.5zm2.5-2.9c1.1 0 1.3 1.3 1.3 1.4H29c0-.1.2-1.4 1.3-1.4zM43 36.8l.4-2.2s-1 .5-1.7.5c-1.4 0-2-1.1-2-2.3 0-2.4 1.2-3.7 2.6-3.7 1 0 1.8.6 1.8.6l.3-2.1s-1.2-.5-2.3-.5c-2.3 0-3.6 2-3.6 5.8 0 2.5.2 4.2 2.6 4.2.8 0 1.9-.3 1.9-.3zM12.4 31.1s.9-.4 2.2-.4c.7 0 1.3.1 1.3.7 0 .4-.1.5-.1.5h-.9c-1.7 0-3.6.7-3.6 3 0 1.8 1.2 2.2 1.9 2.2 1.4 0 2-.9 2.1-.9l-.1.8H17l.8-5.5c0-2.3-2-2.4-2.8-2.4m1.5 4.3c0 .3-1.2 1.9-2.4 1.9-.6 0-.8-.5-.8-.8 0-.5.3-1.2 1.8-1.2.3.1 1.4.1 1.4.1zM17.5 36.8s.6.2 2.3.2c.5 0 3 .1 3-2.6 0-2.5-2.4-2-2.4-3 0-.5.4-.7 1.1-.7.3 0 1.4.1 1.4.1l.3-1.8s-.7-.2-1.9-.2c-1.5 0-2 .6-2 2.6 0 2.3 1.5 2.1 1.5 3 0 .6-.7.7-1.2.7M59.2 30s-.8-1-1.9-1c-1.8 0-2.4 2.2-2.4 4.8 0 1.6-.2 3.3 1.5 3.3 1.2 0 1.9-.8 1.9-.8l-.1.7h2l1.5-9.6m-2.6 5.2c0 1.1-.9 2.5-2 2.5-.7 0-1.1-.6-1.1-1.6 0-1.6.7-2.6 1.6-2.6.7 0 1.5.5 1.5 1.7zM4.2 36.9l1.2-7.2.2 7.2H7l2.6-7.2-1.1 7.2h2.1l1.6-9.6H9.7l-2.8 5.9-.1-5.9H5.7l-3.4 9.6h1.9zM33.1 36.9h2.1c.6-3.3.7-6 2.1-5.5.2-1.3.5-1.8.7-2.3h-.4c-.9 0-1.6 1.2-1.6 1.2l.2-1.1M44.9 31.1s.9-.4 2.2-.4c.7 0 1.3.1 1.3.7 0 .4-.1.5-.1.5h-.9c-1.7 0-3.6.7-3.6 3 0 1.8 1.2 2.2 1.9 2.2 1.4 0 2-.9 2.1-.9l-.1.8h1.8l.8-5.5c0-2.3-2-2.4-2.8-2.4m1.5 4.3c0 .3-1.2 1.9-2.4 1.9-.6 0-.8-.5-.8-.8 0-.5.3-1.2 1.8-1.2.3.1 1.4.1 1.4.1zM49.9 36.9H52c.6-3.3.7-6 2.1-5.5.2-1.3.5-1.8.7-2.3h-.4c-.9 0-1.6 1.2-1.6 1.2l.2-1.1"></path></g>
            </svg>
        </div>

        {!! view_render_event('bagisto.shop.layout.footer.footer_text.after') !!}
    </div>
</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}
