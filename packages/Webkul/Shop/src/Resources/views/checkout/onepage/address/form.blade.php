@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-checkout-address-form-template"
    >
        <div class="mt-2 max-md:mt-3">
            <x-shop::form.control-group class="hidden">
                <x-shop::form.control-group.control
                    type="text"
                    ::name="controlName + '.id'"
                    ::value="address.id"
                />
            </x-shop::form.control-group>

            <!-- First Name -->
            <div class="grid grid-cols-2 gap-x-5 max-md:grid-cols-1">
                <x-shop::form.control-group>
                    <x-shop::form.control-group.label class="required !mt-0">
                        @lang('shop::app.checkout.onepage.address.first-name')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="text"
                        ::name="controlName + '.first_name'"
                        ::value="address.first_name"
                        rules="required"
                        :label="trans('shop::app.checkout.onepage.address.first-name')"
                        :placeholder="trans('shop::app.checkout.onepage.address.first-name')"
                    />

                    <x-shop::form.control-group.error ::name="controlName + '.first_name'" />
                </x-shop::form.control-group>

                {!! view_render_event('bagisto.shop.checkout.onepage.address.form.first_name.after') !!}

                <!-- Last Name -->
                <x-shop::form.control-group>
                    <x-shop::form.control-group.label class="required !mt-0">
                        @lang('shop::app.checkout.onepage.address.last-name')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="text"
                        ::name="controlName + '.last_name'"
                        ::value="address.last_name"
                        rules="required"
                        :label="trans('shop::app.checkout.onepage.address.last-name')"
                        :placeholder="trans('shop::app.checkout.onepage.address.last-name')"
                    />

                    <x-shop::form.control-group.error ::name="controlName + '.last_name'" />
                </x-shop::form.control-group>

                {!! view_render_event('bagisto.shop.checkout.onepage.address.form.last_name.after') !!}
            </div>

            <!-- Email -->
            <x-shop::form.control-group>
                <x-shop::form.control-group.label class="required !mt-0">
                    @lang('shop::app.checkout.onepage.address.email')
                </x-shop::form.control-group.label>

                <x-shop::form.control-group.control
                    type="email"
                    ::name="controlName + '.email'"
                    ::value="address.email"
                    rules="required|email"
                    :label="trans('shop::app.checkout.onepage.address.email')"
                    placeholder="email@example.com"
                />

                <x-shop::form.control-group.error ::name="controlName + '.email'" />
            </x-shop::form.control-group>

            {!! view_render_event('bagisto.shop.checkout.onepage.address.form.email.after') !!}

            <div class="checkout-street-fields grid grid-cols-3 gap-x-5 max-md:grid-cols-1">
                <x-shop::form.control-group>
                    <x-shop::form.control-group.label class="required !mt-0">Street Name</x-shop::form.control-group.label>
                    <x-shop::form.control-group.control type="text" ::name="controlName + '.address.[0]'" ::value="address.address[0]" rules="required|address" label="Street Name" placeholder="E.g. El-Tahrir St." />
                    <x-shop::form.control-group.error ::name="controlName + '.address.[0]'" />
                </x-shop::form.control-group>

                <x-shop::form.control-group>
                    <x-shop::form.control-group.label class="required !mt-0">Building No</x-shop::form.control-group.label>
                    <x-shop::form.control-group.control type="text" ::name="controlName + '.address.[1]'" ::value="address.address[1]" rules="required" label="Building No" placeholder="E.g. 15" />
                </x-shop::form.control-group>

                <x-shop::form.control-group>
                    <x-shop::form.control-group.label class="required !mt-0">Floor / Apt</x-shop::form.control-group.label>
                    <x-shop::form.control-group.control type="text" ::name="controlName + '.address.[2]'" ::value="address.address[2]" rules="required" label="Floor / Apt" placeholder="E.g. 4" />
                </x-shop::form.control-group>
            </div>

            <p class="mb-4 text-sm text-zinc-500">Please enter address details separately for accurate shipping.</p>

            {!! view_render_event('bagisto.shop.checkout.onepage.address.form.address.after') !!}

            <div class="grid grid-cols-2 gap-x-5 max-md:grid-cols-1">
                <x-shop::form.control-group>
                    <x-shop::form.control-group.label class="{{ core()->isCountryRequired() ? 'required' : '' }} !mt-0">Country</x-shop::form.control-group.label>
                    <x-shop::form.control-group.control type="select" ::name="controlName + '.country'" ::value="address.country" v-model="selectedCountry" rules="{{ core()->isCountryRequired() ? 'required' : '' }}" label="Country" placeholder="Country">
                        <option value="EG">Egypt</option>
                    </x-shop::form.control-group.control>
                    <x-shop::form.control-group.error ::name="controlName + '.country'" />
                </x-shop::form.control-group>

                <x-shop::form.control-group>
                    <x-shop::form.control-group.label class="required !mt-0">City</x-shop::form.control-group.label>
                    <x-shop::form.control-group.control type="select" ::name="controlName + '.city'" ::value="address.city" v-model="selectedCity" rules="required" label="City" placeholder="Select City">
                        <option value="">Select City</option>
                        <option v-for="city in egyptianCities" :key="city" :value="city">@{{ city }}</option>
                    </x-shop::form.control-group.control>
                    <x-shop::form.control-group.error ::name="controlName + '.city'" />
                </x-shop::form.control-group>
            </div>

            <x-shop::form.control-group>
                <x-shop::form.control-group.label class="required !mt-0">Area / District</x-shop::form.control-group.label>
                <x-shop::form.control-group.control type="text" ::name="controlName + '.state'" ::value="address.state" v-model="selectedArea" rules="required" label="Area / District" ::placeholder="selectedCity ? 'Area / District' : 'Must select City first'" ::disabled="! selectedCity" />
                <p v-if="! selectedCity" class="mt-1 text-sm text-zinc-500">Must select City first</p>
                <x-shop::form.control-group.error ::name="controlName + '.state'" />
            </x-shop::form.control-group>

            @if (core()->isPostCodeRequired())
                <x-shop::form.control-group class="hidden">
                    <x-shop::form.control-group.control type="text" ::name="controlName + '.postcode'" ::value="address.postcode" rules="required|postcode" label="Postcode" />
                </x-shop::form.control-group>
            @endif

            <x-shop::form.control-group>
                <x-shop::form.control-group.label class="required !mt-0">Telephone</x-shop::form.control-group.label>
                <x-shop::form.control-group.control type="text" ::name="controlName + '.phone'" ::value="address.phone" rules="required|phone" label="Telephone" placeholder="Telephone" />
                <x-shop::form.control-group.error ::name="controlName + '.phone'" />
            </x-shop::form.control-group>

            {!! view_render_event('bagisto.shop.checkout.onepage.address.form.phone.after') !!}
        </div>
    </script>

    <script type="module">
        app.component('v-checkout-address-form', {
            template: '#v-checkout-address-form-template',

            props: {
                controlName: {
                    type: String,
                    required: true,
                },

                address: {
                    type: Object,

                    default: () => ({
                        id: 0,
                        company_name: '',
                        first_name: '',
                        last_name: '',
                        email: '',
                        address: [],
                        country: '',
                        state: '',
                        city: '',
                        postcode: '',
                        phone: '',
                    }),
                },
            },

            data() {
                return {
                    selectedCountry: this.address.country || 'EG',

                    selectedCity: this.address.city,

                    selectedArea: this.address.state,

                    egyptianCities: [
                        'Cairo', 'Giza', 'Alexandria', 'Shubra El Kheima', 'Port Said',
                        'Suez', 'Mansoura', 'Tanta', 'Zagazig', 'Ismailia', 'Faiyum',
                        'Beni Suef', 'Minya', 'Asyut', 'Sohag', 'Qena', 'Luxor',
                        'Aswan', 'Damietta', '6th of October', 'New Cairo', 'Obour',
                        'Sheikh Zayed', 'Banha', 'Kafr El Sheikh', 'Damanhur', 'Hurghada',
                        'Sharm El Sheikh', 'Marsa Matruh', 'Arish', 'New Alamein',
                    ],
                }
            },

            watch: {
                selectedCity() {
                    this.selectedArea = '';
                },
            },
        });
    </script>
@endPushOnce
