<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('shop::app.customers.login-form.page-title')"/>

    <meta name="keywords" content="@lang('shop::app.customers.login-form.page-title')"/>
@endPush

@push('styles')
    <style>
        .keswa-login {
            width: 100%;
            max-width: 550px;
            margin: 0 auto;
            padding: 80px 28px 32px;
            color: #000;
        }

        .keswa-login__logo {
            position: relative;
            width: 184px;
            height: 58px;
            margin: 0 auto 30px;
            color: #242021;
            font-family: Arial, sans-serif;
        }

        .keswa-login__logo-est {
            position: absolute;
            top: 0;
            right: 14px;
            font-size: 11px;
            font-weight: 700;
        }

        .keswa-login__logo-name {
            position: absolute;
            bottom: 0;
            left: 0;
            font-size: 53px;
            font-weight: 800;
            letter-spacing: -4px;
            line-height: 0.85;
        }

        .keswa-login__logo-register {
            position: absolute;
            right: 0;
            bottom: 1px;
            font-size: 10px;
            font-weight: 700;
        }

        .keswa-login__card {
            width: 100%;
            border: 0;
            border-radius: 0;
            padding: 0;
        }

        .keswa-login__title {
            color: #000;
            font-family: Arial, sans-serif;
            font-size: 30px;
            font-weight: 400;
            line-height: 1.2;
        }

        .keswa-login__subtitle {
            margin-top: 3px;
            color: #64748b;
            font-family: Arial, sans-serif;
            font-size: 18px;
            line-height: 1.35;
        }

        .keswa-login .control-input {
            height: 59px;
            border: 1px solid #dce1e8;
            border-radius: 6px;
            background: #fff !important;
            padding: 0 23px;
            color: #1f1f1f;
            font-family: Arial, sans-serif;
            font-size: 23px;
        }

        .keswa-login .control-input::placeholder {
            color: #94a3b8;
        }

        .keswa-login .control-input:-webkit-autofill,
        .keswa-login .control-input:-webkit-autofill:hover,
        .keswa-login .control-input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #fff inset;
            -webkit-text-fill-color: #1f1f1f;
        }

        .keswa-login .primary-button {
            height: 53px;
            border: 0;
            border-radius: 2px;
            background: #1f1f1f;
            color: #fff;
            font-family: Arial, sans-serif;
            font-size: 22px;
            font-weight: 600;
        }

        .keswa-login .primary-button:hover {
            background: #000;
        }

        @media (min-width: 768px) {
            .keswa-login {
                max-width: 1280px;
                padding: 32px 28px 32px;
            }

            .keswa-login__logo {
                width: 184px;
                margin: 0 auto 28px;
            }

            .keswa-login__card {
                max-width: 1226px;
                min-height: 692px;
                margin: 0 auto;
                border: 1px solid #dce1e8;
                padding: 89px 127px 72px;
            }

            .keswa-login__title {
                font-size: 42px;
                line-height: 1;
            }

            .keswa-login__subtitle {
                margin-top: 20px;
                font-size: 23px;
                line-height: 1.25;
            }

            .keswa-login .mt-12 {
                margin-top: 76px;
            }

            .keswa-login .mb-4 {
                margin-bottom: 31px;
            }

            .keswa-login .mb-4 > label {
                font-size: 20px;
                line-height: 1.25;
            }

            .keswa-login .control-input {
                height: 68px;
                padding: 0 26px;
                font-size: 21px;
            }

            .keswa-login .control-input::placeholder {
                font-size: 21px;
            }

            .keswa-login .primary-button {
                width: 527px;
                max-width: 100%;
                height: 64px;
                font-size: 21px;
            }

            .keswa-login .text-base,
            .keswa-login .text-\[18px\] {
                font-size: 19px;
            }

            .keswa-login > p {
                margin-top: 47px;
            }
        }

        @media (max-width: 767px) {
            .keswa-login {
                max-width: 100%;
                padding: 32px 20px 24px;
            }

            .keswa-login__logo {
                width: 152px;
                height: 48px;
                margin-bottom: 24px;
            }

            .keswa-login__logo-name {
                font-size: 44px;
            }

            .keswa-login__title {
                font-size: 24px;
            }

            .keswa-login__subtitle {
                margin-top: 0;
                font-size: 15px;
            }

            .keswa-login .mt-12 {
                margin-top: 16px;
            }

            .keswa-login .mb-4 {
                margin-bottom: 20px;
            }

            .keswa-login .control-input {
                height: 54px;
                padding: 0 15px;
                font-size: 16px;
            }

            .keswa-login .control-input::placeholder {
                font-size: 16px;
            }

            .keswa-login .primary-button {
                width: 100% !important;
                max-width: none;
                height: 46px;
                font-size: 18px;
            }

            .keswa-login__card > p {
                margin-top: 24px;
                font-size: 14px;
                text-align: center;
            }

            .keswa-login > p {
                margin-top: 32px;
                font-size: 12px;
            }
        }
    </style>
@endpush

<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.login-form.page-title')
    </x-slot>

    <div class="keswa-login">
        {!! view_render_event('bagisto.shop.customers.login.logo.before') !!}

        <!-- Company Logo -->
        <div>
            <a
                href="{{ route('shop.home.index') }}"
                aria-label="@lang('shop::app.customers.login-form.bagisto')"
            >
                <div
                    class="keswa-login__logo"
                    role="img"
                    aria-label="Keswa"
                >
                    <span class="keswa-login__logo-est">EST. 2012</span>
                    <span class="keswa-login__logo-name">Keswa</span>
                    <span class="keswa-login__logo-register">®</span>
                </div>
            </a>
        </div>

        {!! view_render_event('bagisto.shop.customers.login.logo.after') !!}

        <!-- Form Container -->
        <div class="keswa-login__card">
            <h1 class="keswa-login__title">
                @lang('shop::app.customers.login-form.page-title')
            </h1>

            <p class="keswa-login__subtitle">
                @lang('shop::app.customers.login-form.form-login-text')
            </p>

            {!! view_render_event('bagisto.shop.customers.login.before') !!}

            <div class="mt-12">
                <x-shop::form :action="route('shop.customer.session.create')">

                    {!! view_render_event('bagisto.shop.customers.login_form_controls.before') !!}

                    <!-- Email -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.customers.login-form.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            class="control-input"
                            name="email"
                            rules="required|email"
                            value=""
                            :label="trans('shop::app.customers.login-form.email')"
                            placeholder="email@example.com"
                            :aria-label="trans('shop::app.customers.login-form.email')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="email" />
                    </x-shop::form.control-group>

                    <!-- Password -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.customers.login-form.password')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="password"
                            class="control-input"
                            id="password"
                            name="password"
                            rules="required|min:6"
                            value=""
                            :label="trans('shop::app.customers.login-form.password')"
                            :aria-label="trans('shop::app.customers.login-form.password')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="password" />
                    </x-shop::form.control-group>

                    <div class="flex justify-between">
                        <div class="flex select-none items-center gap-1.5">
                            <input
                                type="checkbox"
                                id="show-password"
                                class="peer hidden"
                                onchange="switchVisibility()"
                            />

                            <label
                                class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl max-sm:text-xl"
                                style="color: #1f1f1f;"
                                for="show-password"
                            ></label>

                            <label
                                class="cursor-pointer select-none text-base max-sm:text-sm ltr:pl-0 rtl:pr-0"
                                style="color: #64748b;"
                                for="show-password"
                            >
                                @lang('shop::app.customers.login-form.show-password')
                            </label>
                        </div>

                        <div class="block">
                            <a
                                href="{{ route('shop.customers.forgot_password.create') }}"
                                class="cursor-pointer text-base text-black max-sm:text-sm"
                            >
                                <span>
                                    @lang('shop::app.customers.login-form.forgot-pass')
                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- Captcha -->
                    @if (core()->getConfigData('customer.captcha.credentials.status'))
                        <x-shop::form.control-group class="mt-5">
                            {!! \Webkul\Customer\Facades\Captcha::render() !!}

                            <x-shop::form.control-group.error control-name="recaptcha_token" />
                        </x-shop::form.control-group>
                    @endif

                    <!-- Submit Button -->
                    <div class="mt-12 flex flex-wrap items-center">
                        <button
                            class="primary-button block w-full text-center"
                            type="submit"
                        >
                            @lang('shop::app.customers.login-form.button-title')
                        </button>

                    </div>
                </x-shop::form>
            </div>

            {!! view_render_event('bagisto.shop.customers.login.after') !!}

            @if (
                request()->cookie('enable-resend')
                && request()->cookie('email-for-resend')
            )
                <p class="mt-5 font-medium text-zinc-500 max-sm:text-center max-sm:text-sm">
                    <a
                        class="text-navyBlue"
                        href="{{ route('shop.customers.resend.verification_email', urlencode(request()->cookie('email-for-resend'))) }}"
                    >
                        @lang('shop::app.customers.login-form.resend-verification')
                    </a>
                </p>
            @endif

            <p class="mt-8 text-left text-[18px] text-slate-500">
                @lang('shop::app.customers.login-form.new-customer')

                <a
                    class="text-black"
                    href="{{ route('shop.customers.register.index') }}"
                >
                    @lang('shop::app.customers.login-form.create-your-account')
                </a>
            </p>
        </div>

        <p class="mt-12 text-center text-sm text-slate-500">
            @lang('shop::app.customers.login-form.footer', ['current_year'=> date('Y') ])
        </p>
    </div>

    @push('scripts')
        {!! \Webkul\Customer\Facades\Captcha::renderJS() !!}

        <script>
            function switchVisibility() {
                let passwordField = document.getElementById("password");

                passwordField.type = passwordField.type === "password"
                    ? "text"
                    : "password";
            }
        </script>
    @endpush
</x-shop::layouts>
