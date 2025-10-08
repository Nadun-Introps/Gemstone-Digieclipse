@pushOnce('scripts')
    <script type="text/x-template" id="v-mini-cart-template">
    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.before') !!}

    @if (core()->getConfigData('sales.checkout.mini_cart.display_mini_cart'))
    <div class="block-minicart stelina-mini-cart block-header stelina-dropdown">
        <!-- Cart Icon -->
        <a href="javascript:void(0);" class="shopcart-icon" data-stelina="stelina-dropdown">
            Cart
            <span class="count">@{{ cart?.items_count ?? 0 }}</span>
        </a>

        <!-- Cart Content (when items exist) -->
        <div class="shopcart-description stelina-submenu" v-if="cart && cart.items?.length">
            <div class="content-wrap">
                <h3 class="title">Shopping Cart</h3>

                <ul class="minicart-items">
                    <li class="product-cart mini_cart_item" v-for="item in cart.items" :key="item.id">
                        <a :href="`{{ route('shop.product_or_category.index', '') }}/${item.product_url_key}`" class="product-media">
                            <img :src="item.base_image.small_image_url" :alt="item.name">
                        </a>

                        <div class="product-details">
                            <h5 class="product-name">
                                <a :href="`{{ route('shop.product_or_category.index', '') }}/${item.product_url_key}`">@{{ item.name }}</a>
                            </h5>

                            <span class="product-price">
                                <span class="price">@{{ item.formatted_price }}</span>
                            </span>

                            <span class="product-quantity">(x@{{ item.quantity }})</span>

                            <div class="product-remove">
                                <a href="javascript:void(0);" @click="removeItem(item.id)">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>

                <!-- Subtotal -->
                <div class="subtotal">
                    <span class="total-title">Subtotal:</span>
                    <span class="total-price">@{{ cart.formatted_sub_total }}</span>
                </div>

                <!-- Buttons -->
                <div class="actions">
                    <a class="button button-viewcart" href="{{ route('shop.checkout.cart.index') }}">
                        <span>View Bag</span>
                    </a>
                    <a class="button button-checkout" href="{{ route('shop.checkout.onepage.index') }}">
                        <span>Checkout</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Empty Cart -->
        <div class="shopcart-description stelina-submenu" v-else>
            <div class="content-wrap text-center py-5">
                <p class="text-gray-500 mb-2">Your cart is empty</p>
            </div>
        </div>
    </div>
    @else
    <a href="{{ route('shop.checkout.cart.index') }}">
        <span class="icon-cart cursor-pointer text-2xl" role="button"></span>
    </a>
    @endif

    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.after') !!}
</script>

    <script type="module">
        app.component("v-mini-cart", {
            template: '#v-mini-cart-template',
            data() {
                return {
                    cart: null,
                    isLoading: false,
                };
            },

            mounted() {
                // initial fetch
                this.getCart();

                // When other code emits 'update-mini-cart' with cart data: replace
                this.$emitter.on('update-mini-cart', (cart) => {
                    this.cart = cart;
                });

                // When code emits 'cart-updated', re-fetch from server (ensures canonical state)
                this.$emitter.on('cart-updated', () => {
                    this.getCart();
                });
            },

            methods: {
                getCart() {
                    this.$axios.get('{{ route('shop.api.checkout.cart.index') }}')
                        .then(response => {
                            this.cart = response.data.data;
                        })
                        .catch(() => {
                            // ignore errors silently
                        });
                },

                updateItem(quantity, item) {
                    this.isLoading = true;
                    let qty = {};
                    qty[item.id] = quantity;

                    this.$axios.put('{{ route('shop.api.checkout.cart.update') }}', {
                            qty
                        })
                        .then(response => {
                            this.cart = response.data.data;
                            // notify other listeners
                            this.$emitter.emit('update-mini-cart', response.data.data);
                            this.$emitter.emit('cart-updated');
                        })
                        .finally(() => {
                            this.isLoading = false;
                        });
                },

                removeItem(itemId) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.$axios.post('{{ route('shop.api.checkout.cart.destroy') }}', {
                                '_method': 'DELETE',
                                'cart_item_id': itemId,
                            }).then(response => {
                                this.cart = response.data.data;
                                this.$emitter.emit('add-flash', {
                                    type: 'success',
                                    message: response.data.message
                                });
                                this.$emitter.emit('update-mini-cart', response.data.data);
                                this.$emitter.emit('cart-updated');
                            }).catch(error => {
                                this.$emitter.emit('add-flash', {
                                    type: 'error',
                                    message: error.response?.data?.message ||
                                        'Something went wrong.'
                                });
                            });
                        }
                    });
                },
            },
        });
    </script>

    {{-- Global addToCart function (works outside Vue components) --}}
    <script>
        function addToCart(event, productId) {
            event.preventDefault();

            // Find the submit button in the form
            const button = (event.target && event.target.querySelector('button')) || null;
            if (button) {
                button.disabled = true;
                // optional: replace with spinner markup if desired
                button.dataset.originalText = button.innerText;
                button.innerText = 'Adding...';
            }

            axios.post(`{{ route('shop.api.checkout.cart.store') }}`, {
                    quantity: 1,
                    product_id: productId,
                })
                .then(response => {
                    // if API returns cart data, emit update so mini-cart updates instantly
                    if (response.data?.data) {
                        if (window.emitter) {
                            // emit both: immediate cart replacement + re-check
                            window.emitter.emit('update-mini-cart', response.data.data);
                            // Ask components to re-fetch canonical cart if they prefer
                            window.emitter.emit('cart-updated');
                        }
                    }

                    // show flash via Bagisto emitter if available
                    if (response.data?.message) {
                        if (window.emitter) {
                            window.emitter.emit('add-flash', {
                                type: 'success',
                                message: response.data.message
                            });
                        } else {
                            // fallback simple notice
                            console.log(response.data.message);
                        }
                    }
                })
                .catch(error => {
                    const status = error.response?.status;
                    const msg = error.response?.data?.message || 'Something went wrong.';

                    // Redirect guests to login if backend supplies redirect_uri
                    if (status === 401 && error.response?.data?.redirect_uri) {
                        window.location.href = error.response.data.redirect_uri;
                        return;
                    }

                    if (window.emitter) {
                        window.emitter.emit('add-flash', {
                            type: 'error',
                            message: msg
                        });
                    } else {
                        alert(msg);
                    }
                })
                .finally(() => {
                    if (button) {
                        button.disabled = false;
                        button.innerText = button.dataset.originalText || 'Add to cart';
                    }
                });
        }
    </script>
@endpushOnce
