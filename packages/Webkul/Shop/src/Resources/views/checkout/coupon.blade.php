<!-- Coupon Vue Component -->
<v-coupon 
    :cart="cart"
    :show-inline-form="@json($showInlineForm ?? false)"
    @coupon-applied="getCart"
    @coupon-removed="getCart"
>
</v-coupon>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-coupon-template"
    >
        <div :class="showInlineForm ? 'cart-coupon-inline' : 'flex justify-between text-right'">
            <p class="text-base max-md:font-normal max-sm:text-sm" v-if="! showInlineForm">
                @{{ cart.coupon_code ? "@lang('shop::app.checkout.coupon.applied')" : "@lang('shop::app.checkout.coupon.discount')" }}
            </p>

            {!! view_render_event('bagisto.shop.checkout.cart.coupon.before') !!}

            <div class="cart-coupon-form" v-if="showInlineForm">
                <template v-if="! cart.coupon_code">
                    <input
                        type="text"
                        name="code"
                        v-model="couponCode"
                        class="min-w-0 flex-1 border border-zinc-300 px-5 py-3 outline-none max-sm:px-3"
                        placeholder="Apply coupon code"
                        aria-label="@lang('shop::app.checkout.coupon.enter-your-code')"
                    >

                    <button
                        type="button"
                        class="primary-button cart-coupon-apply"
                        :disabled="isStoring || ! couponCode.trim()"
                        @click="applyCoupon({ code: couponCode }, { resetForm: () => couponCode = '' })"
                    >
                        @lang('shop::app.checkout.coupon.button-title')
                    </button>
                </template>

                <span
                    v-else
                    class="inline-flex items-center gap-2"
                >
                    <span class="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-sm font-semibold text-emerald-700">
                        @{{ cart.coupon_code }}
                    </span>

                    <span
                        class="icon-cancel cursor-pointer text-xl text-gray-400 hover:text-red-500"
                        title="@lang('shop::app.checkout.coupon.remove')"
                        @click="destroyCoupon"
                    ></span>
                </span>
            </div>

            <p class="text-base font-medium max-sm:text-sm" v-else>
                <!-- Apply Coupon Form -->
                <x-shop::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <!-- Apply coupon form -->
                    <form @submit="handleSubmit($event, applyCoupon)">
                        {!! view_render_event('bagisto.shop.checkout.cart.coupon.coupon_form_controls.before') !!}

                        <!-- Apply coupon modal -->
                        <x-shop::modal ref="couponModel">
                            <!-- Modal Toggler -->
                            <x-slot:toggle>
                                <span 
                                    class="cursor-pointer text-base text-blue-700 max-sm:text-sm"
                                    role="button"
                                    tabindex="0"
                                    v-if="! cart.coupon_code"
                                >
                                    @lang('shop::app.checkout.coupon.apply')
                                </span>
                            </x-slot>

                            <!-- Modal Header -->
                            <x-slot:header class="max-md:p-5">
                                <h2 class="text-2xl font-medium max-md:text-base">
                                    @lang('shop::app.checkout.coupon.apply')
                                </h2>
                            </x-slot>

                            <!-- Modal Content -->
                            <x-slot:content class="!px-4">
                                <x-shop::form.control-group class="!mb-0">
                                    <x-shop::form.control-group.control
                                        type="text"
                                        class="px-6 py-4 max-md:!mb-0 max-md:!p-3 max-sm:!p-2"
                                        name="code"
                                        rules="required"
                                        :placeholder="trans('shop::app.checkout.coupon.enter-your-code')"
                                    />

                                    <x-shop::form.control-group.error
                                        class="flex"
                                        control-name="code"
                                    />
                                </x-shop::form.control-group>
                            </x-slot>

                            <!-- Modal Footer -->
                            <x-slot:footer>
                                <!-- Coupon Form Action Container -->
                                <div class="flex flex-wrap items-center gap-4 max-md:justify-between">
                                    <div class="flex items-center gap-4 max-md:block">
                                        <p class="text-sm font-medium text-zinc-500 max-md:text-left max-md:text-xs">
                                            @lang('shop::app.checkout.coupon.subtotal')
                                        </p>

                                        <p class="text-3xl font-semibold max-md:text-lg">
                                            @{{ cart.formatted_sub_total }}
                                        </p>
                                    </div>

                                    <x-shop::button
                                        class="primary-button max-w-none flex-auto rounded-2xl px-11 py-3 max-md:max-w-[153px] max-md:rounded-lg max-md:py-2"
                                        :title="trans('shop::app.checkout.coupon.button-title')"
                                        ::loading="isStoring"
                                        ::disabled="isStoring"
                                    />
                                </div>
                            </x-slot>
                        </x-shop::modal>

                        {!! view_render_event('bagisto.shop.checkout.cart.coupon.coupon_form_controls.after') !!}
                    </form>
                </x-shop::form>

                <!-- Applied Coupon Information Container -->
                <span
                    class="inline-flex items-center gap-2"
                    v-if="cart.coupon_code"
                >
                    <span
                        class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-0.5 text-sm font-semibold text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 max-sm:text-xs"
                        :title="'@lang('shop::app.checkout.coupon.applied')'"
                    >
                        @{{ cart.coupon_code }}
                    </span>

                    <span
                        class="icon-cancel cursor-pointer text-xl text-gray-400 transition-colors hover:text-red-500 max-sm:text-base"
                        title="@lang('shop::app.checkout.coupon.remove')"
                        @click="destroyCoupon"
                    >
                    </span>
                </span>
            </p>

            {!! view_render_event('bagisto.shop.checkout.cart.coupon.after') !!}
        </div>
    </script>

    <script type="module">
        app.component('v-coupon', {
            template: '#v-coupon-template',
            
            props: {
                cart: Object,
                showInlineForm: {
                    type: Boolean,
                    default: false,
                },
            },

            data() {
                return {
                    isStoring: false,
                    couponCode: '',
                }
            },

            methods: {
                applyCoupon(params, { resetForm }) {
                    this.isStoring = true;

                    this.$axios.post("{{ route('shop.api.checkout.cart.coupon.apply') }}", params)
                        .then((response) => {
                            this.isStoring = false;

                            this.$emit('coupon-applied');
                  
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            if (! this.showInlineForm) {
                                this.$refs.couponModel.toggle();
                            }

                            resetForm();
                        })
                        .catch((error) => {
                            this.isStoring = false;

                            if (! this.showInlineForm) {
                                this.$refs.couponModel.toggle();
                            }

                            if ([400, 422].includes(error.response?.status || error.response?.request?.status)) {
                                this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.message });

                                resetForm();

                                return;
                            }

                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                        });
                },

                destroyCoupon() {
                    this.$axios.delete("{{ route('shop.api.checkout.cart.coupon.remove') }}", {
                            '_token': "{{ csrf_token() }}"
                        })
                        .then((response) => {
                            this.$emit('coupon-removed');

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch(error => console.log(error));
                },
            }
        })
    </script>
@endPushOnce
