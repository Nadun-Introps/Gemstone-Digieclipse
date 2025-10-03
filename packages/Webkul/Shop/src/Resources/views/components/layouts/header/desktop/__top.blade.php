{!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.before') !!}

<v-topbar></v-topbar>

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.after') !!}

@pushOnce('scripts')
    <!-- Topbar Template -->
    <script type="text/x-template" id="v-topbar-template">
        <div class="flex w-full items-center justify-between border border-b border-l-0 border-r-0 border-t-0 px-16" style="background-color:#F2F2F2;padding: 10px 70px 11px 70px;">
            
            <!-- Left corner welcome text -->
            <h6 class="text-sm font-semibold text-dark">
                Welcome to our online store!
            </h6>

            <!-- Right corner dropdown + login/register -->
            <div class="flex items-center gap-4">
                
                <!-- Combined Currency & Locale Dropdown -->
                <x-shop::dropdown position="bottom-right">
                    <x-slot:toggle>
                        <div
                            class="flex cursor-pointer items-center gap-2.5 text-dark"
                            role="button"
                            tabindex="0"
                            @click="combinedToggler = ! combinedToggler"
                        >
                            <span>
                                {{ core()->getCurrentCurrency()->symbol . ' ' . core()->getCurrentCurrencyCode() }}
                                /
                                {{ core()->getCurrentChannel()->locales()->orderBy('name')->where('code', app()->getLocale())->value('name') }}
                            </span>

                            <span
                                class="text-2xl"
                                :class="{'icon-arrow-up': combinedToggler, 'icon-arrow-down': ! combinedToggler}"
                                role="presentation"
                            ></span>
                        </div>
                    </x-slot>

                    <!-- Dropdown Content -->
                    <x-slot:content class="journal-scroll max-h-[500px] !p-0">
                        <div class="grid gap-2 p-2">
                            <!-- Currency List -->
                            <p class="px-3 py-1 text-xs font-semibold text-gray-600">Currencies</p>
                            <v-currency-switcher></v-currency-switcher>

                            <!-- Locale List -->
                            <p class="px-3 py-1 mt-2 text-xs font-semibold text-gray-600">Languages</p>
                            <v-locale-switcher></v-locale-switcher>
                        </div>
                    </x-slot>
                </x-shop::dropdown>

                <!-- Separator -->
                <span class="text-dark">|</span>

                <!-- Login / Register link-->
                <a href="#" class="text-dark hover:underline text-sm">
                    Login / Register
                </a>
            </div>
        </div>
    </script>

    <!-- Currency Switcher Template -->
    <script type="text/x-template" id="v-currency-switcher-template">
        <div class="my-2.5 grid gap-1 overflow-auto max-md:my-0 sm:max-h-[500px]">
            <span
                class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                v-for="currency in currencies"
                :class="{'bg-gray-100': currency.code == '{{ core()->getCurrentCurrencyCode() }}'}"
                @click="change(currency)"
            >
                @{{ currency.symbol + ' ' + currency.code }}
            </span>
        </div>
    </script>

    <!-- Locale Switcher Template -->
    <script type="text/x-template" id="v-locale-switcher-template">
        <div class="my-2.5 grid gap-1 overflow-auto max-md:my-0 sm:max-h-[500px]">
            <span
                class="flex cursor-pointer items-center gap-2.5 px-5 py-2 text-base hover:bg-gray-100"
                :class="{'bg-gray-100': locale.code == '{{ app()->getLocale() }}'}"
                v-for="locale in locales"
                @click="change(locale)"                  
            >
                <img
                    :src="locale.logo_url || '{{ bagisto_asset('images/default-language.svg') }}'"
                    width="24"
                    height="16"
                />

                @{{ locale.name }}
            </span>
        </div>
    </script>

    <!-- Vue Components -->
    <script type="module">
        app.component('v-topbar', {
            template: '#v-topbar-template',
            data() {
                return {
                    combinedToggler: false, // Combined dropdown toggler
                };
            },
        });

        app.component('v-currency-switcher', {
            template: '#v-currency-switcher-template',
            data() {
                return {
                    currencies: @json(core()->getCurrentChannel()->currencies),
                };
            },
            methods: {
                change(currency) {
                    let url = new URL(window.location.href);
                    url.searchParams.set('currency', currency.code);
                    window.location.href = url.href;
                }
            }
        });

        app.component('v-locale-switcher', {
            template: '#v-locale-switcher-template',
            data() {
                return {
                    locales: @json(core()->getCurrentChannel()->locales()->orderBy('name')->get()),
                };
            },
            methods: {
                change(locale) {
                    let url = new URL(window.location.href);
                    url.searchParams.set('locale', locale.code);
                    window.location.href = url.href;
                }
            }
        });
    </script>
@endPushOnce
