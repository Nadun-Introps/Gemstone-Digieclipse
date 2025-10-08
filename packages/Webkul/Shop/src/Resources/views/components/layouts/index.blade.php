@props([
    'hasHeader' => true,
    'hasFeature' => true,
    'hasFooter' => true,
])

<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}" dir="{{ core()->getCurrentLocale()->direction }}">

<head>

    {!! view_render_event('bagisto.shop.layout.head.before') !!}

    <title>{{ $title ?? '' }}</title>

    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-language" content="{{ app()->getLocale() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="base-url" content="{{ url()->to('/') }}">
    <meta name="currency" content="{{ core()->getCurrentCurrency()->toJson() }}">

    @stack('meta')

    <link rel="icon" sizes="16x16"
        href="{{ core()->getCurrentChannel()->favicon_url ?? bagisto_asset('images/favicon.ico') }}" />

    @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])

    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        as="style">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="/test_assets/css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/x-icon" href="test_assets/images/favicon.png" />
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('test_assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('test_assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('test_assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('test_assets/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('test_assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('test_assets/css/chosen.min.css') }}">
    <link rel="stylesheet" href="{{ asset('test_assets/css/pe-icon-7-stroke.css') }}">
    <link rel="stylesheet" href="{{ asset('test_assets/css/magnific-popup.min.css') }}">
    <link rel="stylesheet" href="{{ asset('test_assets/css/lightbox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('test_assets/js/fancybox/source/jquery.fancybox.css') }}">
    <link rel="stylesheet" href="{{ asset('test_assets/css/jquery.scrollbar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('test_assets/css/mobile-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('test_assets/fonts/flaticon/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('test_assets/css/style2.css') }}">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap" as="style">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap">

    <script src="https://kit.fontawesome.com/750e98817f.js" crossorigin="anonymous"></script>

    <!-- Swiper CSS
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" /> -->

    @stack('styles')

    <style>
        {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
    </style>

    @if (core()->getConfigData('general.content.speculation_rules.enabled'))
        <script type="speculationrules">
                @json(core()->getSpeculationRules(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            </script>
    @endif

    {!! view_render_event('bagisto.shop.layout.head.after') !!}

</head>

<body class="home">
    {!! view_render_event('bagisto.shop.layout.body.before') !!}

    <a href="#main" class="skip-to-main-content-link">
        Skip to main content
    </a>

    <div id="app">
        <!-- Flash Message Blade Component -->
        <x-shop::flash-group />

        <!-- Confirm Modal Blade Component -->
        <x-shop::modal.confirm />

        <!-- Page Header Blade Component -->
        @if ($hasHeader)
            <x-shop::layouts.header />
        @endif

        @if (core()->getConfigData('general.gdpr.settings.enabled') && core()->getConfigData('general.gdpr.cookie.enabled'))
            <x-shop::layouts.cookie />
        @endif

        {!! view_render_event('bagisto.shop.layout.content.before') !!}

        <!-- Page Content Blade Component -->
        <main id="main" class="bg-white">
            {{ $slot }}
        </main>

        {!! view_render_event('bagisto.shop.layout.content.after') !!}


        <!-- Page Services Blade Component -->
        @if ($hasFeature)
            <x-shop::layouts.services />
        @endif

        <!-- Page Footer Blade Component -->
        @if ($hasFooter)
            <x-shop::layouts.footer />
        @endif
    </div>

    {!! view_render_event('bagisto.shop.layout.body.after') !!}

    @stack('scripts')

    {!! view_render_event('bagisto.shop.layout.vue-app-mount.before') !!}
    <script>
        /**
         * Load event, the purpose of using the event is to mount the application
         * after all of our `Vue` components which is present in blade file have
         * been registered in the app. No matter what `app.mount()` should be
         * called in the last.
         */
        window.addEventListener("load", function(event) {
            app.mount("#app");
        });
    </script>

    {!! view_render_event('bagisto.shop.layout.vue-app-mount.after') !!}

    <script type="text/javascript">
        {!! core()->getConfigData('general.content.custom_scripts.custom_javascript') !!}
    </script>
    <script src="{{ asset('test_assets/js/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('test_assets/js/jquery.plugin-countdown.min.js') }}"></script>
    <script src="{{ asset('test_assets/js/jquery-countdown.min.js') }}"></script>
    <script src="{{ asset('test_assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('test_assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('test_assets/js/magnific-popup.min.js') }}"></script>
    <script src="{{ asset('test_assets/js/isotope.min.js') }}"></script>
    <script src="{{ asset('test_assets/js/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('test_assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('test_assets/js/mobile-menu.js') }}"></script>
    <script src="{{ asset('test_assets/js/chosen.min.js') }}"></script>
    <script src="{{ asset('test_assets/js/slick.js') }}"></script>
    <script src="{{ asset('test_assets/js/jquery.elevateZoom.min.js') }}"></script>
    <script src="{{ asset('test_assets/js/jquery.actual.min.js') }}"></script>
    <script src="{{ asset('test_assets/js/fancybox/source/jquery.fancybox.js') }}"></script>
    <script src="{{ asset('test_assets/js/lightbox.min.js') }}"></script>
    <script src="{{ asset('test_assets/js/owl.thumbs.min.js') }}"></script>
    <script src="{{ asset('test_assets/js/frontend-plugin.js') }}"></script>
    <!-- Swiper JS -->
    <!---<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!--<script>
        document.addEventListener('DOMContentLoaded', () => {
            new Swiper('.swiper', {
                slidesPerView: 1,
                spaceBetween: 15,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        });
    </script>-->
</body>

</html>
