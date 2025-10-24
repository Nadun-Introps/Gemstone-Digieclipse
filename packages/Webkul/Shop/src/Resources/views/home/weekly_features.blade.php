@push('styles')
    <style>
        .slick-slide {
            padding: 0px 35px;
        }
        .product-item .price ins {
            font-size: 13px;
        }

        .stelina-product.layout1 {
            background: #ab8e66;
            padding: 70px 0 0px;
        }

        @media (max-width: 768px) {
            .slick-slide {
                padding: 0 20px;
            }
        }

        @media (max-width: 480px) {
            .slick-slide {
                padding: 0 15px;
            }
        }

        @media (max-width: 380px) {
            .slick-slide {
                padding: 0 60px;
            }
        }

    </style>
@endpush
<div class="stelina-product layout1">
    <div class="container">
        <div class="container-wapper">
            <div class="head">
                <h3 class="title">Weekly Featured</h3>
                <div class="subtitle">Let’s Shop our featured item this week</div>
            </div>
            <div class="product-list-owl owl-slick equal-container nav-center-left"
                data-slick='{"autoplay":false, "autoplaySpeed":1000, "arrows":true, "dots":false, "infinite":true, "speed":800,"infinite":false}'
                data-responsive='[{"breakpoint":"2000","settings":{"slidesToShow":3}},{"breakpoint":"1200","settings":{"slidesToShow":2}},{"breakpoint":"992","settings":{"slidesToShow":2}},{"breakpoint":"767","settings":{"slidesToShow":3, "arrows":false}},{"breakpoint":"481","settings":{"slidesToShow":2, "arrows":false}}, {"breakpoint":"381","settings":{"slidesToShow":1,"arrows":false}}]'>

                @foreach ($newArrivals as $product)
                    <div class="product-item style-1 product-type-variable" style="height: 330px;">
                        <div class="product-inner equal-element weekly-featured-card">
                            <div class="product-top">
                                @if ($product->new)
                                    <div class="flash">
                                        <span class="onnew"><span class="text">New</span></span>
                                    </div>
                                @endif
                            </div>

                            <div class="product-thumb">
                                <div class="thumb-inner">
                                    <a href="{{ route('shop.product_or_category.index', $product->url_key) }}">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    </a>
                                    <div class="thumb-group">
                                        {{-- <div class="yith-wcwl-add-to-wishlist">
                                            <div class="yith-wcwl-add-button">
                                                <a href="#">Add to Wishlist</a>
                                            </div>
                                        </div> --}}
                                        <a href="{{ route('shop.product_or_category.index', $product->url_key) }}"
                                            class="button quick-view-button">Quick View</a>
                                        <div class="loop-form-add-to-cart">
                                            <form method="POST" onsubmit="addToCart2(event, {{ $product->id }})">
                                                @csrf
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="single_add_to_cart_button button">
                                                    Add to cart
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="product-info">
                                <h5 class="product-name product_title weekly">
                                    <a href="{{ route('shop.product_or_category.index', $product->url_key) }}">
                                        {{ $product->name }}
                                    </a>
                                </h5>

                                <div class="group-info">
                                    <div class="stars-rating">
                                        <div class="star-rating"> <span class="star-3"></span> </div>
                                        <div class="count-star"> (3) </div>
                                    </div>
                                    <div class="price">
                                        @if ($product->special_price)
                                            <del>${{ number_format($product->price, 2) }}</del>
                                            <ins>${{ number_format($product->special_price, 2) }}</ins>
                                        @else
                                            <ins>${{ number_format($product->price, 2) }}</ins>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function addToCart2(event, productId) {
            event.preventDefault();

            const button = event.target.querySelector('button');
            if (button) {
                button.disabled = true;
                button.innerText = 'Adding...';
            }

            axios.post(`{{ route('shop.api.checkout.cart.store') }}`, {
                    quantity: 1,
                    product_id: productId,
                })
                .then(response => {
                    // Check if the controller returned 'data' and 'message'
                    const resData = response.data.data ?? null;
                    const resMessage = response.data.message ?? 'Product added to cart';

                    // Update Bagisto mini-cart via emitter
                    if (resData && window.emitter) {
                        window.emitter.emit('update-mini-cart', resData);
                    }

                    // Show top-right success box
                    showSuccessBox(resMessage);

                    // Reload page after 1 second
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                })
                .catch(error => {
                    const msg = error.response?.data?.message || 'Something went wrong.';

                    // If user is not logged in, redirect to login page
                    if (error.response?.status === 401 && error.response?.data?.redirect_uri) {
                        window.location.href = error.response.data.redirect_uri;
                        return;
                    }

                    // Show error box
                    showSuccessBox(msg, true);

                    // Enable button again
                    if (button) {
                        button.disabled = false;
                        button.innerText = 'Add to cart';
                    }
                })
                .finally(() => {
                    if (button) {
                        button.disabled = false;
                        button.innerText = 'Add to cart';
                    }
                });
        }

        function showSuccessBox(message, isError = false) {
            // Remove existing message if any
            const oldBox = document.querySelector('#custom-cart-message');
            if (oldBox) oldBox.remove();

            // Create message box
            const box = document.createElement('div');
            box.id = 'custom-cart-message';
            box.innerText = message;
            box.style.position = 'fixed';
            box.style.top = '20px';
            box.style.right = '20px';
            box.style.zIndex = '9999';
            box.style.background = isError ? '#e74c3c' : '#ab8e66';
            box.style.color = 'white';
            box.style.padding = '12px 20px';
            box.style.borderRadius = '8px';
            box.style.boxShadow = '0 2px 8px rgba(0,0,0,0.2)';
            box.style.fontSize = '15px';
            box.style.transition = 'all 0.3s ease';
            box.style.opacity = '0';
            box.style.transform = 'translateY(-20px)';

            document.body.appendChild(box);

            // Fade in
            setTimeout(() => {
                box.style.opacity = '1';
                box.style.transform = 'translateY(0)';
            }, 50);

            // Fade out and remove after 1 second
            setTimeout(() => {
                box.style.opacity = '0';
                box.style.transform = 'translateY(-20px)';
                setTimeout(() => box.remove(), 300);
            }, 1000);
        }
    </script>
@endpush
