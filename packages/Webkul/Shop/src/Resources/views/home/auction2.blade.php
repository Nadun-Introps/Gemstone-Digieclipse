@push('styles')
    <style>
        .countdown {
            display: flex;
            gap: 5px;
            font-size: 12px;
            margin-top: 8px;
            /* margin-left: 20px; */
            margin-bottom: 10px;
            justify-content: center;
        }
        .product-inner.equal-element {
            height: 410px !important;
        }
        .product-item.style-5.auction-card {
            padding: 25px !important;
        }
        .countdown div {
            text-align: center;
            padding: 0px 0px 0px;
            border: 1px solid #ddd;
            border-radius: 50%;
            min-width: 50px;
            margin-right: 4px;
            display: inline-block;
            font-weight: 600;
            position: relative;
            background-color: #efefef;
            color: #868686;
            font-family: 'Jost';
        }

        .countdown span {
            display: block;
            font-weight: bold;
            font-size: 16px;
            color: #020000;
        }

        .quick-wiew-button {
            color: #fff;
            background-color: #ab8e66;
            transition: all 0.3s ease;
        }

        /* .quick-wiew-button:hover {
            color: #fff;
        } */

        /* .product-item .thumb-group .loop-form-add-to-cart:hover .single_add_to_cart_button::before {
            color: #fff;
        } */

        @media (max-width: 1200px) {
            .product-inner.equal-element {
                height: 430px !important;
            }
        }


        @media (max-width: 992px) {
            .product-item.style-5.auction-card {
                width: 290px !important;
            }
        }

        @media (max-width: 480px) {
            .product-item.style-5.auction-card {
                width: 300px !important;
            }

             .slick-slide {
                padding: 40px;
            }
        }
    </style>
@endpush

<div class="stelina-product produc-featured rows-space-65">
    <div class="container">
        <h3 class="custommenu-title-blog">Auctions</h3>
        
        <div class="owl-products owl-slick equal-container nav-center"
            data-slick='{"autoplay":false, "autoplaySpeed":1000, "arrows":false, "dots":true, "infinite":false, "speed":800, "rows":1}'
            data-responsive='[{"breakpoint":"2000","settings":{"slidesToShow":4}},{"breakpoint":"1200","settings":{"slidesToShow":3}},{"breakpoint":"992","settings":{"slidesToShow":2}},{"breakpoint":"480","settings":{"slidesToShow":1}}, {"breakpoint":"380","settings":{"slidesToShow":1}}]'>

            @forelse($auctions as $a)
                <div class="product-item style-5 auction-card" data-end="{{ $a['end'] }}"
                    data-start="{{ $a['start'] }}" data-product-id="{{ $a['id'] }}"
                    onclick="window.location.href='{{ route('shop.bidding.bidding_single', ['id' => $a['id']]) }}'">

                    <div class="product-inner equal-element">
                        <div class="product-top">
                            <div class="flash">
                                <span class="onnew"><span class="text">new</span></span>
                            </div>
                        </div>

                        <div class="product-thumb">
                            <div class="thumb-inner">
                                <a href="{{ route('shop.bidding.bidding_single', ['id' => $a['id']]) }}">
                                    <img src="{{ $a['image'] }}" alt="{{ $a['product_name'] }}">
                                </a>
                                <div class="thumb-group">
                                    <div class="yith-wcwl-add-to-wishlist">
                                        <div class="yith-wcwl-add-button">
                                            <a href="#">Add to Wishlist</a>
                                        </div>
                                    </div>
                                    <a href="#" class="button quick-view-button">Quick View</a>
                                    <div class="loop-form-add-to-cart">
                                        <button class="single_add_to_cart_button button">Add to cart</button>
                                    </div>
                                </div>
                            </div>

                            <div class="countdown">
                                <div><span class="days">0</span>DAYS</div>
                                <div><span class="hours">00</span>HRS</div>
                                <div><span class="minutes">00</span>MIN</div>
                                <div><span class="seconds">00</span>SEC</div>
                            </div>

                            <div class="product-info">
                                <h5 class="product-name product_title">
                                    <a href="{{ route('shop.bidding.bidding_single', ['id' => $a['id']]) }}">
                                        {{ $a['product_name'] }}
                                    </a>
                                </h5>
                                <div class="group-info">
                                    <div class="stars-rating">
                                        <div class="star-rating"><span class="star-5"></span></div>
                                        <div class="count-star">(5)</div>
                                    </div>
                                    <div class="price" style="font-size: 13px;">
                                        {{-- <ins>{{ $a['currency'] }} {{ number_format($a['price'], 2) }}</ins> --}}
                                        <ins>
                                            @if ($a['currency'] === 'USD')
                                            ${{ number_format($a['price'], 2) }}
                                            @else
                                                {{ $a['currency'] }} {{ number_format($a['price'], 2) }}
                                            @endif
                                        </ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p>No auctions available</p>
            @endforelse
        </div>

    </div>
</div>

@push('scripts')
    <script>
        function pad(n) {
            return (n < 10) ? ('0' + n) : String(n);
        }

        function updateAuctionCountdown(card, endIso) {
            if (!endIso) return;
            const endTime = Date.parse(endIso);
            const now = Date.now();
            let diff = endTime - now;

            const daysEl = card.querySelector('.days');
            const hoursEl = card.querySelector('.hours');
            const minutesEl = card.querySelector('.minutes');
            const secondsEl = card.querySelector('.seconds');

            if (diff <= 0) {
                if (daysEl) daysEl.innerText = '0';
                if (hoursEl) hoursEl.innerText = '00';
                if (minutesEl) minutesEl.innerText = '00';
                if (secondsEl) secondsEl.innerText = '00';
                card.classList.add('auction-ended');
                return;
            }

            const MS_IN_SEC = 1000;
            const MS_IN_MIN = 60 * MS_IN_SEC;
            const MS_IN_HOUR = 60 * MS_IN_MIN;
            const MS_IN_DAY = 24 * MS_IN_HOUR;

            const days = Math.floor(diff / MS_IN_DAY);
            diff -= days * MS_IN_DAY;

            const hours = Math.floor(diff / MS_IN_HOUR);
            diff -= hours * MS_IN_HOUR;

            const minutes = Math.floor(diff / MS_IN_MIN);
            diff -= minutes * MS_IN_MIN;

            const seconds = Math.floor(diff / MS_IN_SEC);

            if (daysEl) daysEl.innerText = String(days);
            if (hoursEl) hoursEl.innerText = pad(hours);
            if (minutesEl) minutesEl.innerText = pad(minutes);
            if (secondsEl) secondsEl.innerText = pad(seconds);
        }

        document.addEventListener('DOMContentLoaded', function() {
            setInterval(function() {
                document.querySelectorAll('.auction-card').forEach(function(card) {
                    const endIso = card.dataset.end;
                    updateAuctionCountdown(card, endIso);
                });
            }, 1000);
        });
    </script>
@endpush
