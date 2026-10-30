@props([
    'name'     => '',
    'value'    => 1,
    'minValue' => 1,
    'maxValue' => 100,
])

<v-quantity-changer
    {{ $attributes->merge(['class' => 'flex items-center border border-navyBlue']) }}
    name="{{ $name }}"
    value="{{ $value }}"
    min-value="{{ $minValue }}"
    max-value="{{ $maxValue }}"
>
</v-quantity-changer>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-quantity-changer-template"
    >
        <div>
            <span 
                class="icon-minus cursor-pointer text-2xl"
                role="button"
                tabindex="0"
                aria-label="@lang('shop::app.components.quantity-changer.decrease-quantity')"
                @click="decrease"
            >
            </span>

            <p class="w-2.5 select-none text-center max-sm:text-sm">
                @{{ quantity }}
            </p>
            
            <span 
                class="icon-plus cursor-pointer text-2xl"
                role="button"
                tabindex="0"
                aria-label="@lang('shop::app.components.quantity-changer.increase-quantity')"
                @click="increase"
            >
            </span>

            <v-field
                type="hidden"
                :name="name"
                v-model="quantity"
            ></v-field>
        </div>
    </script>

    <script type="module">
        app.component("v-quantity-changer", {
            template: '#v-quantity-changer-template',

            props:['name', 'value', 'minValue', 'maxValue'],

            data() {
                return  {
                    quantity: Math.min(Number(this.value) || 1, Number(this.maxValue) || 100),
                }
            },

            watch: {
                value() {
                    this.quantity = Math.min(Number(this.value) || 1, Number(this.maxValue) || 100);
                },
            },

            methods: {
                increase() {
                    if (Number(this.quantity) < Number(this.maxValue)) {
                        this.quantity = Number(this.quantity) + 1;

                        this.$emit('change', this.quantity);
                    }
                },

                decrease() {
                    if (this.quantity > this.minValue) {
                        this.quantity -= 1;

                        this.$emit('change', this.quantity);
                    }
                },
            }
        });
    </script>
@endpushOnce
