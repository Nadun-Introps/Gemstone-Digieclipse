<!-- Page Layout -->
<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.home.bidding_single.title')
        </x-slot>

        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Hero Banner -->
        <section class="relative w-full h-52 bg-black">
            <img src="" alt="Gems" class="absolute w-full h-full object-cover opacity-70">
            <div class="absolute top-8 left-10">
                <h1 class="text-4xl font-bold text-white">Shop</h1>
            </div>
        </section>

        <!-- Main Product Section -->
        <section class="max-w-5xl mx-auto p-8 grid grid-cols-1 md:grid-cols-2 gap-20">
            <!-- Left: Image Gallery -->
            <div class="flex flex-col h-full">
                <img src="//wijayagems.com/cdn/shop/files/image_35669368-b4d5-4eb3-9f53-c92498f79590_110x110@2x.jpg?v=1706956381"
                    class="w-full h-[400px] object-cover rounded-lg border" alt="Main Gem">
                <div class="flex space-x-3 mt-4">
                    <img src="//wijayagems.com/cdn/shop/files/image_35669368-b4d5-4eb3-9f53-c92498f79590_110x110@2x.jpg?v=1706956381"
                        class="flex-1 h-28 rounded-md border cursor-pointer object-cover">
                    <img src="//wijayagems.com/cdn/shop/files/image_f4828cee-1de7-4446-9e30-0b7380294ac4_110x110@2x.jpg?v=1720173081"
                        class="flex-1 h-28 rounded-md border cursor-pointer object-cover">
                    <img src="//wijayagems.com/cdn/shop/files/image_c9d82fea-890c-4650-82a2-375afda016a1_110x110@2x.jpg?v=1720173081"
                        class="flex-1 h-28 rounded-md border cursor-pointer object-cover">
                </div>
            </div>
            <!-- Right: Bidding Info -->
            <div class="flex flex-col h-full w-3/4 space-y-6">
                <h2 class="text-xl font-bold">7.23ct Natural Star Ruby</h2>
                <p class="text-xl" style="margin-top: 10px;">Rs. 1,286,000.00</p>

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
                    <p class="text-sm text-gray-500">Ending on : <br>August 4th, 2024 4:27 PM</p>
                </div>
                <!-- Bidding Info -->
                <div class="bg-gray-300 p-4 rounded-lg">
                    <p class="">Current Bid (12 Bids)</p>
                    <p class="text-xl font-bold">$150</p>
                </div>

                <p class="flex justify-between">Opening Bid Amount: <span class="font-semibold">$100</span></p>
                <p class="flex justify-between" style="margin-top: 10px;">Next Minimum Bid Amount: <span
                        class="font-semibold">$155</span></p>

                <!-- Bid Input -->
                <div class="flex space-x-3 w-full items-center">
                    <label class="w-1/2 items-center" for="">Enter Bid Amount</label>
                    <input type="number" class="w-1/2 border-2 border-gray-500 rounded-md px-2 py-1 appearance-none 
         focus:outline-none focus:ring-0 focus:border-gray-500" min="0"/>
                </div>
                <div class="flex justify-center">
                    <button class="bg-black text-white px-16 py-2 rounded-md hover:bg-gray-800">
                        Bid Now
                    </button>
                </div>

            </div>
        </section>

        <!-- Product Description -->
        <section class="max-w-6xl mx-auto p-6">
            <h3 class="text-xl font-bold mb-4">Product Description</h3><br>
            <ul class="space-y-2 mb-6">
                <li><b>Product name:</b> Natural Star Ruby</li>
                <li><b>Product category:</b> Natural</li>
                <li><b>Carat Weight:</b> 200g</li>
                <li><b>Color:</b> Purple</li>
                <li><b>Shape:</b> Star</li>
            </ul>
            <p class="text-gray-600 leading-relaxed text-justify">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Quis ipsum suspendisse ultrices gravida.
                Risus commodo viverra maecenas accumsan lacus vel facilisis.
            </p>
        </section>



</x-shop::layouts>