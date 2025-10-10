@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

@php
    $avgRatings = $reviewHelper->getAverageRating($product);
    $percentageRatings = $reviewHelper->getPercentageRating($product);
    $customAttributeValues = $productViewHelper->getAdditionalData($product);
    $attributeData = collect($customAttributeValues)->filter(fn($item) => !empty($item['value']));
    $productBaseImage = product_image()->getProductBaseImage($product);
    $galleryImages =
        $product->images ??
        (isset($productBaseImage['large_image_url'])
            ? collect([
                [
                    'url' => $productBaseImage['large_image_url'],
                    'medium' => $productBaseImage['medium_image_url'],
                    'small' => $productBaseImage['small_image_url'],
                ],
            ])
            : collect());
@endphp

@push('meta')
    <meta name="description"
        content="{{ trim($product->meta_description) != '' ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '') }}" />
    <meta name="keywords" content="{{ $product->meta_keywords }}" />

    @if (core()->getConfigData('catalog.rich_snippets.products.enable'))
        <script type="application/ld+json">
            {!! app('Webkul\Product\Helpers\SEO')->getProductJsonLd($product) !!}
        </script>
    @endif

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $product->name }}" />
    <meta name="twitter:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />
    <meta name="twitter:image:alt" content="" />
    <meta name="twitter:image" content="{{ $productBaseImage['medium_image_url'] }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:title" content="{{ $product->name }}" />
    <meta property="og:image" content="{{ $productBaseImage['medium_image_url'] }}" />
    <meta property="og:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />
    <meta property="og:url" content="{{ route('shop.product_or_category.index', $product->url_key) }}" />
@endPush

<x-shop::layouts>
    <x-slot:title>
        {{ trim($product->meta_title) != '' ? $product->meta_title : $product->name }}
    </x-slot>

    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}

    @if (core()->getConfigData('general.general.breadcrumbs.shop'))
        <div class="d-none d-lg-block mb-3">
            <x-shop::breadcrumbs name="product" :entity="$product" />
        </div>
    @endif

    {{-- Vue product component mount point --}}
    <v-product>
        {{-- skeleton while Vue loads --}}
        <x-shop::shimmer.products.view />
    </v-product>

    {{-- Full UI inside Vue template so Vue has a consistent DOM to compile --}}
    @pushOnce('scripts')
        <script type="text/x-template" id="v-product-template">
            <x-shop::form v-slot="{ meta, errors, handleSubmit }" as="div">
                <form id="v-product-form" ref="formData" @submit="handleSubmit($event, addToCart)">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="is_buy_now" v-model="is_buy_now">

                    <div class="main-content main-content-details single no-sidebar">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="breadcrumb-trail breadcrumbs d-lg-none">
                                        {{-- optional mobile breadcrumb rendering (you can remove if duplicate) --}}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="content-area content-details full-width col-lg-9 col-md-8 col-sm-12 col-xs-12">
                                    <div class="site-main">
                                        <div class="details-product">
                                            {{-- Product Gallery --}}
                                            <div class="details-thumd">
                                                {{-- Main image preview --}}
                                                <div class="image-preview-container image-thick-box image_preview_container mb-3 text-center">
                                                    <img
                                                        id="img_zoom"
                                                        src="{{ $productBaseImage['large_image_url'] ?? ($galleryImages->first()['url'] ?? '') }}"
                                                        data-zoom-image="{{ $productBaseImage['large_image_url'] ?? ($galleryImages->first()['url'] ?? '') }}"
                                                        alt="{{ $product->name }}"
                                                        class="img-fluid"
                                                    />
                                                    <a href="#" class="btn-zoom open_qv">
                                                        <i class="fa fa-search" aria-hidden="true"></i>
                                                    </a>
                                                </div>

                                                {{-- Thumbnails Carousel --}}
                                                <div class="product-preview image-small product_preview">
                                                    <div
                                                        id="thumbnails"
                                                        class="thumbnails_carousel owl-carousel"
                                                        data-nav="true"
                                                        data-autoplay="false"
                                                        data-dots="false"
                                                        data-loop="false"
                                                        data-margin="10"
                                                        data-responsive='{"0":{"items":3},"480":{"items":3},"600":{"items":3},"1000":{"items":3}}'
                                                    >
                                                        @if ($galleryImages && count($galleryImages))
                                                            @foreach ($galleryImages as $img)
                                                                @php
                                                                    // Normalize image structure (Bagisto sometimes returns array or object)
                                                                    $large = $img->url ?? ($img['url'] ?? ($img->large ?? ($productBaseImage['large_image_url'] ?? '')));
                                                                    $small = $img->small ?? ($img['small'] ?? ($img['url'] ?? $large));
                                                                @endphp

                                                                <a
                                                                    href="#"
                                                                    data-image="{{ $large }}"
                                                                    data-zoom-image="{{ $large }}"
                                                                    class="{{ $loop->first ? 'active' : '' }}"
                                                                >
                                                                    <img src="{{ $small }}" data-large-image="{{ $large }}" alt="thumb-{{ $loop->index }}">
                                                                </a>
                                                            @endforeach
                                                        @else
                                                            {{-- fallback: single product image --}}
                                                            <a
                                                                href="#"
                                                                data-image="{{ $productBaseImage['large_image_url'] ?? '' }}"
                                                                data-zoom-image="{{ $productBaseImage['large_image_url'] ?? '' }}"
                                                                class="active"
                                                            >
                                                                <img
                                                                    src="{{ $productBaseImage['medium_image_url'] ?? ($productBaseImage['large_image_url'] ?? '') }}"
                                                                    alt="thumb"
                                                                >
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Right: Info --}}
                                            <div class="details-infor">
                                                <h1 class="product-title">{{ $product->name }}</h1>

                                                {{-- Ratings --}}
                                                @if ($totalRatings = $reviewHelper->getTotalFeedback($product))
                                                    <div class="stars-rating">
                                                        <div class="star-rating">
                                                            <x-shop::products.ratings :average="$avgRatings" :total="$totalRatings" ::rating="true" />
                                                        </div>
                                                        <div class="count-star">({{ $totalRatings }})</div>
                                                    </div>
                                                @endif

                                                {{-- Availability --}}
                                                <div class="availability">
                                                    availability:
                                                    <a href="#">{{ $product->haveSufficientQuantity(1) ? 'in Stock' : 'Out of Stock' }}</a>
                                                </div>

                                                {{-- Price --}}
                                                <div class="price">
                                                    <span>{!! $product->getTypeInstance()->getPriceHtml() !!}</span>
                                                </div>

                                                @if (\Webkul\Tax\Facades\Tax::isInclusiveTaxProductPrices())
                                                    <div class="text-muted small">
                                                        (@lang('shop::app.products.view.tax-inclusive'))
                                                    </div>
                                                @endif

                                                {{-- Short description --}}
                                                <div class="product-details-description">
                                                    <ul>
                                                        {!! $product->short_description ? "<li>{$product->short_description}</li>" : '' !!}
                                                    </ul>
                                                </div>

                                                {{-- Variations --}}
                                                <div class="variations">
                                                    @includeWhen(View::exists('shop::products.view.types.configurable'), 'shop::products.view.types.configurable')
                                                    @includeWhen(View::exists('shop::products.view.types.simple'), 'shop::products.view.types.simple')
                                                    @includeWhen(View::exists('shop::products.view.types.bundle'), 'shop::products.view.types.bundle')
                                                    @includeWhen(View::exists('shop::products.view.types.grouped'), 'shop::products.view.types.grouped')
                                                    @includeWhen(View::exists('shop::products.view.types.downloadable'), 'shop::products.view.types.downloadable')
                                                    @includeWhen(View::exists('shop::products.view.types.booking'), 'shop::products.view.types.booking')
                                                </div>

                                                {{-- Group buttons: wishlist (heart), size chart, qty + add to cart --}}
                                                <div class="group-button">
                                                   {{-- Wishlist --}}
                                                    @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                                                        <div class="yith-wcwl-add-to-wishlist">
                                                            <div class="yith-wcwl-add-button">
                                                                <a href="javascript:void(0)"
                                                                    class="flex items-center gap-2 border rounded-full px-3 py-2 transition-all duration-200"
                                                                    :class="isWishlist ? 'text-red-500 border-red-300 bg-red-50' : 'text-gray-700 border-gray-200 hover:text-black hover:border-gray-400'"
                                                                    @click="addToWishlist()">
                                                                    <span>@{{ isWishlist ? 'Wishlisted' : 'Add to Wishlist' }}</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    {{-- Size chart --}}
                                                    <div class="size-chart-wrapp">
                                                        <div class="btn-size-chart">
                                                            @if (isset($product->size_chart) && $product->size_chart)
                                                                <a id="size_chart" href="{{ Storage::url($product->size_chart) }}" class="fancybox">View Size Chart</a>
                                                            @else
                                                                <a id="size_chart" href="#" class="fancybox">View Size Chart</a>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Quantity + Add to cart --}}
                                                    <div class="quantity-add-to-cart">
                                                        <div class="quantity">
                                                            <div class="control">
                                                                @if ($product->getTypeInstance()->showQuantityBox())
                                                                    <a class="btn-number qtyminus quantity-minus" href="#">-</a>
                                                                    <input type="text" name="quantity" data-step="1" data-min="0" value="1" title="Qty" class="input-qty qty" size="4" />
                                                                    <a href="#" class="btn-number qtyplus quantity-plus">+</a>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                                                            <button type="submit" class="single_add_to_cart_button button">@lang('shop::app.products.view.add-to-cart')</button>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Buy Now / Compare --}}
                                                {{-- <div class="mt-3">
                                                    @if (core()->getConfigData('catalog.products.storefront.buy_now_button_display'))
                                                        <button type="submit" class="btn btn-success" @click="is_buy_now=1">Buy Now</button>
                                                    @endif

                                                    <a href="javascript:void(0)" class="btn btn-outline-secondary" @click="addToCompare({{ $product->id }})">@lang('shop::app.products.view.compare')</a>
                                                </div> --}}
                                            </div> {{-- .details-infor --}}
                                        </div> {{-- .details-product --}}

                                        {{-- Tabs (your UI) --}}
                                        <div class="tab-details-product">
                                            <ul class="tab-link">
                                                <li class="active">
                                                    <a data-toggle="tab" aria-expanded="true" href="#product-descriptions">Descriptions</a>
                                                </li>
                                                @if (count($attributeData))
                                                    <li>
                                                        <a data-toggle="tab" aria-expanded="true" href="#information">Information</a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a data-toggle="tab" aria-expanded="true" href="#reviews">Reviews</a>
                                                </li>
                                            </ul>

                                            <div class="tab-container">
                                                <div id="product-descriptions" class="tab-panel active">
                                                    {!! $product->description !!}
                                                </div>

                                                @if (count($attributeData))
                                                    <div id="information" class="tab-panel">
                                                        <table class="table table-bordered">
                                                            @foreach ($customAttributeValues as $customAttributeValue)
                                                                @if (!empty($customAttributeValue['value']))
                                                                    <tr>
                                                                        <td>{{ $customAttributeValue['label'] }}</td>
                                                                        <td>
                                                                            @if ($customAttributeValue['type'] == 'file')
                                                                                <a href="{{ Storage::url($product[$customAttributeValue['code']]) }}" download="{{ $customAttributeValue['label'] }}">
                                                                                    {{ $customAttributeValue['value'] }}
                                                                                </a>
                                                                            @elseif ($customAttributeValue['type'] == 'image')
                                                                                <a href="{{ Storage::url($product[$customAttributeValue['code']]) }}" download="{{ $customAttributeValue['label'] }}">
                                                                                    <img src="{{ Storage::url($customAttributeValue['value']) }}" alt="{{ $customAttributeValue['label'] }}" style="height:30px;">
                                                                                </a>
                                                                            @else
                                                                                {{ $customAttributeValue['value'] ?? '-' }}
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                @endif

                                                <div id="reviews" class="tab-panel">
                                                    @include('shop::products.view.reviews')
                                                </div>
                                            </div>
                                        </div> {{-- .tab-details-product --}}
                                    </div> {{-- .site-main --}}
                                </div> {{-- .content-area --}}
                            </div> {{-- .row --}}
                        </div> {{-- .container --}}
                    </div> {{-- .main-content --}}
                </form>
            </x-shop::form>
        </script>

        {{-- Vue component registration (keeps your logic) --}}
        <script type="module">
            app.component('v-product', {
                template: '#v-product-template',

                data() {
                    return {
                        isWishlist: Boolean(
                            "{{ (bool) auth()->guard()->user()?->wishlist_items->where('channel_id', core()->getCurrentChannel()->id)->where('product_id', $product->id)->count() }}"
                        ),
                        isCustomer: '{{ auth()->guard('customer')->check() }}',
                        is_buy_now: 0,
                        isStoring: {
                            addToCart: false,
                            buyNow: false,
                        },
                    }
                },

                methods: {
                    addToCart(params) {
                        const operation = this.is_buy_now ? 'buyNow' : 'addToCart';
                        this.isStoring[operation] = true;

                        let formData = new FormData(this.$refs.formData || document.getElementById('v-product-form'));

                        // ensure quantity
                        if (!formData.has('quantity')) {
                            const qtyInput = document.querySelector('input[name="quantity"]');
                            const qty = qtyInput ? qtyInput.value : 1;
                            formData.append('quantity', qty);
                        }

                        this.$axios.post('{{ route('shop.api.checkout.cart.store') }}', formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then(response => {
                                if (response.data.message) {
                                    this.$emitter.emit('update-mini-cart', response.data.data);
                                    this.$emitter.emit('add-flash', {
                                        type: 'success',
                                        message: response.data.message
                                    });

                                    if (response.data.redirect) {
                                        window.location.href = response.data.redirect;
                                    }
                                } else {
                                    this.$emitter.emit('add-flash', {
                                        type: 'warning',
                                        message: response.data.data.message
                                    });
                                }

                                this.isStoring[operation] = false;
                            })
                            .catch(error => {
                                this.isStoring[operation] = false;

                                this.$emitter.emit('add-flash', {
                                    type: 'warning',
                                    message: error?.response?.data?.message ?? 'Something went wrong'
                                });
                            });
                    },

                    addToWishlist() {
                        if (this.isCustomer) {
                            this.$axios.post('{{ route('shop.api.customers.account.wishlist.store') }}', {
                                    product_id: "{{ $product->id }}"
                                })
                                .then(response => {
                                    this.isWishlist = !this.isWishlist;

                                    this.$emitter.emit('add-flash', {
                                        type: 'success',
                                        message: response.data.data.message
                                    });
                                })
                                .catch(error => {});
                        } else {
                            window.location.href = "{{ route('shop.customer.session.index') }}";
                        }
                    },

                    addToCompare(productId) {
                        if (this.isCustomer) {
                            this.$axios.post('{{ route('shop.api.compare.store') }}', {
                                    'product_id': productId
                                })
                                .then(response => {
                                    this.$emitter.emit('add-flash', {
                                        type: 'success',
                                        message: response.data.data.message
                                    });
                                })
                                .catch(error => {
                                    if ([400, 422].includes(error.response.status)) {
                                        this.$emitter.emit('add-flash', {
                                            type: 'warning',
                                            message: error.response.data.data.message
                                        });
                                        return;
                                    }
                                    this.$emitter.emit('add-flash', {
                                        type: 'error',
                                        message: error.response.data.message
                                    });
                                });

                            return;
                        }

                        let existingItems = this.getStorageValue(this.getCompareItemsStorageKey()) ?? [];

                        if (existingItems.length) {
                            if (!existingItems.includes(productId)) {
                                existingItems.push(productId);
                                this.setStorageValue(this.getCompareItemsStorageKey(), existingItems);
                                this.$emitter.emit('add-flash', {
                                    type: 'success',
                                    message: "@lang('shop::app.products.view.add-to-compare')"
                                });
                            } else {
                                this.$emitter.emit('add-flash', {
                                    type: 'warning',
                                    message: "@lang('shop::app.products.view.already-in-compare')"
                                });
                            }
                        } else {
                            this.setStorageValue(this.getCompareItemsStorageKey(), [productId]);
                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: "@lang('shop::app.products.view.add-to-compare')"
                            });
                        }
                    },

                    updateQty(quantity, id) {
                        this.isLoading = true;
                        let qty = {};
                        qty[id] = quantity;

                        this.$axios.put('{{ route('shop.api.checkout.cart.update') }}', {
                                qty
                            })
                            .then(response => {
                                if (response.data.message) {
                                    this.cart = response.data.data;
                                } else {
                                    this.$emitter.emit('add-flash', {
                                        type: 'warning',
                                        message: response.data.data.message
                                    });
                                }

                                this.isLoading = false;
                            }).catch(error => this.isLoading = false);
                    },

                    getCompareItemsStorageKey() {
                        return 'compare_items';
                    },

                    setStorageValue(key, value) {
                        localStorage.setItem(key, JSON.stringify(value));
                    },

                    getStorageValue(key) {
                        let value = localStorage.getItem(key);
                        if (value) value = JSON.parse(value);
                        return value;
                    },

                    scrollToReview() {
                        let accordianElement = document.querySelector('#review-accordian-button');
                        if (accordianElement) {
                            accordianElement.click();
                            accordianElement.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }

                        let tabElement = document.querySelector('#review-tab-button');
                        if (tabElement) {
                            tabElement.click();
                            tabElement.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }
                    },

                    ensureQuantity(formData) {
                        if (!formData.has('quantity')) {
                            formData.append('quantity', 1);
                        }
                    },
                },
            });
        </script>

        {{-- Owl thumbnails + tabs + qty handlers --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const $thumbnails = jQuery('#thumbnails');

                if ($thumbnails && $thumbnails.length && typeof $thumbnails.owlCarousel === 'function') {
                    $thumbnails.owlCarousel({
                        nav: true,
                        dots: false,
                        autoplay: false,
                        loop: false,
                        margin: 10,
                        responsive: {
                            0: {
                                items: 3
                            },
                            480: {
                                items: 3
                            },
                            600: {
                                items: 3
                            },
                            1000: {
                                items: 3
                            }
                        }
                    });

                    $thumbnails.on('click', 'a', function(e) {
                        e.preventDefault();
                        const $a = jQuery(this);
                        const image = $a.data('image') || $a.find('img').data('large-image');
                        const zoomImage = $a.data('zoom-image') || image;

                        if (image) {
                            jQuery('#img_zoom').attr('src', image).attr('data-zoom-image', zoomImage);
                        }

                        $thumbnails.find('a').removeClass('active');
                        $a.addClass('active');
                    });
                }

                // tabs (UI-only switching)
                jQuery('.tab-link a').on('click', function(e) {
                    e.preventDefault();
                    jQuery('.tab-link a').removeClass('active');
                    jQuery(this).addClass('active');

                    const target = jQuery(this).attr('href');
                    jQuery('.tab-panel').removeClass('active show');
                    jQuery(target).addClass('active show');
                });

                // qty buttons
                jQuery(document).on('click', '.qtyplus', function(e) {
                    e.preventDefault();
                    let $input = jQuery(this).siblings('input.qty');
                    if (!$input.length) $input = jQuery(this).closest('.quantity').find('input.qty');
                    let val = parseInt($input.val() || 0);
                    const step = parseInt($input.data('step') || 1);
                    $input.val(val + step).trigger('change');
                });

                jQuery(document).on('click', '.qtyminus', function(e) {
                    e.preventDefault();
                    let $input = jQuery(this).siblings('input.qty');
                    if (!$input.length) $input = jQuery(this).closest('.quantity').find('input.qty');
                    let val = parseInt($input.val() || 0);
                    const step = parseInt($input.data('step') || 1);
                    const min = parseInt($input.data('min') || 0);
                    val = Math.max(min, val - step);
                    $input.val(val).trigger('change');
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const $thumbnails = jQuery('#thumbnails');

                if ($thumbnails && $thumbnails.length && typeof $thumbnails.owlCarousel === 'function') {
                    $thumbnails.owlCarousel({
                        nav: true,
                        dots: false,
                        autoplay: false,
                        loop: false,
                        margin: 10,
                        responsive: {
                            0: {
                                items: 3
                            },
                            480: {
                                items: 3
                            },
                            600: {
                                items: 3
                            },
                            1000: {
                                items: 3
                            }
                        }
                    });

                    // Click to change main image
                    $thumbnails.on('click', 'a', function(e) {
                        e.preventDefault();
                        const $a = jQuery(this);
                        const image = $a.data('image') || $a.find('img').data('large-image');
                        const zoomImage = $a.data('zoom-image') || image;

                        if (image) {
                            jQuery('#img_zoom')
                                .attr('src', image)
                                .attr('data-zoom-image', zoomImage);
                        }

                        $thumbnails.find('a').removeClass('active');
                        $a.addClass('active');
                    });
                }
            });
        </script>
    @endPushOnce

    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}
</x-shop::layouts>
