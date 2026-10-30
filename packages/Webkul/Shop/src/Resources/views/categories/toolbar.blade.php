{!! view_render_event('bagisto.shop.categories.view.toolbar.before') !!}

<v-toolbar @filter-applied='setFilters("toolbar", $event)'></v-toolbar>

{!! view_render_event('bagisto.shop.categories.view.toolbar.after') !!}

@inject('toolbar' , 'Webkul\Product\Helpers\Toolbar')

@pushOnce('scripts')
    <script
        type="text/x-template"
        id='v-toolbar-template'
    >
        <div>
            <!-- Desktop Toolbar -->
            <div class="category-toolbar-desktop grid grid-cols-2 items-center max-md:hidden">
                {!! view_render_event('bagisto.shop.categories.toolbar.filter.before') !!}

                <x-shop::dropdown
                    class="category-sort-control z-[1] col-start-2 row-start-1"
                    position="bottom-left"
                >
                    <x-slot:toggle>
                        <button class="flex w-full max-w-[220px] cursor-pointer items-center justify-between gap-4 rounded-lg border border-zinc-200 bg-white px-5 py-3 text-sm transition-all hover:border-gray-400 focus:border-gray-400">
                            @{{ sortLabel ?? "@lang('shop::app.products.sort-by.title')" }}

                            <span class="icon-arrow-down text-xl" role="presentation"></span>
                        </button>
                    </x-slot>

                    <x-slot:menu>
                        <x-shop::dropdown.menu.item
                            v-for="(sort, key) in filters.available.sort"
                            ::class="{'bg-gray-100': sort.value == filters.applied.sort}"
                            @click="apply('sort', sort.value)"
                        >
                            @{{ sort.value === 'created_at-desc' ? 'Newest First' : sort.title }}
                        </x-shop::dropdown.menu.item>
                    </x-slot>
                </x-shop::dropdown>

                {!! view_render_event('bagisto.shop.categories.toolbar.filter.after') !!}
                {!! view_render_event('bagisto.shop.categories.toolbar.pagination.before') !!}

                <div class="category-view-modes col-start-1 row-start-1 flex items-center gap-5">
                    <span
                        class="flex h-7 w-7 cursor-pointer items-center justify-center"
                        style="width: 28px; height: 28px; flex: 0 0 28px"
                        role="button"
                        aria-label="@lang('shop::app.categories.toolbar.list')"
                        tabindex="0"
                        :class="((filters.applied.mode ?? filters.default.mode) === 'list') ? 'text-zinc-900' : 'text-zinc-400'"
                        @click="changeMode('list')"
                    >
                        <svg aria-hidden="true" class="h-6 w-6" style="width:24px;height:24px" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="4" cy="5" r="1.7" />
                            <circle cx="4" cy="12" r="1.7" />
                            <circle cx="4" cy="19" r="1.7" />
                            <rect x="9" y="3.8" width="14" height="2.4" rx="1.2" />
                            <rect x="9" y="10.8" width="14" height="2.4" rx="1.2" />
                            <rect x="9" y="17.8" width="14" height="2.4" rx="1.2" />
                        </svg>
                    </span>

                    <span
                        class="flex h-7 w-7 cursor-pointer items-center justify-center"
                        style="width: 28px; height: 28px; flex: 0 0 28px"
                        role="button"
                        aria-label="@lang('shop::app.categories.toolbar.grid')"
                        tabindex="0"
                        :class="((filters.applied.mode ?? filters.default.mode) === 'grid') ? 'text-zinc-900' : 'text-zinc-400'"
                        @click="changeMode('grid')"
                    >
                        <svg aria-hidden="true" class="h-6 w-6" style="width:24px;height:24px" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="4" cy="5" r="2.6" />
                            <circle cx="12" cy="5" r="2.6" />
                            <circle cx="20" cy="5" r="2.6" />
                            <circle cx="4" cy="19" r="2.6" />
                            <circle cx="12" cy="19" r="2.6" />
                            <circle cx="20" cy="19" r="2.6" />
                        </svg>
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.categories.toolbar.pagination.after') !!}
            </div>

            <!-- Mobile Toolbar -->
            <div class="md:hidden">
                <ul>
                    <li
                        class="px-4 py-2.5"
                        :class="{'bg-gray-100': sort.value == filters.applied.sort}"
                        v-for="(sort, key) in filters.available.sort"
                        @click="apply('sort', sort.value)"
                    >
                        @{{ sort.title }}
                    </li>
                </ul>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-toolbar', {
            template: '#v-toolbar-template',

            data() {
                return {
                    filters: {
                        available: {
                            sort: @json($toolbar->getAvailableOrders()),

                            limit: @json($toolbar->getAvailableLimits()),

                            mode: @json($toolbar->getAvailableModes()),
                        },

                        default: {
                            sort: 'created_at-desc',

                            limit: '{{ $toolbar->getLimit([]) }}',

                            mode: '{{ $toolbar->getMode([]) }}',
                        },

                        applied: {
                            sort: '{{ $params['sort'] ?? 'created_at-desc' }}',

                            limit: '{{ $toolbar->getLimit($params ?? []) }}',

                            mode: '{{ $toolbar->getMode($params ?? []) }}',
                        }
                    }
                };
            },

            created() {
                let queryParams = new URLSearchParams(window.location.search);

                queryParams.forEach((value, filter) => {
                    if (['sort', 'limit', 'mode'].includes(filter)) {
                        this.filters.applied[filter] = value;
                    }
                });
            },

            mounted() {
                this.setFilters();
            },

            computed: {
                sortLabel() {
                    const selectedSort = this.filters.available.sort.find(sort => sort.value === this.filters.applied.sort);

                    return selectedSort?.value === 'created_at-desc' ? 'Newest First' : selectedSort?.title;
                }
            },

            methods: {
                apply(type, value) {
                    this.filters.applied[type] = value;

                    this.setFilters();
                },

                changeMode(value = 'grid') {
                    this.filters.applied['mode'] = value;

                    this.setFilters();
                },

                setFilters() {
                    let filters = {};

                    for (let key in this.filters.applied) {
                        if (this.filters.applied[key] != this.filters.default[key]) {
                            filters[key] = this.filters.applied[key];
                        }
                    }

                    this.$emit('filter-applied', {
                        default: this.filters.default,
                        applied: filters,
                    });
                }
            },
        });
    </script>
@endPushOnce
