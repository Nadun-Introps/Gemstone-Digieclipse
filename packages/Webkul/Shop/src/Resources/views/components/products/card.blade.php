<v-product-card {{ $attributes }} :product="product">
</v-product-card>

@pushOnce('scripts')
    <script type="text/x-template" id="v-product-card-template">
    <li class="product-item col-lg-3 col-md-4 col-sm-6 col-xs-6 col-ts-12 style-1" v-if="mode != 'list'">
        <div class="product-inner equal-element">
            
            <!-- Top Badge Area -->
            <div class="product-top">
                <div class="flash">
                    <span class="onnew" v-if="product.is_new && !product.on_sale">
                        <span class="text">New</span>
                    </span>
                    <span class="onsale" v-if="product.on_sale">
                        <span class="text">Sale</span>
                    </span>
                </div>
            </div>

            <!-- Product Image -->
            <div class="product-thumb">
                <div class="thumb-inner ">
                    <a :href="`{{ route('shop.product_or_category.index', '') }}/${product.url_key}`">
                        <x-shop::media.images.lazy
                            class="transition-all duration-300 hover:scale-105 rounded-md"
                            ::src="product.base_image.medium_image_url"
                            ::key="product.id"
                            ::index="product.id"
                            width="250"
                            height="250"
                            ::alt="product.name"
                        />
                    </a>

                    <!-- Hover Actions -->
                    <div class="thumb-group">

                        <!-- Wishlist -->
                            @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                                <div class="yith-wcwl-add-to-wishlist">
                                    <div class="yith-wcwl-add-button">
                                        <a href="javascript:void(0)"
                                            :class="product.is_wishlist ? 'text-red-500' : 'text-white'"
                                            @click="addToWishlist()">
                                            <i :class="product.is_wishlist ? 'icon-heart-fill' : 'icon-heart'"></i>
                                            @{{ product.is_wishlist ? 'Wishlisted' : 'Add to Wishlist' }}
                                        </a>
                                    </div>
                                </div>
                            @endif

                        <!-- Quick View Placeholder -->
                        <a href="javascript:void(0)" class="button quick-view-button">
                            Quick View
                        </a>

                        <!-- Add to Cart -->
                        @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                            <div class="loop-form-add-to-cart">
                                <button 
                                    class="single_add_to_cart_button button"
                                    :disabled="! product.is_saleable || isAddingToCart"
                                    @click="addToCart()">
                                    @lang('shop::app.components.products.card.add-to-cart')
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="product-info">
                <h5 class="product-name product_title">
                    <a :href="`{{ route('shop.product_or_category.index', '') }}/${product.url_key}`" class="text-gray-800 hover:text-blue-600">
                        @{{ product.name }}
                    </a>
                </h5>

                <div class="group-info">
                    <!-- Ratings -->
                    <div class="stars-rating">
                        <template v-if="product.ratings.total">
                            <x-shop::products.ratings
                                ::average="product.ratings.average"
                                ::total="product.ratings.total"
                                ::rating="false"
                            />
                        </template>
                        <template v-else>
                            <span class="text-gray-400 text-sm">@lang('shop::app.components.products.card.review-description')</span>
                        </template>
                    </div>

                    <!-- Price -->
                    <div class="price">
                        <span v-html="product.price_html"></span>
                    </div>
                </div>
            </div>
        </div>
    </li>
</script>

    <script type="module">
        app.component('v-product-card', {
            template: '#v-product-card-template',

            props: ['mode', 'product'],

            data() {
                return {
                    isCustomer: '{{ auth()->guard('customer')->check() }}',

                    isAddingToCart: false,
                }
            },

            methods: {
                addToWishlist() {
                    if (this.isCustomer) {
                        this.$axios.post(`{{ route('shop.api.customers.account.wishlist.store') }}`, {
                                product_id: this.product.id
                            })
                            .then(response => {
                                this.product.is_wishlist = !this.product.is_wishlist;

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
                    /**
                     * This will handle for customers.
                     */
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

                    /**
                     * This will handle for guests.
                     */
                    let items = this.getStorageValue() ?? [];

                    if (items.length) {
                        if (!items.includes(productId)) {
                            items.push(productId);

                            localStorage.setItem('compare_items', JSON.stringify(items));

                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: "@lang('shop::app.components.products.card.add-to-compare-success')"
                            });
                        } else {
                            this.$emitter.emit('add-flash', {
                                type: 'warning',
                                message: "@lang('shop::app.components.products.card.already-in-compare')"
                            });
                        }
                    } else {
                        localStorage.setItem('compare_items', JSON.stringify([productId]));

                        this.$emitter.emit('add-flash', {
                            type: 'success',
                            message: "@lang('shop::app.components.products.card.add-to-compare-success')"
                        });

                    }
                },

                getStorageValue(key) {
                    let value = localStorage.getItem('compare_items');

                    if (!value) {
                        return [];
                    }

                    return JSON.parse(value);
                },

                addToCart() {
                    this.isAddingToCart = true;

                    this.$axios.post('{{ route('shop.api.checkout.cart.store') }}', {
                            'quantity': 1,
                            'product_id': this.product.id,
                        })
                        .then(response => {
                            if (response.data.message) {
                                this.$emitter.emit('update-mini-cart', response.data.data);

                                this.$emitter.emit('add-flash', {
                                    type: 'success',
                                    message: response.data.message
                                });
                            } else {
                                this.$emitter.emit('add-flash', {
                                    type: 'warning',
                                    message: response.data.data.message
                                });
                            }

                            this.isAddingToCart = false;
                        })
                        .catch(error => {
                            this.$emitter.emit('add-flash', {
                                type: 'error',
                                message: error.response.data.message
                            });

                            if (error.response.data.redirect_uri) {
                                window.location.href = error.response.data.redirect_uri;
                            }

                            this.isAddingToCart = false;
                        });
                },
            },
        });
    </script>
@endpushOnce
