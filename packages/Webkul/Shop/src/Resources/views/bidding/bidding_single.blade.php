<!-- Page Layout -->
<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ $biddingProduct->product_name }} - @lang('shop::app.home.bidding_single.title')
    </x-slot>

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .thumbnail.active {
            border: 2px solid #f97316;
            /* orange-500 */
        }

        .thumbnail:hover {
            opacity: 0.8;
            cursor: pointer;
        }
    </style>

    <!-- Countdown Timer Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set the date we're counting down to
            const endDate = new Date("{{ $biddingProduct->end_date }} {{ $biddingProduct->end_time }}").getTime();

            // Update the count down every 1 second
            const countdownFunction = setInterval(function() {
                // Get today's date and time
                const now = new Date().getTime();

                // Find the distance between now and the count down date
                const distance = endDate - now;

                // Time calculations for days, hours, minutes and seconds
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Output the result in elements with id="days", "hours", "minutes", "seconds"
                document.getElementById("days").innerHTML = days.toString().padStart(2, '0');
                document.getElementById("hours").innerHTML = hours.toString().padStart(2, '0');
                document.getElementById("minutes").innerHTML = minutes.toString().padStart(2, '0');
                document.getElementById("seconds").innerHTML = seconds.toString().padStart(2, '0');

                // If the count down is over, write some text
                if (distance < 0) {
                    clearInterval(countdownFunction);
                    document.getElementById("days").innerHTML = "00";
                    document.getElementById("hours").innerHTML = "00";
                    document.getElementById("minutes").innerHTML = "00";
                    document.getElementById("seconds").innerHTML = "00";
                    document.getElementById("bid-button").disabled = true;
                    document.getElementById("bid-button").innerHTML = "Auction Ended";
                }
            }, 1000);

            // Image gallery functionality
            function changeMainImage(thumbnail) {
                const mainImage = document.getElementById('mainImage');
                mainImage.src = thumbnail.getAttribute('data-main-src');

                // Remove active class from all thumbnails
                document.querySelectorAll('.thumbnail').forEach(img => {
                    img.classList.remove('active');
                });

                // Add active class to clicked thumbnail
                thumbnail.classList.add('active');
            }

            // Set first thumbnail as active on page load
            const firstThumbnail = document.querySelector('.thumbnail');
            if (firstThumbnail) {
                firstThumbnail.classList.add('active');
            }
        });
    </script>

    <!-- Hero Banner -->
    <section class="relative w-full h-52 bg-black">
        <img src="" alt="Gems" class="absolute w-full h-full object-cover opacity-70">
        <div class="absolute top-8 left-10">
            <h1 class="text-4xl font-bold text-white">Bidding</h1>
        </div>
    </section>

    <!-- Main Product Section -->
    <section class="max-w-5xl mx-auto p-8 grid grid-cols-1 md:grid-cols-2 gap-20">
        <!-- Left: Image Gallery -->
        <div class="flex flex-col h-full">
            <!-- Main Image -->
            @if (count($productImages) > 0)
                <img src="{{ asset('storage/' . $productImages[0]->path) }}"
                    class="w-full h-[400px] object-cover rounded-lg border" alt="{{ $biddingProduct->product_name }}"
                    id="mainImage">
            @else
                <!-- Fallback image if no product images found -->
                <img src="//wijayagems.com/cdn/shop/files/image_35669368-b4d5-4eb3-9f53-c92498f79590_110x110@2x.jpg?v=1706956381"
                    class="w-full h-[400px] object-cover rounded-lg border" alt="{{ $biddingProduct->product_name }}"
                    id="mainImage">
            @endif

            <!-- Thumbnail Images -->
            <div class="flex space-x-3 mt-4">
                @if (count($productImages) > 0)
                    @foreach ($productImages as $index => $image)
                        <img src="{{ asset('storage/' . $image->path) }}"
                            class="flex-1 h-28 rounded-md border cursor-pointer object-cover thumbnail"
                            alt="Thumbnail {{ $index + 1 }}" data-main-src="{{ asset('storage/' . $image->path) }}"
                            onclick="changeMainImage(this)">
                    @endforeach
                @else
                    <!-- Fallback thumbnails if no images found -->
                    <img src="//wijayagems.com/cdn/shop/files/image_35669368-b4d5-4eb3-9f53-c92498f79590_110x110@2x.jpg?v=1706956381"
                        class="flex-1 h-28 rounded-md border cursor-pointer object-cover thumbnail"
                        data-main-src="//wijayagems.com/cdn/shop/files/image_35669368-b4d5-4eb3-9f53-c92498f79590_110x110@2x.jpg?v=1706956381"
                        onclick="changeMainImage(this)">
                    <img src="//wijayagems.com/cdn/shop/files/image_f4828cee-1de7-4446-9e30-0b7380294ac4_110x110@2x.jpg?v=1720173081"
                        class="flex-1 h-28 rounded-md border cursor-pointer object-cover thumbnail"
                        data-main-src="//wijayagems.com/cdn/shop/files/image_f4828cee-1de7-4446-9e30-0b7380294ac4_110x110@2x.jpg?v=1720173081"
                        onclick="changeMainImage(this)">
                    <img src="//wijayagems.com/cdn/shop/files/image_c9d82fea-890c-4650-82a2-375afda016a1_110x110@2x.jpg?v=1720173081"
                        class="flex-1 h-28 rounded-md border cursor-pointer object-cover thumbnail"
                        data-main-src="//wijayagems.com/cdn/shop/files/image_c9d82fea-890c-4650-82a2-375afda016a1_110x110@2x.jpg?v=1720173081"
                        onclick="changeMainImage(this)">
                @endif
            </div>
        </div>

        <!-- Right: Bidding Info -->
        <div class="flex flex-col h-full w-3/4 space-y-6">
            <h2 class="text-xl font-bold">{{ $biddingProduct->product_name }}</h2>
            <p class="text-xl" style="margin-top: 10px;">Rs. {{ number_format($biddingProduct->price, 2) }}</p>

            <div class="bg-gray-300 p-4 rounded-lg">
                <!-- Countdown -->
                <div class="flex space-x-4 text-center">
                    <div>
                        <p id="days" class="text-2xl font-bold">00</p>
                        <span class="text-sm">Days</span>
                    </div>
                    <div>
                        <p id="hours" class="text-2xl font-bold">00</p>
                        <span class="text-sm">Hours</span>
                    </div>
                    <div>
                        <p id="minutes" class="text-2xl font-bold">00</p>
                        <span class="text-sm">Minutes</span>
                    </div>
                    <div>
                        <p id="seconds" class="text-2xl font-bold">00</p>
                        <span class="text-sm">Seconds</span>
                    </div>
                </div>
                <!-- Progress Bar -->
                <div class="w-3/4 bg-gray-200 rounded-full h-2 mt-4">
                    <div class="bg-orange-400 h-2 rounded-full" style="width: 60%;"></div>
                </div>
                <p class="text-sm text-gray-500">Ending on :
                    <br>{{ date('F jS, Y g:i A', strtotime($biddingProduct->end_date . ' ' . $biddingProduct->end_time)) }}
                </p>
            </div>
            <!-- Bidding Info -->
            <div class="bg-gray-300 p-4 rounded-lg">
                <p class="">{{ count($biddingHistory) }} Bid(s)</p>
                <p class="text-xl font-bold">${{ $currentBid ? number_format($currentBid, 2) : '0.00' }}</p>
            </div>

            <p class="flex justify-between">Opening Bid Amount: <span
                    class="font-semibold">${{ number_format($biddingProduct->price, 2) }}</span></p>
            <p class="flex justify-between" style="margin-top: 10px;">Next Minimum Bid Amount: <span
                    class="font-semibold">${{ $currentBid ? number_format($currentBid + $biddingProduct->minimum_increment, 2) : number_format($biddingProduct->price + $biddingProduct->minimum_increment, 2) }}</span>
            </p>

            <!-- Bid Input -->
            <form action="{{ route('shop.bidding.add_to_cart', $biddingProduct->bid_pro_id) }}" method="POST">
                @csrf
                <div class="flex space-x-3 w-full items-center">
                    <label class="w-1/2 items-center" for="bid_amount">Enter Bid Amount</label>
                    <input type="number" name="bid_amount"
                        class="w-1/2 border-2 border-gray-500 rounded-md px-2 py-1 appearance-none
                focus:outline-none focus:ring-0 focus:border-gray-500"
                        min="{{ $currentBid ? $currentBid + $biddingProduct->minimum_increment : $biddingProduct->price + $biddingProduct->minimum_increment }}"
                        step="{{ $biddingProduct->minimum_increment }}" required />
                </div>
                <div class="flex justify-center mt-4">
                    <button id="bid-button" type="submit"
                        class="bg-black text-white px-16 py-2 rounded-md hover:bg-gray-800">
                        Bid Now
                    </button>
                </div>
            </form>

        </div>
    </section>

    <!-- Product Description -->
    <section class="max-w-6xl mx-auto p-6">
        <h3 class="text-xl font-bold mb-4">Product Description</h3><br>
        <ul class="space-y-2 mb-6">
            <li><b>Product name:</b> {{ $biddingProduct->product_name }}</li>
            <li><b>Auction Status:</b> {{ ucfirst($biddingProduct->status) }}</li>
            <li><b>Starting Price:</b> Rs. {{ number_format($biddingProduct->price, 2) }}</li>
            <li><b>Minimum Bid Increment:</b> Rs. {{ number_format($biddingProduct->minimum_increment, 2) }}</li>
            @if ($biddingProduct->reserve_price)
                <li><b>Reserve Price:</b> Rs. {{ number_format($biddingProduct->reserve_price, 2) }}</li>
            @endif
        </ul>

        <!-- Description -->
        @if ($biddingProduct->description)
            <p class="text-gray-600 leading-relaxed text-justify">
                {!! $biddingProduct->description !!}
            </p>
        @else
            <p class="text-gray-600 leading-relaxed text-justify">
                No description available for this product.
            </p>
        @endif
    </section>

</x-shop::layouts>
