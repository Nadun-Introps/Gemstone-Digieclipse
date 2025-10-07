{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.before') !!}

<!-- Main Header -->
<div class="container">
    <div class="main-header">
        <div class="row">
            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.before') !!}

            <div class="col-lg-4 col-sm-6 col-md-4 col-xs-7 col-ts-12 header-element">
                <div class="block-search-block">
                    <form class="form-search" action="{{ route('shop.search.index') }}" method="GET" role="search">
                        <div class="form-content">
                            <div class="inner">
                                <input type="text" name="query" value="{{ request('query') }}" class="input"
                                    placeholder="Search here"
                                    minlength="{{ core()->getConfigData('catalog.products.search.min_query_length') }}"
                                    maxlength="{{ core()->getConfigData('catalog.products.search.max_query_length') }}"
                                    aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.search-text')" pattern="[^\\]+" required>
                                <button class="btn-search" type="submit" aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.submit')">
                                    <span class="icon-search"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.after') !!}
            <div class="col-lg-4 col-sm-6 col-md-4 col-xs-5 col-ts-12">
                <div class="logo">
                    <a href="{{ route('shop.home.index') }}">
                        <img src="/test_assets/images/gem_logo.png" alt="logo">
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-sm-12 col-md-4 col-xs-12 col-ts-12">
                <div class="header-control">
                    <div class="block-minicart stelina-mini-cart block-header stelina-dropdown">
                        <a href="javascript:void(0);" class="shopcart-icon" data-stelina="stelina-dropdown">
                            Cart
                            <span class="count">
                                0
                            </span>
                        </a>
                        <div class="no-product stelina-submenu">
                            <p class="text">
                                You have
                                <span>
                                    0 item(s)
                                </span>
                                in your bag
                            </p>
                        </div>
                    </div>
                    <a class="menu-bar mobile-navigation menu-toggle" href="#">
                        <span></span>
                        <span></span>
                        <span></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="header-nav-container">
    <div class="container">
        <div class="header-nav-wapper main-menu-wapper">
            <div class="header-nav">
                <div class="container-wapper">
                    <ul class="stelina-clone-mobile-menu stelina-nav main-menu " id="menu-main-menu">
                        <li class="menu-item">
                            <a href="{{ route('shop.home.index') }}" class="stelina-menu-item-title" title="Home"
                                id="home">
                                Home
                            </a>
                            <span class="toggle-submenu"></span>
                        </li>
                        <li class="menu-item ">
                            <a href="{{ route('shop.search.index') }}?new=1&sort=created_at-desc&limit=10"
                                class="stelina-menu-item-title" title="Shop" id="shop">
                                Shop
                            </a>
                            <span class="toggle-submenu"></span>
                            <ul class="submenu"></ul>
                        </li>
                        <!--<li class="menu-item  menu-item-has-children item-megamenu">
                            <a href="#" class="stelina-menu-item-title" title="Pages">Pages</a>
                        </li>-->
                        <li class="menu-item">
                            <a href="inblog_right-siderbar.html" class="stelina-menu-item-title"
                                title="Blogs">Blogs</a>
                        </li>
                        <li class="menu-item">
                            <a href="page/about-us" class="stelina-menu-item-title" title="About">About</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--</header>-->
<div class="header-device-mobile">
    <div class="wapper">
        <div class="item mobile-logo">
            <div class="logo">
                <a href="#">
                    <img src="test_assets/images/Gem-logo-W.png" alt="img">
                </a>
            </div>
        </div>
        <div class="item item mobile-search-box has-sub">
            <a href="#">
                <span class="icon">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </span>
            </a>
            <div class="block-sub">
                <a href="#" class="close">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </a>
                <div class="header-searchform-box">
                    <form class="header-searchform">
                        <div class="searchform-wrap">
                            <input type="text" class="search-input" placeholder="Enter keywords to search...">
                            <input type="submit" class="submit button" value="Search">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="item mobile-settings-box has-sub">
            <a href="#">
                <span class="icon">
                    <i class="fa fa-cog" aria-hidden="true"></i>
                </span>
            </a>
            <div class="block-sub">
                <a href="#" class="close">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </a>
                <div class="block-sub-item">
                    <h5 class="block-item-title">Currency</h5>
                    <form class="currency-form stelina-language">
                        <ul class="stelina-language-wrap">
                            <li class="active">
                                <a href="#">
                                    <span>
                                        English (USD)
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span>
                                        French (EUR)
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span>
                                        Japanese (JPY)
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
        <div class="item menu-bar">
            <a class=" mobile-navigation  menu-toggle" href="#">
                <span></span>
                <span></span>
                <span></span>
            </a>
        </div>
    </div>
</div>

@pushOnce('scripts')
    <!-- Keep your v-desktop-category Vue component script -->
@endPushOnce

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.after') !!}
