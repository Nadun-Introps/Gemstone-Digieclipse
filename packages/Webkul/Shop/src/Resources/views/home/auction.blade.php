@push('styles')
    <style>
        .live-auctions-container {
            position: relative;
            padding: 40px 0;
            max-width: 1200px;
            margin: auto;
        }

        .live-auctions-container h2 {
            font-size: 26px;
            margin-bottom: 25px;
            color: #404040;
        }

        .live-auctions-container h2 strong {
            color: #404040;
        }

        .live-auctions-grid {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            gap: 20px;
            padding: 10px;
            scroll-behavior: smooth;
        }

        .live-auction-card {
            flex: 0 0 calc(33.333% - 20px);
            /* 3 cards per view on desktop */
            scroll-snap-align: start;
            border: 1px solid #eee;
            border-radius: 8px;
            background: #fff;
            overflow: hidden;
            padding: 15px;
            display: flex;
            gap: 15px;
            transition: box-shadow 0.3s ease;
        }

        .live-auction-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .live-auction-img img {
            width: 120px;
            height: auto;
            object-fit: contain;
        }

        .live-auction-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .live-auction-details h3 {
            font-size: 15px;
            font-weight: 500;
            margin: 0 0 5px;
            color: #333;
        }

        .live-auction-details .price {
            font-size: 16px;
            font-weight: 600;
            color: #000;
        }

        .live-auction-details .old-price {
            text-decoration: line-through;
            font-size: 14px;
            color: #999;
            margin-left: 5px;
        }

        .star-rating {
            color: #ff9800;
            font-size: 14px;
        }

        .countdown {
            display: flex;
            gap: 8px;
            font-size: 12px;
            margin-top: 8px;
        }

        .countdown div {
            text-align: center;
            padding: 4px 6px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #f9f9f9;
        }

        .countdown span {
            display: block;
            font-weight: bold;
            font-size: 13px;
            color: #d32f2f;
        }

        /* Hide scrollbar */
        .live-auctions-grid::-webkit-scrollbar {
            display: none;
        }

        /* Arrows */
        .arrow_icon_right,
        .arrow_icon_left {
            position: absolute;
            top: 75px;
            color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 27px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: background 0.3s ease, transform 0.2s ease;
            z-index: 0;
        }

        .arrow_icon_right:hover,
        .arrow_icon_left:hover {
            background: #f0f0f0;
            transform: scale(1.1);
        }

        .arrow_icon_right {
            right: 10px;
        }

        .arrow_icon_left {
            right: 55px;
        }

        /* Tablet */
        @media (max-width: 992px) {
            .live-auction-card {
                flex: 0 0 calc(50% - 20px);
            }

            /* 2 cards per view */
        }

        /* Mobile */
        @media (max-width: 576px) {
            .live-auction-card {
                flex: 0 0 90%;
                max-width: 90%;
            }

            /* 1 card per view */
            .live-auction-img img {
                width: 80px;
            }

            .live-auction-details h3 {
                font-size: 14px;
            }

            .live-auctions-container h2 {
                font-size: 20px;
            }
        }
    </style>
@endpush
{{-- resources/views/vendor/shop/home/auction.blade.php --}}
<div class="live-auctions-container">
    <h2>Live <strong>Auctions</strong></h2>

    <div class="icon-arrow-left-stylish rtl:icon-arrow-right-stylish inline-block cursor-pointer text-2xl max-lg:hidden arrow_icon_left"
        onclick="slideLeft()"></div>
    <div class="icon-arrow-right-stylish rtl:icon-arrow-left-stylish inline-block cursor-pointer text-2xl max-lg:hidden arrow_icon_right"
        onclick="slideRight()"></div>

    <div class="live-auctions-grid scrollbar-hide" id="auctionSlider">
        @forelse($auctions as $a)
            <div class="live-auction-card auction-card" data-end="{{ $a['end'] }}" data-start="{{ $a['start'] }}"
                data-product-id="{{ $a['id'] }}">

                <div class="live-auction-img">
                    <img src="{{ $a['image'] }}" alt="{{ $a['product_name'] }}">
                </div>

                <div class="live-auction-details">
                    <h3>{{ $a['product_name'] }}</h3>
                    <div class="star-rating">★★★★★</div>
                    <div class="price">{{ $a['currency'] }} {{ number_format($a['price'], 2) }}</div>

                    <div class="countdown">
                        <div><span class="days">0</span>DAYS</div>
                        <div><span class="hours">00</span>HRS</div>
                        <div><span class="minutes">00</span>MIN</div>
                        <div><span class="seconds">00</span>SEC</div>
                    </div>
                </div>
            </div>
        @empty
            <p>No auctions available</p>
        @endforelse
    </div>
</div>

@push('scripts')
    <script>
        // Helper: pad numbers with 0
        function pad(n) {
            return (n < 10) ? ('0' + n) : String(n);
        }

        // Update one auction card
        function updateAuctionCountdown(card, endIso) {
            if (!endIso) return;

            const endTime = Date.parse(endIso); // ms
            const now = Date.now();
            let diff = endTime - now;

            const daysEl = card.querySelector('.days');
            const hoursEl = card.querySelector('.hours');
            const minutesEl = card.querySelector('.minutes');
            const secondsEl = card.querySelector('.seconds');

            if (diff <= 0) {
                // Auction ended
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

        // Run every second on all auction cards
        document.addEventListener('DOMContentLoaded', function() {
            setInterval(function() {
                document.querySelectorAll('.auction-card').forEach(function(card) {
                    const endIso = card.dataset.end; // e.g. "2025-09-16T14:30:00Z"
                    updateAuctionCountdown(card, endIso);
                });
            }, 1000);
        });
    </script>
@endpush
