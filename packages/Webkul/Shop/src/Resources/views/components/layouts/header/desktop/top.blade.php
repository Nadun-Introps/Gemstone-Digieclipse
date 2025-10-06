{!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.before') !!}

<v-topbar></v-topbar>

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.after') !!}

@pushOnce('scripts')
    <!-- Topbar Template -->
    <script type="text/x-template" id="v-topbar-template">
            <div class="top-bar">
                <div class="container">
                    
                    <!-- Left corner welcome text -->
                    <div class="top-bar-left">
                        <div class="header-message">
                            Welcome to our online store!
                        </div>
                    </div>

                    <!-- Right corner -->
                    <div class="top-bar-right">
                        
                        <!-- Combined Currency & Locale Dropdown -->
                        <div class="header-language">
                            <div class="stelina-language stelina-dropdown">
                                <x-shop::dropdown position="bottom-right">
                                    <x-slot:toggle>
                                        <a href="#" 
                                           class="active language-toggle"
                                           role="button"
                                           data-stelina="stelina-dropdown"
                                           tabindex="0"
                                           @click="combinedToggler = ! combinedToggler">
                                            
                                            <span>
                                                {{ core()->getCurrentCurrency()->symbol . ' ' . core()->getCurrentCurrencyCode() }}
                                                ({{ core()->getCurrentChannel()->locales()->orderBy('name')->where('code', app()->getLocale())->value('name') }})
                                            </span>
                                        </a>
                                    </x-slot>

                                    <!-- Dropdown Content -->
                                    <x-slot:content class="stelina-submenu">
                                        <li class="switcher-option">
                                            <v-currency-switcher></v-currency-switcher>
                                        </li>

                                        <li class="switcher-option">
                                            <v-locale-switcher></v-locale-switcher>
                                        </li>
                                    </x-slot>
                                </x-shop::dropdown>
                            </div>
                        </div>

                        <!-- User Links -->
                        <ul class="header-user-links">
                            @if(auth()->guard('customer')->check())
                                <li>
                                    <a href="{{ route('shop.customer.profile.index') }}">
                                        {{ auth()->guard('customer')->user()->first_name }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('shop.customer.session.destroy') }}">
                                        Logout
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('shop.customer.session.index') }}">
                                        Login or Register
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
    </script>

    <!-- Currency Switcher Template -->
    <script type="text/x-template" id="v-currency-switcher-template">
        <ul>
            <li v-for="currency in currencies"
                :class="{'active': currency.code == '{{ core()->getCurrentCurrencyCode() }}'}"
                class="switcher-option"
                @click="change(currency)">
                <a href="javascript:void(0)">
                    <span>@{{ currency.symbol + ' ' + currency.code }}</span>
                </a>
            </li>
        </ul>
    </script>

    <!-- Locale Switcher Template -->
    <script type="text/x-template" id="v-locale-switcher-template">
        <ul>
            <li v-for="locale in locales"
                :class="{'active': locale.code == '{{ app()->getLocale() }}'}"
                class="switcher-option"
                @click="change(locale)">
                <a href="javascript:void(0)">
                    <span>@{{ locale.name }}</span>
                </a>
            </li>
        </ul>
    </script>

    <!-- Vue Components -->
    <script type="module">
        app.component('v-topbar', {
            template: '#v-topbar-template',
            data() {
                return {
                    combinedToggler: false,
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
