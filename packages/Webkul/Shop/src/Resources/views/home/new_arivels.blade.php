@push('styles')
    <style>
    .product-inner.equal-element.arrivals-card{
        height: 395px !important;
    }
    </style>
@endpush

<div class="stelina-product produc-featured rows-space-40">
    <div class="container">
        <h3 class="custommenu-title-blog">New Arrivals</h3>

        <ul class="row list-products auto-clear equal-container product-grid">
            @foreach ($newArrivals as $product)
                <li class="product-item col-lg-3 col-md-3 col-sm-4 col-xs-4 col-ts-6 style-1">
                    <div class="product-inner equal-element arrivals-card">
                        <!-- Top Badge -->
                        <div class="product-top">
                            @if ($product->new)
                                <div class="flash">
                                    <span class="onnew"><span class="text">New</span></span>
                                </div>
                            @elseif($product->special_price)
                                <div class="flash">
                                    <span class="onsale"><span class="text">Sale</span></span>
                                </div>
                            @endif
                        </div>

                        <!-- Product Image -->
                        <div class="product-thumb">
                            <div class="thumb-inner">
                                <a href="{{ route('shop.product_or_category.index', $product->url_key) }}">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                </a>
                                <div class="thumb-group">
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

                        <!-- Product Info -->
                        <div class="product-info">
                            <h5 class="product-name product_title">
                                <a href="{{ route('shop.product_or_category.index', $product->url_key) }}">
                                    {{ $product->name }}
                                </a>
                            </h5>
                            <div class="group-info">
                                <div class="stars-rating" style="margin-bottom:4px;">
                                    <div class="star-rating">
                                        <span class="star-{{ round($product->reviews->avg('rating')) }}"></span>
                                    </div>
                                    <div class="count-star">
                                        ({{ $product->reviews->count() }})
                                    </div>
                                </div>
                                <div class="price">
                                    {!! $product->getTypeInstance()->getPriceHtml() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
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
