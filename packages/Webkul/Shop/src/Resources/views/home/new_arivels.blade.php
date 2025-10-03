a<div class="stelina-product produc-featured rows-space-40">
    <div class="container">
        <h3 class="custommenu-title-blog">New Arrivals</h3>

        <ul class="row list-products auto-clear equal-container product-grid">
            @foreach ($newArrivals as $product)
                <li class="product-item col-lg-3 col-md-4 col-sm-6 col-xs-6 col-ts-12 style-1">
                    <div class="product-inner equal-element">
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
                                <a href="#">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                </a>
                                <div class="thumb-group">
                                    <a href="#" class="button quick-wiew-button">Quick View</a>
                                    <div class="loop-form-add-to-cart">
                                        <form method="POST" action="">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button class="single_add_to_cart_button button">
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
                                <a href="#">
                                    {{ $product->name }}
                                </a>
                            </h5>
                            <div class="group-info">
                                <div class="stars-rating">
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
