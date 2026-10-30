<div
    class="w-full overflow-hidden 1180:hidden"
    v-if="isMediaLoading"
>
    <div class="shimmer aspect-square max-h-screen w-full bg-zinc-200"></div>
</div>

<div
    class="w-full overflow-hidden 1180:hidden"
    v-else
>
    <v-product-carousel
        :options="[
            ...media.images,
            ...media.videos
        ]"
        @click="isImageZooming = ! isImageZooming"
    >
        <x-shop::shimmer.products.gallery />
    </v-product-carousel>
</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-carousel-template"
    >
        <div
            class="relative mx-auto w-full overflow-hidden"
            @touchstart.passive="handleTouchStart"
            @touchend="handleTouchEnd"
        >
            <template v-if="activeMedia">
                <video
                    v-if="activeMedia.type == 'videos'"
                    controls
                    class="block h-auto w-full object-contain"
                    :alt="activeMedia.video_url"
                    :key="activeMedia.video_url"
                >
                    <source
                        :src="activeMedia.video_url"
                        type="video/mp4"
                    />
                </video>

                <img
                    v-else
                    class="mx-auto block h-auto w-full select-none object-contain"
                    :src="activeMedia.large_image_url"
                    :alt="activeMedia.large_image_url"
                    draggable="false"
                />
            </template>

            <div
                v-if="options?.length > 1"
                class="absolute bottom-3 left-0 flex w-full justify-center max-sm:bottom-2.5"
            >
                <button
                    v-for="(media, index) in options"
                    :key="index"
                    type="button"
                    class="mx-1 h-1.5 w-1.5 cursor-pointer rounded-full"
                    :class="{ 'bg-navyBlue': index === currentIndex, 'bg-gray-500 opacity-30': index !== currentIndex }"
                    :aria-label="`Show image ${index + 1}`"
                    @click.stop="currentIndex = index"
                ></button>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-product-carousel', {
            template: '#v-product-carousel-template',

            props: ['options'],

            data() {
                return {
                    currentIndex: 0,
                    touchStartX: 0,
                };
            },

            computed: {
                activeMedia() {
                    return this.options?.[this.currentIndex] ?? null;
                },
            },

            watch: {
                options() {
                    this.currentIndex = Math.min(this.currentIndex, Math.max(this.options.length - 1, 0));
                },
            },

            methods: {
                handleTouchStart(event) {
                    this.touchStartX = event.changedTouches[0].clientX;
                },

                handleTouchEnd(event) {
                    const touchEndX = event.changedTouches[0].clientX;
                    const movement = touchEndX - this.touchStartX;
                    const isRtl = document.dir === 'rtl';

                    if (Math.abs(movement) < 50) {
                        return;
                    }

                    if ((movement < 0 && ! isRtl) || (movement > 0 && isRtl)) {
                        this.currentIndex = Math.min(this.currentIndex + 1, this.options.length - 1);

                        return;
                    }

                    this.currentIndex = Math.max(this.currentIndex - 1, 0);
                },
            },
        });
    </script>
@endPushOnce
