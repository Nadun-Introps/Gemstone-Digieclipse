<div class="stelina-product layout1">
    <div class="container">
        <div class="container-wapper">
            <div class="head">
                <h3 class="title">Weekly Featured</h3>
                <div class="subtitle">Let’s Shop our featured item this week</div>
            </div>

            <div class="product-list-owl owl-slick equal-container nav-center-left"
                data-slick='{"autoplay":false, "autoplaySpeed":1000, "arrows":true, "dots":false, "infinite":true, "speed":800,"infinite":false}'
                data-responsive='[{"breakpoint":"2000","settings":{"slidesToShow":3}},{"breakpoint":"1200","settings":{"slidesToShow":2}},{"breakpoint":"992","settings":{"slidesToShow":1}},{"breakpoint":"768","settings":{"slidesToShow":2}},{"breakpoint":"481","settings":{"slidesToShow":1}}]'>

                @foreach ($featuredProducts as $product)
                    <div class="product-item style-1 product-type-variable">
                        <div class="product-inner equal-element">
                            <div class="product-top">
                                @if ($product->new)
                                    <div class="flash">
                                        <span class="onnew"><span class="text">New</span></span>
                                    </div>
                                @endif
                            </div>

                            <div class="product-thumb">
                                <div class="thumb-inner">
                                    <a href="">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    </a>
                                    <div class="thumb-group">
                                        <div class="yith-wcwl-add-to-wishlist">
                                            <div class="yith-wcwl-add-button">
                                                <a href="#">Add to Wishlist</a>
                                            </div>
                                        </div>
                                        <a href="#" class="button quick-wiew-button">Quick View</a>
                                        <div class="loop-form-add-to-cart">
                                            <button class="single_add_to_cart_button button">Add to cart</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="product-info">
                                <h5 class="product-name product_title">
                                    <a href="#">
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
                                            <del>${{ $product->price }}</del>
                                            <ins>${{ $product->special_price }}</ins>
                                        @else
                                            <ins>${{ $product->price }}</ins>
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
