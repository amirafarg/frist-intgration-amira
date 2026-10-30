@if (Webkul\Product\Helpers\ProductType::hasVariants($product->type))
    {!! view_render_event('bagisto.shop.products.view.configurable-options.before', ['product' => $product]) !!}

    <v-product-configurable-options :errors="errors"></v-product-configurable-options>

    {!! view_render_event('bagisto.shop.products.view.configurable-options.after', ['product' => $product]) !!}

    @php
        $isTshirtCategory = $product->categories->contains(fn ($category) =>
            $category->translations->contains(function ($translation) {
                $categoryIdentifier = strtolower(preg_replace(
                    '/[^a-z]/i',
                    '',
                    $translation->slug ?: $translation->name
                ));

                return in_array($categoryIdentifier, ['tshirt', 'tshirts'], true);
            })
        );
    @endphp

    @push('scripts')
        <script
            type="text/x-template"
            id="v-product-configurable-options-template"
        >
            <div class="w-[455px] max-w-full max-sm:w-full">
                <input
                    type="hidden"
                    name="selected_configurable_option"
                    id="selected_configurable_option"
                    :value="selectedOptionVariant"
                    ref="selected_configurable_option"
                >

                <div
                    class="mt-5"
                    v-for='(attribute, index) in childAttributes'
                >
                    <!-- Dropdown Options Container -->
                    <template v-if="! attribute.swatch_type || attribute.swatch_type == '' || attribute.swatch_type == 'dropdown'">
                        <!-- Dropdown Label -->
                        <h2 class="mb-4 text-red-50 text-xl max-sm:mb-1.5 max-sm:text-base max-sm:font-medium">
                            @{{ attribute.label }}
                        </h2>

                        <!-- Dropdown Options -->
                        <v-field
                            as="select"
                            :name="'super_attribute[' + attribute.id + ']'"
                            class="custom-select mb-3 block w-full cursor-pointer rounded-lg border border-zinc-200 bg-white px-5 py-3 text-base text-zinc-500 focus:border-blue-500 focus:ring-blue-500"
                            :class="[errors['super_attribute[' + attribute.id + ']'] ? 'border border-red-500' : '']"
                            :id="'attribute_' + attribute.id"
                            v-model="attribute.selectedValue"
                            rules="required"
                            :label="attribute.label"
                            :aria-label="attribute.label"
                            :disabled="attribute.disabled"
                            @change="configure(attribute, $event.target.value)"
                        >
                            <option
                                v-for='(option, index) in attribute.options'
                                :value="option.id"
                            >
                                @{{ option.label }}
                            </option>
                        </v-field>
                    </template>

                    <!-- Swatch Options Container -->
                    <template v-else>
                        <!-- Option Label -->
                        <div class="w-[455px] max-w-full max-sm:w-full">
                                <div class="flex items-center justify-between w-full">
                                    <h2 class="mb-2 text-black mt-1 text-xl max-sm:mb-2 max-sm:text-base">
                                        @{{ attribute.label }}
                                    </h2>

                                    @unless ($isKnittedRoundW26 ?? false)
                                    <template v-if="attribute.swatch_type == 'text' && ['size', 'jeans_size', 'jeans_size_testing'].includes(attribute.code)">
                                        <button
                                            type="button"
                                            class="text-primary hover:text-orange underline font-medium cursor-pointer flex items-center gap-1 text-sm absolute right-0 max-sm:relative max-sm:right-auto"
                                            @click="$refs.sizeChartModal.open()"
                                        >
                                            <span class="icon-ruler text-lg"></span>
                                            View Size Chart
                                        </button>
                                    </template>
                                    @endunless
                            </div>
                        </div>

                        <!-- Swatch Options -->
                        <div class="flex items-stretch gap-2">
                            <template v-for="(option, index) in attribute.options">
                                <template v-if="option.id">
                                    <!-- Color Swatch Options -->
                                    <label
                                        class="relative  -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none"
                                        :class="{'ring-2 ring-gray-900' : option.id == attribute.selectedValue}"
                                        :title="option.label"
                                        v-if="attribute.swatch_type == 'color'"
                                     >
                                        <v-field
                                            type="radio"
                                            :name="'super_attribute[' + attribute.id + ']'"
                                            :value="option.id"
                                            v-slot="{ field }"
                                            rules="required"
                                            :label="attribute.label"
                                            :aria-label="attribute.label"
                                        >
                                            <input
                                                type="radio"
                                                :name="'super_attribute[' + attribute.id + ']'"
                                                :value="option.id"
                                                v-bind="field"
                                                :id="'attribute_' + attribute.id"
                                                :aria-labelledby="'color-choice-' + index + '-label'"
                                                class="peer sr-only"
                                                @click="configure(attribute, $event.target.value)"
                                            />
                                        </v-field>

                                        <span
                                            class="h-8 w-8 rounded-full border border-gray-200 max-sm:h-[25px] max-sm:w-[25px]"
                                            tabindex="0"
                                            :style="{ 'background-color': option.swatch_value }"
                                        ></span>
                                    </label>

                                    <!-- Image Swatch Options -->
                                    <label
                                        class="group relative flex h-[60px] w-[60px] cursor-pointer items-center justify-center overflow-hidden rounded-md border bg-white font-medium uppercase text-gray-900 hover:bg-gray-50 sm:py-6"
                                        :class="{'border-navyBlue' : option.id == attribute.selectedValue }"
                                        :title="option.label"
                                        v-if="attribute.swatch_type == 'image'"
                                     >
                                        <v-field
                                            type="radio"
                                            :name="'super_attribute[' + attribute.id + ']'"
                                            v-model="attribute.selectedValue"
                                            :value="option.id"
                                            v-slot="{ field }"
                                            rules="required"
                                            :label="attribute.label"
                                            :aria-label="attribute.label"
                                        >
                                            <input
                                                type="radio"
                                                :name="'super_attribute[' + attribute.id + ']'"
                                                :value="option.id"
                                                v-bind="field"
                                                :id="'attribute_' + attribute.id"
                                                :aria-labelledby="'color-choice-' + index + '-label'"
                                                class="peer sr-only"
                                                @click="configure(attribute, $event.target.value)"
                                            />
                                        </v-field>

                                        <img
                                            :src="option.swatch_value"
                                            :title="option.label"
                                        />
                                    </label>

                                    <!-- Text Swatch Options -->
                                    <label
                                        class="group relative flex h-fit w-[50px] cursor-pointer items-stretch justify-center rounded-sm border border-gray-300 bg-white px-4 py-2 font-medium uppercase text-gray-900 hover:bg-gray-50 max-sm:h-fit max-sm:w-fit max-sm:px-3.5 max-sm:py-2"
                                        :class="{'border-transparent !bg-black text-white' : option.id == attribute.selectedValue}"

                                        :title="option.label"
                                        v-if="attribute.swatch_type == 'text'"
                                     >
                                        <v-field
                                            type="radio"
                                            :name="'super_attribute[' + attribute.id + ']'"
                                            :value="option.id"
                                            v-model="attribute.selectedValue"
                                            v-slot="{ field }"
                                            rules="required"
                                            :label="attribute.label"
                                            :aria-label="attribute.label"
                                        >
                                            <input
                                                type="radio"
                                                :name="'super_attribute[' + attribute.id + ']'"
                                                :value="option.id"
                                                v-bind="field"
                                                :id="'attribute_' + attribute.id"
                                                class="peer sr-only"
                                                :aria-labelledby="'color-choice-' + index + '-label'"
                                                @click="configure(attribute, $event.target.value)"
                                            />
                                        </v-field>

                                        <span class="text-lg max-sm:text-sm">
                                            @{{ option.label }}
                                        </span>

                                        <span
                                            class="pointer-events-none absolute -inset-px rounded-full"
                                            role="presentation"
                                        >
                                        </span>
                                    </label>
                                </template>

                            </template>
                        </div>

                        <div class="block mt-4 text-base text-zinc-800 max-sm:mt-3 max-sm:text-sm">
                            <span
                                class="text-sm text-gray-600 max-sm:text-xs"
                                v-if="! attribute.options.length">

                                @lang('shop::app.products.view.type.configurable.select-above-options')
                            </span>

                            <!-- paragraph -->
                            @if (! ($isKnittedRoundW26 ?? false))
                            @if ($isTshirtCategory)
                                @if ($isSlimTeeS24 ?? false)
                                    <p class="w-max max-w-none text-sm text-darkBlack max-sm:w-full max-sm:text-xs"
                                        v-if="attribute.swatch_type == 'text' && ['size', 'jeans_size', 'jeans_size_testing'].includes(attribute.code)">
                                        <span class="block whitespace-nowrap max-sm:whitespace-normal">Slim Tee S24 A modern Round Slim Fit T-Shirt crafted from 100% cotton,</span>
                                        <span class="block whitespace-nowrap max-sm:whitespace-normal">designed to deliver a clean, stylish look with everyday comfort.</span>
                                    </p>
                                @elseif ($isRoundBasRoglam ?? false)
                                    <p class="block text-sm max-sm:text-xs text-darkBlack whitespace-nowrap"
                                        v-if="attribute.swatch_type == 'text' && ['size', 'jeans_size', 'jeans_size_testing'].includes(attribute.code)">
                                        Round Basic Reglam — A versatile everyday essential made from 100% cotton,<br>
                                        featuring a classic regular fit and distinctive raglan sleeves. Designed to combine<br>
                                        all-day comfort with a clean, sporty, and effortlessly stylish look.
                                    </p>
                                @else
                                    <p class="block text-sm max-sm:text-xs text-darkBlack whitespace-nowrap"
                                        v-if="attribute.swatch_type == 'text' && ['size', 'jeans_size', 'jeans_size_testing'].includes(attribute.code)">
                                        Round Relaxed — A clean and versatile basic T-shirt designed with a relaxed fit,<br>
                                        <span class="block">offering everyday comfort and a modern effortless look.</span>
                                    </p>
                                @endif
                            @else
                                <p class="block text-base max-sm:text-xs text-darkBlack whitespace-nowrap"
                                    v-if="attribute.swatch_type == 'text' && ['size', 'jeans_size', 'jeans_size_testing'].includes(attribute.code)">
                                    A premium Box Fit shirt crafted from 100% cotton crinkle fabric, delivering <br>
                                    breathable comfort with a clean, modern silhouette.
                                </p>
                            @endif
                            @endif
                        </div>

                    </template>

                    <v-error-message class="d-none" v-if="message"
                        :name="'super_attribute[' + attribute.id + ']'"
                        v-slot="{ message }"
                    >
                        <p class="mt-1 text-xs italic text-red-500">
                            @{{ message }}
                        </p>
                    </v-error-message>
                </div>
            </div>

            <v-modal
                :is-active="false"
                ref="sizeChartModal">
                    <template v-slot:header="{ toggle }">
                        <div class="sticky top-0 flex items-center justify-between gap-5 border-b border-zinc-200 bg-white px-6 py-3 max-sm:p-4">
                            <p class="text-xl font-medium">
                                Size Chart
                            </p>

                            <span
                                class="icon-cancel cursor-pointer text-3xl max-sm:text-2xl"
                                @click="toggle"
                            ></span>
                        </div>
                    </template>

                    <template v-slot:content>
                        <div class="bg-white p-6 max-sm:p-4">
                            <div class="grid gap-5 items-center">
                                <img
                                    src="{{ ($isSlimTeeS24 ?? false) ? asset('images/DaiGcPZ9zNCzmudBoVpstyalecoOEyLBldOjyFPc.jpg') : (($isRoundBasRoglam ?? false) ? asset('images/EzdQT0426YyWnoN8tqDmi04IqbyoriJDb8EV9GmG.webp') : ($isTshirtCategory ? asset('images/relaxed-size.jpg') : 'https://keswastore.com/storage/size-charts/w987kynRLdfd934NWctQ3rtGzRK1hzjxR1T9W6bN.jpg')) }}"
                                    class="block mx-auto max-w-[200px] rounded-lg border border-gray-200"
                                    alt="Size Chart Image"
                                >

                                <div class="overflow-x-auto">
                                    <table class="w-full border-collapse text-left border border-gray-200">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="p-3 border-b border-gray-200 font-semibold text-gray-800 text-sm">Size</th>
                                                <th class="p-3 border-b border-gray-200 font-semibold text-gray-800 text-sm">M</th>
                                                <th class="p-3 border-b border-gray-200 font-semibold text-gray-800 text-sm">L</th>
                                                <th class="p-3 border-b border-gray-200 font-semibold text-gray-800 text-sm">XL</th>
                                                <th class="p-3 border-b border-gray-200 font-semibold text-gray-800 text-sm">XXL</th>
                                                @if ($isSlimTeeS24 ?? false)
                                                    <th class="p-3 border-b border-gray-200 font-semibold text-gray-800 text-sm">XXXL</th>
                                                @endif
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr class="hover:bg-gray-50 text-gray-600">
                                                <td class="p-3 border-b border-gray-100 font-medium text-sm">chest</td>
                                                <td class="p-3 border-b border-gray-100 text-sm">{{ ($isSlimTeeS24 ?? false) ? 50 : (($isRoundBasRoglam ?? false) ? 51 : ($isTshirtCategory ? 55 : 58)) }}</td>
                                                <td class="p-3 border-b border-gray-100 text-sm">{{ ($isSlimTeeS24 ?? false) ? 53 : (($isRoundBasRoglam ?? false) ? 54 : ($isTshirtCategory ? 57 : 59)) }}</td>
                                                <td class="p-3 border-b border-gray-100 text-sm">{{ ($isSlimTeeS24 ?? false) ? 57 : (($isRoundBasRoglam ?? false) ? 57 : ($isTshirtCategory ? 59 : 60)) }}</td>
                                                <td class="p-3 border-b border-gray-100 text-sm">{{ ($isSlimTeeS24 ?? false) ? 60 : (($isRoundBasRoglam ?? false) ? 60 : 61) }}</td>
                                                @if ($isSlimTeeS24 ?? false)
                                                    <td class="p-3 border-b border-gray-100 text-sm"></td>
                                                @endif
                                            </tr>

                                            <tr class="hover:bg-gray-50 text-gray-600">
                                                <td class="p-3 border-b border-gray-100 font-medium text-sm">length</td>
                                                <td class="p-3 border-b border-gray-100 text-sm">{{ ($isSlimTeeS24 ?? false) ? 68 : (($isRoundBasRoglam ?? false) ? 72 : ($isTshirtCategory ? 71 : 70)) }}</td>
                                                <td class="p-3 border-b border-gray-100 text-sm">{{ ($isSlimTeeS24 ?? false) ? 71 : (($isRoundBasRoglam ?? false) ? 74 : ($isTshirtCategory ? 72 : 71)) }}</td>
                                                <td class="p-3 border-b border-gray-100 text-sm">{{ ($isSlimTeeS24 ?? false) ? 73 : (($isRoundBasRoglam ?? false) ? 76 : ($isTshirtCategory ? 73 : 72)) }}</td>
                                                <td class="p-3 border-b border-gray-100 text-sm">{{ ($isSlimTeeS24 ?? false) ? 75 : (($isRoundBasRoglam ?? false) ? 78 : ($isTshirtCategory ? 74 : 73)) }}</td>
                                                @if ($isSlimTeeS24 ?? false)
                                                    <td class="p-3 border-b border-gray-100 text-sm"></td>
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </template>
            </v-modal>
        </script>

        <script type="module">
            let galleryImages = @json(product_image()->getGalleryImages($product));

            app.component('v-product-configurable-options', {
                template: '#v-product-configurable-options-template',

                props: ['errors'],

                data() {
                    return {
                        config: @json(app('Webkul\Product\Helpers\ConfigurableOption')->getConfigurationConfig($product)),

                        childAttributes: [],

                        possibleOptionVariant: null,

                        selectedOptionVariant: '',

                        galleryImages: [],
                    }
                },

                mounted() {
                    let attributes = JSON.parse(JSON.stringify(this.config)).attributes.slice();

                    let index = attributes.length;

                    while (index--) {
                        let attribute = attributes[index];

                        attribute.options = [];

                        attribute.disabled = false;

                        this.fillAttributeOptions(attribute);

                        attribute = Object.assign(attribute, {
                            childAttributes: this.childAttributes.slice(),
                            prevAttribute: attributes[index - 1],
                            nextAttribute: attributes[index + 1]
                        });

                        this.childAttributes.unshift(attribute);
                    }

                    this.reloadPrice();
                },

                methods: {
                    configure(attribute, optionId) {
                        this.possibleOptionVariant = this.getPossibleOptionVariant(attribute, optionId);

                        if (optionId) {
                            attribute.selectedValue = optionId;

                            if (attribute.nextAttribute) {
                                attribute.nextAttribute.disabled = false;

                                this.clearAttributeSelection(attribute.nextAttribute);

                                this.fillAttributeOptions(attribute.nextAttribute);

                                this.resetChildAttributes(attribute.nextAttribute);
                            } else {
                                this.selectedOptionVariant = this.possibleOptionVariant;
                            }
                        } else {
                            this.clearAttributeSelection(attribute);

                            this.clearAttributeSelection(attribute.nextAttribute);

                            this.resetChildAttributes(attribute);
                        }

                        this.reloadPrice();

                        this.reloadImages();
                    },

                    getPossibleOptionVariant(attribute, optionId) {
                        let matchedOptions = attribute.options.filter(option => option.id == optionId);

                        if (matchedOptions[0]?.allowedProducts) {
                            return matchedOptions[0].allowedProducts[0];
                        }

                        return undefined;
                    },

                    fillAttributeOptions(attribute) {
                        let options = this.config.attributes.find(tempAttribute => tempAttribute.id === attribute.id)?.options;

                        attribute.options = [{
                            'id': '',
                            'label': "@lang('shop::app.products.view.type.configurable.select-options')",
                            'products': []
                        }];

                        if (! options) {
                            return;
                        }

                        let prevAttributeSelectedOption = attribute.prevAttribute?.options.find(option => option.id == attribute.prevAttribute.selectedValue);

                        let index = 1;

                        for (let i = 0; i < options.length; i++) {
                            let allowedProducts = [];

                            if (prevAttributeSelectedOption) {
                                for (let j = 0; j < options[i].products.length; j++) {
                                    if (prevAttributeSelectedOption.allowedProducts && prevAttributeSelectedOption.allowedProducts.includes(options[i].products[j])) {
                                        allowedProducts.push(options[i].products[j]);
                                    }
                                }
                            } else {
                                allowedProducts = options[i].products.slice(0);
                            }

                            if (allowedProducts.length > 0) {
                                options[i].allowedProducts = allowedProducts;

                                attribute.options[index++] = options[i];
                            }
                        }
                    },

                    resetChildAttributes(attribute) {
                        if (! attribute.childAttributes) {
                            return;
                        }

                        attribute.childAttributes.forEach(function (set) {
                            set.selectedValue = null;

                            set.disabled = true;
                        });
                    },

                    clearAttributeSelection (attribute) {
                        if (! attribute) {
                            return;
                        }

                        attribute.selectedValue = null;

                        this.selectedOptionVariant = null;
                    },

                    reloadPrice () {
                        let selectedOptionCount = this.childAttributes.filter(
                            attribute => attribute.selectedValue
                        ).length;

                        let finalPrice = document.querySelector('.final-price');
                        let regularPrice = document.querySelector('.regular-price');
                        let discountBadge = document.querySelector('.discount-badge');

                        let configVariant = this.config.variant_prices[this.possibleOptionVariant];

                        const updateDisplayedPrices = (finalValue, regularValue) => {
                            const finalAmount = parseFloat(finalValue) || 0;
                            const regularAmount = parseFloat(regularValue) || 0;
                            const hasDiscount = regularAmount > finalAmount;

                            finalPrice.innerHTML = finalAmount.toFixed(0) + ' <span>EGP</span>';
                            regularPrice.innerHTML = regularAmount.toFixed(0) + ' <span>EGP</span>';
                            regularPrice.style.display = hasDiscount ? 'block' : 'none';

                            if (discountBadge) {
                                discountBadge.style.display = hasDiscount ? 'block' : 'none';
                                discountBadge.textContent = hasDiscount
                                    ? Math.round(((regularAmount - finalAmount) / regularAmount) * 100) + '% OFF'
                                    : '';
                            }
                        };

                        if (@json($isRoundBasRoglam ?? false)) {
                            updateDisplayedPrices(275, 550);

                            this.$emitter.emit(
                                'configurable-variant-selected-event',
                                this.childAttributes.length == selectedOptionCount
                                    ? this.possibleOptionVariant
                                    : 0
                            );

                            return;
                        }

                        if (@json($isSlimTeeS24 ?? false)) {
                            updateDisplayedPrices(299, 600);

                            this.$emitter.emit(
                                'configurable-variant-selected-event',
                                this.childAttributes.length == selectedOptionCount
                                    ? this.possibleOptionVariant
                                    : 0
                            );

                            return;
                        }

                        if (@json($isKnittedRoundW26 ?? false)) {
                            const parentFinalPrice = parseFloat(
                                this.config.final?.price
                                ?? finalPrice.textContent.replace(/[^0-9.]/g, '')
                            );
                            const variantFinalPrice = this.childAttributes.length == selectedOptionCount && configVariant
                                ? parseFloat(configVariant.final.price)
                                : parentFinalPrice;

                            updateDisplayedPrices(variantFinalPrice || parentFinalPrice, 700);

                            this.$emitter.emit(
                                'configurable-variant-selected-event',
                                this.childAttributes.length == selectedOptionCount
                                    ? this.possibleOptionVariant
                                    : 0
                            );

                            return;
                        }

                        // All options selected.
                        if (this.childAttributes.length == selectedOptionCount && configVariant) {
                            let variantFinalPrice = parseFloat(configVariant.final.price);
                            let variantRegularPrice = parseFloat(configVariant.regular.price);
                            const parentFinalPrice = parseFloat(this.config.final?.price ?? 0);
                            const parentRegularPrice = parseFloat(this.config.regular?.price ?? 0);

                            if (variantRegularPrice <= 0 && parentRegularPrice > 0) {
                                variantFinalPrice = parentFinalPrice;
                                variantRegularPrice = parentRegularPrice;
                            }

                            // A configurable product may have its special price
                            // on the parent while child variants have no override.
                            if (
                                variantFinalPrice >= variantRegularPrice
                                && parentFinalPrice < parentRegularPrice
                                && parentRegularPrice > 0
                            ) {
                                variantFinalPrice = variantRegularPrice * (parentFinalPrice / parentRegularPrice);
                            }

                            updateDisplayedPrices(variantFinalPrice, variantRegularPrice);

                            this.$emitter.emit(
                                'configurable-variant-selected-event',
                                this.possibleOptionVariant
                            );

                            return;
                        }

                        // Options are not completely selected yet.
                        updateDisplayedPrices(this.config.final?.price, this.config.regular?.price);

                        this.$emitter.emit(
                            'configurable-variant-selected-event',
                            0
                        );
                    },

                    reloadImages () {
                        galleryImages.splice(0, galleryImages.length);

                        let imageVariant = this.possibleOptionVariant;

                        console.log('IMAGE VARIANT:', this.possibleOptionVariant);

                        /*
                        * If only the color is selected, use the first available
                        * variant belonging to that color.
                        */
                        let selectedOptionCount = this.childAttributes.filter(
                            attribute => attribute.selectedValue
                        ).length;

                        if (
                            selectedOptionCount < this.childAttributes.length
                        ) {
                            for (let i = 0; i < this.childAttributes.length; i++) {
                                let attribute = this.childAttributes[i];

                                if (! attribute.selectedValue) {
                                    continue;
                                }

                                let selectedOption = attribute.options.find(
                                    option => option.id == attribute.selectedValue
                                );

                                if (
                                    selectedOption &&
                                    selectedOption.allowedProducts &&
                                    selectedOption.allowedProducts.length
                                ) {
                                    let variantWithImage = selectedOption.allowedProducts.find(
                                        variantId =>
                                            this.config.variant_images[variantId] &&
                                            this.config.variant_images[variantId].length
                                    );

                                    if (variantWithImage) {
                                        imageVariant = variantWithImage;

                                        break;
                                    }
                                }
                            }
                        }

                        /*
                        * Load images for the selected color/variant.
                        */
                        if (imageVariant) {
                            const variantImages =
                                this.config.variant_images[imageVariant] || [];

                            const variantVideos =
                                this.config.variant_videos[imageVariant] || [];

                            variantImages.forEach(function (image) {
                                galleryImages.push(image);
                            });

                            variantVideos.forEach(function (video) {
                                galleryImages.push(video);
                            });
                        }

                        /*
                        * Update gallery.
                        */
                        if (galleryImages.length) {
                            this.$parent.$parent.$refs.gallery.media.images = [
                                ...galleryImages
                            ];
                        }

                        this.$emitter.emit(
                            'configurable-variant-update-images-event',
                            galleryImages
                        );
                    },
                }
            });

        </script>
    @endpush

@endif
