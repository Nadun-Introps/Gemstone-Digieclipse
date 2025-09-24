<!-- Page Layout -->
<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.bidding.success.title')
    </x-slot>

    <!-- Hero Banner -->
    <section class="relative w-full h-52 bg-black">
        <img src="" alt="Gems" class="absolute w-full h-full object-cover opacity-70">
        <div class="absolute top-8 left-10">
            <h1 class="text-4xl font-bold text-white">Bid Successful</h1>
        </div>
    </section>

    <!-- Success Content -->
    <section class="max-w-4xl mx-auto p-8 text-center">
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="text-green-500 text-6xl mb-4">✓</div>
            <h2 class="text-2xl font-bold mb-4">Your Bid Has Been Placed Successfully!</h2>

            <p class="text-gray-600 mb-6">
                Thank you for participating in our auction. Your bid has been recorded and will be
                active until the auction ends. You will be notified if you win the auction.
            </p>

            <div class="flex justify-center gap-4">
                <a href="{{ route('shop.bidinglist.biding_list') }}"
                    class="bg-navyBlue text-white px-6 py-2 rounded-md hover:bg-blue-700">
                    View Other Auctions
                </a>
                <a href="{{ route('shop.home.index') }}"
                    class="border border-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-100">
                    Continue Shopping
                </a>
            </div>
        </div>
    </section>
</x-shop::layouts>
