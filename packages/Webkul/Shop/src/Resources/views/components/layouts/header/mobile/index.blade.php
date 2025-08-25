<!--
    This code needs to be refactored to reduce the amount of PHP in the Blade
    template as much as possible.
-->
@php
    $showCompare = (bool) core()->getConfigData('catalog.products.settings.compare_option');

    $showWishlist = (bool) core()->getConfigData('customer.settings.wishlist.wishlist_option');
@endphp

<div class="flex flex-wrap gap-4 px-4 pb-4 pt-6 shadow-sm lg:hidden">
    <div class="flex w-full items-center justify-between">
        <!-- Left Navigation -->
        <div class="flex items-center gap-x-1.5">
            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.before') !!}

            <!-- Drawer -->
            <v-mobile-drawer></v-mobile-drawer>

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.logo.before') !!}

            <a
                href="{{ route('shop.home.index') }}"
                class="max-h-[30px]"
                aria-label="@lang('shop::app.components.layouts.header.mobile.bagisto')"
            >
                <img
                    src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                    alt="{{ config('app.name') }}"
                    width="131"
                    height="29"
                >
            </a>

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.logo.after') !!}
        </div>

        <!-- Right Navigation -->
        <div>
            <div class="flex items-center gap-x-5 max-md:gap-x-4">
                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.compare.before') !!}

                @if($showCompare)
                    <a
                        href="{{ route('shop.compare.index') }}"
                        aria-label="@lang('shop::app.components.layouts.header.mobile.compare')"
                    >
                        <span class="icon-heart cursor-pointer text-2xl"></span>
                    </a>
                @endif

                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.compare.after') !!}

                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.mini_cart.before') !!}

                @if(core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                    @include('shop::checkout.cart.mini-cart')
                @endif

                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.mini_cart.after') !!}

                <!-- For Large screens -->
                <div class="max-md:hidden">
                    <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                        <x-slot:toggle>
                            <span class="icon-users cursor-pointer text-2xl"></span>
                        </x-slot>

                        <!-- Guest Dropdown -->
                        @guest('customer')
                            <x-slot:content>
                                <div class="grid gap-2.5">
                                    <p class="font-dmserif text-xl">
                                        @lang('shop::app.components.layouts.header.mobile.welcome-guest')
                                    </p>

                                    <p class="text-sm">
                                        @lang('shop::app.components.layouts.header.mobile.dropdown-text')
                                    </p>
                                </div>

                                <p class="mt-3 w-full border border-zinc-200"></p>

                                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.customers_action.before') !!}

                                <div class="mt-6 flex gap-4">
                                    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.sign_in_button.before') !!}

                                    <a
                                        href="{{ route('shop.customer.session.create') }}"
                                        class="m-0 mx-auto block w-max cursor-pointer rounded-2xl bg-navyBlue px-7 py-4 text-center text-base font-medium text-white ltr:ml-0 rtl:mr-0"
                                    >
                                        @lang('shop::app.components.layouts.header.mobile.sign-in')
                                    </a>

                                    <a
                                        href="{{ route('shop.customers.register.index') }}"
                                        class="m-0 mx-auto block w-max cursor-pointer rounded-2xl border-2 border-navyBlue bg-white px-7 py-3.5 text-center text-base font-medium text-navyBlue ltr:ml-0 rtl:mr-0"
                                    >
                                        @lang('shop::app.components.layouts.header.mobile.sign-up')
                                    </a>

                                    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.sign_in_button.after') !!}
                                </div>

                                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.customers_action.after') !!}
                            </x-slot>
                        @endguest

                        <!-- Customers Dropdown -->
                        @auth('customer')
                            <x-slot:content class="!p-0">
                                <div class="grid gap-2.5 p-5 pb-0">
                                    <p class="font-dmserif text-xl">
                                        @lang('shop::app.components.layouts.header.mobile.welcome')’
                                        {{ auth()->guard('customer')->user()->first_name }}
                                    </p>

                                    <p class="text-sm">
                                        @lang('shop::app.components.layouts.header.mobile.dropdown-text')
                                    </p>
                                </div>

                                <p class="mt-3 w-full border border-zinc-200"></p>

                                <div class="mt-2.5 grid gap-1 pb-2.5">
                                    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.profile_dropdown.links.before') !!}

                                    <a
                                        class="cursor-pointer px-5 py-2 text-base"
                                        href="{{ route('shop.customers.account.profile.index') }}"
                                    >
                                        @lang('shop::app.components.layouts.header.mobile.profile')
                                    </a>

                                    <a
                                        class="cursor-pointer px-5 py-2 text-base"
                                        href="{{ route('shop.customers.account.orders.index') }}"
                                    >
                                        @lang('shop::app.components.layouts.header.mobile.orders')
                                    </a>

                                    @if ($showWishlist)
                                        <a
                                            class="cursor-pointer px-5 py-2 text-base"
                                            href="{{ route('shop.customers.account.wishlist.index') }}"
                                        >
                                            @lang('shop::app.components.layouts.header.mobile.wishlist')
                                        </a>
                                    @endif

                                    <!--Customers logout-->
                                    @auth('customer')
                                        <x-shop::form
                                            method="DELETE"
                                            action="{{ route('shop.customer.session.destroy') }}"
                                            id="customerLogout"
                                        />

                                        <a
                                            class="cursor-pointer px-5 py-2 text-base"
                                            href="{{ route('shop.customer.session.destroy') }}"
                                            onclick="event.preventDefault(); document.getElementById('customerLogout').submit();"
                                        >
                                            @lang('shop::app.components.layouts.header.mobile.logout')
                                        </a>
                                    @endauth

                                    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.profile_dropdown.links.after') !!}
                                </div>
                            </x-slot>
                        @endauth
                    </x-shop::dropdown>
                </div>

                <!-- For Medium and small screen -->
                <div class="md:hidden">
                    @guest('customer')
                        <a
                            href="{{ route('shop.customer.session.create') }}"
                            aria-label="@lang('shop::app.components.layouts.header.mobile.account')"
                        >
                            <span class="icon-users cursor-pointer text-2xl"></span>
                        </a>
                    @endguest

                    <!-- Customers Dropdown -->
                    @auth('customer')
                        <a
                            href="{{ route('shop.customers.account.index') }}"
                            aria-label="@lang('shop::app.components.layouts.header.mobile.account')"
                        >
                            <span class="icon-users cursor-pointer text-2xl"></span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.search.before') !!}

    <!-- Serach Catalog Form -->
    <form
            action="{{ route('shop.search.index') }}"
            class="flex max-w-[445px] items-center"
            role="search"
            style="margin-left: 20px;"
        >
            <label
                for="organic-search"
                class="sr-only"
            >
                @lang('shop::app.components.layouts.header.desktop.bottom.search')
            </label>

            <!-- Search input -->
            <input
                type="text"
                name="query"
                value="{{ request('query') }}"
                class="block w-full rounded-lg border border-transparent bg-zinc-100 pl-3 pr-11 py-3 text-xs font-medium text-gray-900 placeholder-black transition-all hover:border-gray-400 focus:border-gray-400"
                minlength="{{ core()->getConfigData('catalog.products.search.min_query_length') }}"
                maxlength="{{ core()->getConfigData('catalog.products.search.max_query_length') }}"
                placeholder="Search..."
                aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.search-text')"
                aria-required="true"
                pattern="[^\\]+"
                required
                style="height: 28px;padding: ;margin: 10px;margin-left:20px; 10px;padding-left: 11px;border-radius: 0;"
            >

            <!-- Transparent submit button with icon on right -->
            <button
                type="submit"
                class="top-2.5 right-3 flex items-center text-xl text-gray-500 hover:text-gray-700 bg-transparent border-0 p-0 cursor-pointer"
                aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.submit')"
            >
                <div class="icon-search"></div>
            </button>
        </form>

    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.search.after') !!}
</div>

@pushOnce('scripts')
<script type="text/x-template" id="v-mobile-drawer-template">
    <x-shop::drawer position="left" width="100%" @close="onDrawerClose">
        <x-slot:toggle>
            <span class="icon-hamburger cursor-pointer text-2xl"></span>
        </x-slot>

        <x-slot:header>
            <div class="flex items-center justify-between px-4 py-2">
                <a href="{{ route('shop.home.index') }}">
                    <img src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}" 
                         alt="{{ config('app.name') }}" width="131" height="29">
                </a>
            </div>
        </x-slot>

        <x-slot:content class="!p-0">
            <!-- Account / Profile -->
            <div class="border-b border-zinc-200 p-4">
                <div class="grid grid-cols-[auto_1fr] items-center gap-4 rounded-xl border border-zinc-200 p-2.5">
                    <div>
                        <img src="{{ auth()->user()?->image_url ?? bagisto_asset('images/user-placeholder.png') }}" 
                             class="h-[60px] w-[60px] rounded-full">
                    </div>

                    @guest('customer')
                        <a href="{{ route('shop.customer.session.create') }}" class="text-base font-medium">
                            @lang('shop::app.components.layouts.header.mobile.login')
                        </a>
                    @endguest

                    @auth('customer')
                        <div class="flex flex-col justify-between">
                            <p class="font-medium text-xl">Hello! {{ auth()->user()?->first_name }}</p>
                            <p class="text-zinc-500 text-sm">{{ auth()->user()?->email }}</p>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Static category links -->
            <div class="flex flex-col py-4">
                <a href="{{ url('/') }}" class="px-6 py-3 hover:bg-gray-100 text-base font-medium text-black">Home</a>
                <a href="{{ url('/shop') }}" class="px-6 py-3 hover:bg-gray-100 text-base font-medium text-black">Shop</a>
                <a href="{{ url('/about-us') }}" class="px-6 py-3 hover:bg-gray-100 text-base font-medium text-black">About Us</a>
                <a href="{{ url('/contact-us') }}" class="px-6 py-3 hover:bg-gray-100 text-base font-medium text-black">Contact Us</a>
                <a href="{{ url('/blog') }}" class="px-6 py-3 hover:bg-gray-100 text-base font-medium text-black">Blog</a>
            </div>
        </x-slot>

        <!-- Footer for currency & locale (unchanged) -->
        <x-slot:footer>
            @if(core()->getCurrentChannel()->locales()->count() > 1 || core()->getCurrentChannel()->currencies()->count() > 1)
                <div class="fixed bottom-0 grid w-full grid-cols-[1fr_auto_1fr] items-center border-t border-zinc-200 bg-white px-5">
                    <!-- Currency Drawer -->
                    <x-shop::drawer position="bottom" width="100%">
                        <x-slot:toggle>
                            <div class="flex cursor-pointer items-center px-2.5 py-3.5 text-lg font-medium">
                                {{ core()->getCurrentCurrency()->symbol . ' ' . core()->getCurrentCurrencyCode() }}
                            </div>
                        </x-slot>
                        <x-slot:header>
                            <p class="text-lg font-semibold">@lang('shop::app.components.layouts.header.mobile.currencies')</p>
                        </x-slot>
                        <x-slot:content class="!px-0">
                            <v-currency-switcher></v-currency-switcher>
                        </x-slot>
                    </x-shop::drawer>

                    <span class="h-5 w-0.5 bg-zinc-200"></span>

                    <!-- Locale Drawer -->
                    <x-shop::drawer position="bottom" width="100%">
                        <x-slot:toggle>
                            <div class="flex cursor-pointer items-center px-2.5 py-3.5 text-lg font-medium">
                                <img src="{{ core()->getCurrentLocale()->logo_url ?? bagisto_asset('images/default-language.svg') }}" 
                                     alt="Locale" width="24" height="16" />
                                {{ core()->getCurrentChannel()->locales()->where('code', app()->getLocale())->value('name') }}
                            </div>
                        </x-slot>
                        <x-slot:header>
                            <p class="text-lg font-semibold">@lang('shop::app.components.layouts.header.mobile.locales')</p>
                        </x-slot>
                        <x-slot:content class="!px-0">
                            <v-locale-switcher></v-locale-switcher>
                        </x-slot>
                    </x-shop::drawer>
                </div>
            @endif
        </x-slot>
    </x-shop::drawer>
</script>

    <script
        type="text/x-template"
        id="v-mobile-category-template"
    >
        <!-- Wrapper with transition effects -->
        <div class="relative h-full overflow-hidden">
            <!-- Sliding container -->
            <div
                class="flex h-full transition-transform duration-300"
                :class="{
                    'ltr:translate-x-0 rtl:translate-x-0': currentViewLevel !== 'third',
                    'ltr:-translate-x-full rtl:translate-x-full': currentViewLevel === 'third'
                }"
            >
                <!-- First level view -->
                <div class="h-full w-full flex-shrink-0 overflow-auto px-6">
                    <div class="py-4">
                        <div
                            v-for="category in categories"
                            :key="category.id"
                            :class="{'mb-2': category.children && category.children.length}"
                        >
                            <div class="flex cursor-pointer items-center justify-between py-2 transition-colors duration-200">
                                <a :href="category.url" class="text-base font-medium text-black">
                                    @{{ category.name }}
                                </a>
                            </div>

                            <!-- Second Level Categories -->
                            <div v-if="category.children && category.children.length" >
                                <div
                                    v-for="secondLevelCategory in category.children"
                                    :key="secondLevelCategory.id"
                                >
                                    <div
                                        class="flex cursor-pointer items-center justify-between py-2 transition-colors duration-200"
                                        @click="showThirdLevel(secondLevelCategory, category, $event)"
                                    >
                                        <a :href="secondLevelCategory.url" class="text-sm font-normal">
                                            @{{ secondLevelCategory.name }}
                                        </a>

                                        <span
                                            v-if="secondLevelCategory.children && secondLevelCategory.children.length"
                                            class="icon-arrow-right rtl:icon-arrow-left"
                                        ></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Third level view -->
                <div
                    class="h-full w-full flex-shrink-0"
                    v-if="currentViewLevel === 'third'"
                >
                    <div class="border-b border-gray-200 px-6 py-4">
                        <button
                            @click="goBackToMainView"
                            class="flex items-center justify-center gap-2 focus:outline-none"
                            aria-label="Go back"
                        >
                            <span class="icon-arrow-left rtl:icon-arrow-right text-lg"></span>
                            <div class="text-base font-medium text-black">
                                @lang('shop::app.components.layouts.header.mobile.back-button')
                            </div>
                        </button>
                    </div>

                    <!-- Third Level Content -->
                    <div class="px-6 py-4">
                        <div
                            v-for="thirdLevelCategory in currentSecondLevelCategory?.children"
                            :key="thirdLevelCategory.id"
                            class="mb-2"
                        >
                            <a
                                :href="thirdLevelCategory.url"
                                class="block py-2 text-sm transition-colors duration-200"
                            >
                                @{{ thirdLevelCategory.name }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
    app.component('v-mobile-drawer', {
        template: '#v-mobile-drawer-template',

        data() {
            return {
                isDrawerActive: false
            };
        },

        methods: {
            onDrawerClose() {
                // Drawer close logic if needed
            }
        }
    });
</script>
@endPushOnce
