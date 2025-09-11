@php
    $channel = core()->getCurrentChannel();
@endphp

<!-- SEO Meta Content -->
@push ('meta')
    <meta
        name="title"
        content="{{ $channel->home_seo['meta_title'] ?? '' }}"
    />

    <meta
        name="description"
        content="{{ $channel->home_seo['meta_description'] ?? '' }}"
    />

    <meta
        name="keywords"
        content="{{ $channel->home_seo['meta_keywords'] ?? '' }}"
    />
@endPush

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
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* 2 cards per row */
            gap: 20px;
            padding: 10px;
        }

        /* Remove horizontal scroll and snap properties */
        .live-auctions-grid::-webkit-scrollbar {
            display: none;
        }
        .live-auction-card {
            flex: 0 0 calc(33.333% - 20px);
            /* 3 cards per view */
            border: 1px solid #eee;
            border-radius: 8px;
            background: #fff;
            padding: 15px;
            display: flex;
            gap: 15px;
            transition: box-shadow 0.3s ease;
        }
        .live-auction-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .live-auction-img {
            flex-shrink: 0;
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

        @media (max-width: 992px) {
            .live-auctions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 600px) {
            .live-auctions-grid {
                grid-template-columns: repeat(1, 1fr);
            }
        }
    </style>
@endpush

<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{  $channel->home_seo['meta_title'] ?? '' }}
    </x-slot>
    <div class="container px-[60px] max-lg:px-8 max-sm:px-4">
        <div class="flex items-start gap-10 max-lg:gap-5 md:mt-10">
                <div class="panel-side journal-scroll grid max-h-[1320px] min-w-[342px] grid-cols-[1fr] overflow-y-auto overflow-x-hidden max-xl:min-w-[270px] md:max-w-[342px] md:ltr:pr-7 md:rtl:pl-7" style="border: 1px solid darkgrey;padding: 15px;">
                <div class="flex h-[50px] items-center justify-between border-b border-zinc-200 pb-2.5 max-md:hidden">
                    <p class="text-lg font-semibold max-sm:font-medium"> Filters: </p>
                    <p class="cursor-pointer text-xs font-medium" tabindex="0"> Clear All </p>
                </div>
                <div class="border-b border-zinc-200 last:border-b-0">
                    <div>
                        <div class="flex cursor-pointer select-none items-center justify-between p-4 px-0 py-2.5 max-sm:!pb-1.5" role="button" tabindex="0">
                            <div class="flex items-center justify-between">
                            <p class="text-lg font-semibold max-sm:text-base max-sm:font-medium">Price</p>
                            </div>
                            <span class="icon-arrow-up text-2xl" role="button" aria-label="Toggle accordion" tabindex="0"></span>
                        </div>
                        <div class="z-10 rounded-lg bg-white p-1.5 !p-0">
                            <ul>
                            <li>
                                <div>
                                    <div>
                                        <div class="flex items-center gap-4">
                                        <p class="text-base max-sm:text-sm"> Range: </p>
                                        <p class="text-base font-semibold max-sm:text-sm">$0.00 - $180,000.00</p>
                                        </div>
                                        <div class="relative mx-auto flex h-20 w-full items-center justify-center p-2">
                                        <div class="relative h-1 w-full rounded-2xl bg-gray-200">
                                            <div class="absolute left-1/4 right-0 h-full rounded-xl bg-navyBlue" style="left: 0%; right: 0%;"></div>
                                            <span><input step="1" type="range" class="pointer-events-none absolute h-1 w-full cursor-pointer appearance-none bg-transparent outline-none [&amp;::-moz-range-thumb]:pointer-events-auto [&amp;::-moz-range-thumb]:h-[18px] [&amp;::-moz-range-thumb]:w-[18px] [&amp;::-moz-range-thumb]:appearance-none [&amp;::-moz-range-thumb]:rounded-full [&amp;::-moz-range-thumb]:bg-white [&amp;::-moz-range-thumb]:ring [&amp;::-moz-range-thumb]:ring-navyBlue [&amp;::-ms-thumb]:pointer-events-auto [&amp;::-ms-thumb]:h-[18px] [&amp;::-ms-thumb]:w-[18px] [&amp;::-ms-thumb]:appearance-none [&amp;::-ms-thumb]:rounded-full [&amp;::-ms-thumb]:bg-white [&amp;::-ms-thumb]:ring [&amp;::-ms-thumb]:ring-navyBlue [&amp;::-webkit-slider-thumb]:pointer-events-auto [&amp;::-webkit-slider-thumb]:h-[18px] [&amp;::-webkit-slider-thumb]:w-[18px] [&amp;::-webkit-slider-thumb]:appearance-none [&amp;::-webkit-slider-thumb]:rounded-full [&amp;::-webkit-slider-thumb]:bg-white [&amp;::-webkit-slider-thumb]:ring [&amp;::-webkit-slider-thumb]:ring-navyBlue" min="0" max="180000" aria-label="Min Range" value="0"></span><span><input step="1" type="range" class="pointer-events-none absolute h-1 w-full cursor-pointer appearance-none bg-transparent outline-none [&amp;::-moz-range-thumb]:pointer-events-auto [&amp;::-moz-range-thumb]:h-[18px] [&amp;::-moz-range-thumb]:w-[18px] [&amp;::-moz-range-thumb]:appearance-none [&amp;::-moz-range-thumb]:rounded-full [&amp;::-moz-range-thumb]:bg-white [&amp;::-moz-range-thumb]:ring [&amp;::-moz-range-thumb]:ring-navyBlue [&amp;::-ms-thumb]:pointer-events-auto [&amp;::-ms-thumb]:h-[18px] [&amp;::-ms-thumb]:w-[18px] [&amp;::-ms-thumb]:appearance-none [&amp;::-ms-thumb]:rounded-full [&amp;::-ms-thumb]:bg-white [&amp;::-ms-thumb]:ring [&amp;::-ms-thumb]:ring-navyBlue [&amp;::-webkit-slider-thumb]:pointer-events-auto [&amp;::-webkit-slider-thumb]:h-[18px] [&amp;::-webkit-slider-thumb]:w-[18px] [&amp;::-webkit-slider-thumb]:appearance-none [&amp;::-webkit-slider-thumb]:rounded-full [&amp;::-webkit-slider-thumb]:bg-white [&amp;::-webkit-slider-thumb]:ring [&amp;::-webkit-slider-thumb]:ring-navyBlue" min="0" max="180000" aria-label="Max Range" value="180000"></span>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="border-b border-zinc-200 last:border-b-0">
                    <div>
                        <div class="flex cursor-pointer select-none items-center justify-between p-4 px-0 py-2.5 max-sm:!pb-1.5" role="button" tabindex="0">
                            <div class="flex items-center justify-between">
                            <p class="text-lg font-semibold max-sm:text-base max-sm:font-medium">Color</p>
                            </div>
                            <span class="icon-arrow-up text-2xl" role="button" aria-label="Toggle accordion" tabindex="0"></span>
                        </div>
                        <div class="z-10 rounded-lg bg-white p-1.5 !p-0">
                            <div class="flex flex-col gap-1">
                            <div class="relative">
                                <div class="icon-search pointer-events-none absolute top-3 flex items-center text-2xl max-md:text-xl max-sm:top-2.5 ltr:left-3 rtl:right-3"></div>
                                <input type="text" class="block w-full rounded-xl border border-zinc-200 px-11 py-3.5 text-sm font-medium text-gray-900 max-md:rounded-lg max-md:px-10 max-md:py-3 max-md:font-normal max-sm:text-xs" placeholder="Search">
                            </div>
                            <p class="mt-1 flex flex-row-reverse text-xs text-gray-600">Showing 5 of 5 options</p>
                            </div>
                            <ul class="pb-3 text-base text-gray-700">
                            <li>
                                <div class="flex select-none items-center gap-x-4 rounded hover:bg-gray-100 max-sm:gap-x-1 max-sm:!p-0 ltr:pl-2 rtl:pr-2"><input type="checkbox" id="filter_23_option_ 1" class="peer hidden" value="1"><label class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-navyBlue peer-checked:text-navyBlue max-sm:text-xl" role="checkbox" aria-checked="false" aria-label="Red" aria-labelledby="label_option_1" tabindex="0" for="filter_23_option_ 1"></label><label class="w-full cursor-pointer p-2 text-base text-gray-900 max-sm:p-1 max-sm:text-sm ltr:pl-0 rtl:pr-0" id="label_option_1" for="filter_23_option_ 1" role="button" tabindex="0">Red</label></div>
                            </li>
                            <li>
                                <div class="flex select-none items-center gap-x-4 rounded hover:bg-gray-100 max-sm:gap-x-1 max-sm:!p-0 ltr:pl-2 rtl:pr-2"><input type="checkbox" id="filter_23_option_ 2" class="peer hidden" value="2"><label class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-navyBlue peer-checked:text-navyBlue max-sm:text-xl" role="checkbox" aria-checked="false" aria-label="Green" aria-labelledby="label_option_2" tabindex="0" for="filter_23_option_ 2"></label><label class="w-full cursor-pointer p-2 text-base text-gray-900 max-sm:p-1 max-sm:text-sm ltr:pl-0 rtl:pr-0" id="label_option_2" for="filter_23_option_ 2" role="button" tabindex="0">Green</label></div>
                            </li>
                            <li>
                                <div class="flex select-none items-center gap-x-4 rounded hover:bg-gray-100 max-sm:gap-x-1 max-sm:!p-0 ltr:pl-2 rtl:pr-2"><input type="checkbox" id="filter_23_option_ 3" class="peer hidden" value="3"><label class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-navyBlue peer-checked:text-navyBlue max-sm:text-xl" role="checkbox" aria-checked="false" aria-label="Yellow" aria-labelledby="label_option_3" tabindex="0" for="filter_23_option_ 3"></label><label class="w-full cursor-pointer p-2 text-base text-gray-900 max-sm:p-1 max-sm:text-sm ltr:pl-0 rtl:pr-0" id="label_option_3" for="filter_23_option_ 3" role="button" tabindex="0">Yellow</label></div>
                            </li>
                            <li>
                                <div class="flex select-none items-center gap-x-4 rounded hover:bg-gray-100 max-sm:gap-x-1 max-sm:!p-0 ltr:pl-2 rtl:pr-2"><input type="checkbox" id="filter_23_option_ 4" class="peer hidden" value="4"><label class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-navyBlue peer-checked:text-navyBlue max-sm:text-xl" role="checkbox" aria-checked="false" aria-label="Black" aria-labelledby="label_option_4" tabindex="0" for="filter_23_option_ 4"></label><label class="w-full cursor-pointer p-2 text-base text-gray-900 max-sm:p-1 max-sm:text-sm ltr:pl-0 rtl:pr-0" id="label_option_4" for="filter_23_option_ 4" role="button" tabindex="0">Black</label></div>
                            </li>
                            <li>
                                <div class="flex select-none items-center gap-x-4 rounded hover:bg-gray-100 max-sm:gap-x-1 max-sm:!p-0 ltr:pl-2 rtl:pr-2"><input type="checkbox" id="filter_23_option_ 5" class="peer hidden" value="5"><label class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-navyBlue peer-checked:text-navyBlue max-sm:text-xl" role="checkbox" aria-checked="false" aria-label="White" aria-labelledby="label_option_5" tabindex="0" for="filter_23_option_ 5"></label><label class="w-full cursor-pointer p-2 text-base text-gray-900 max-sm:p-1 max-sm:text-sm ltr:pl-0 rtl:pr-0" id="label_option_5" for="filter_23_option_ 5" role="button" tabindex="0">White</label></div>
                            </li>
                            </ul>
                            <!---->
                        </div>
                    </div>
                </div>
                <div class="border-b border-zinc-200 last:border-b-0">
                    <div>
                        <div class="flex cursor-pointer select-none items-center justify-between p-4 px-0 py-2.5 max-sm:!pb-1.5" role="button" tabindex="0">
                            <div class="flex items-center justify-between">
                            <p class="text-lg font-semibold max-sm:text-base max-sm:font-medium">Size</p>
                            </div>
                            <span class="icon-arrow-up text-2xl" role="button" aria-label="Toggle accordion" tabindex="0"></span>
                        </div>
                        <div class="z-10 rounded-lg bg-white p-1.5 !p-0">
                            <div class="flex flex-col gap-1">
                            <div class="relative">
                                <div class="icon-search pointer-events-none absolute top-3 flex items-center text-2xl max-md:text-xl max-sm:top-2.5 ltr:left-3 rtl:right-3"></div>
                                <input type="text" class="block w-full rounded-xl border border-zinc-200 px-11 py-3.5 text-sm font-medium text-gray-900 max-md:rounded-lg max-md:px-10 max-md:py-3 max-md:font-normal max-sm:text-xs" placeholder="Search">
                            </div>
                            <p class="mt-1 flex flex-row-reverse text-xs text-gray-600">Showing 4 of 4 options</p>
                            </div>
                            <ul class="pb-3 text-base text-gray-700">
                            <li>
                                <div class="flex select-none items-center gap-x-4 rounded hover:bg-gray-100 max-sm:gap-x-1 max-sm:!p-0 ltr:pl-2 rtl:pr-2"><input type="checkbox" id="filter_24_option_ 6" class="peer hidden" value="6"><label class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-navyBlue peer-checked:text-navyBlue max-sm:text-xl" role="checkbox" aria-checked="false" aria-label="S" aria-labelledby="label_option_6" tabindex="0" for="filter_24_option_ 6"></label><label class="w-full cursor-pointer p-2 text-base text-gray-900 max-sm:p-1 max-sm:text-sm ltr:pl-0 rtl:pr-0" id="label_option_6" for="filter_24_option_ 6" role="button" tabindex="0">S</label></div>
                            </li>
                            <li>
                                <div class="flex select-none items-center gap-x-4 rounded hover:bg-gray-100 max-sm:gap-x-1 max-sm:!p-0 ltr:pl-2 rtl:pr-2"><input type="checkbox" id="filter_24_option_ 7" class="peer hidden" value="7"><label class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-navyBlue peer-checked:text-navyBlue max-sm:text-xl" role="checkbox" aria-checked="false" aria-label="M" aria-labelledby="label_option_7" tabindex="0" for="filter_24_option_ 7"></label><label class="w-full cursor-pointer p-2 text-base text-gray-900 max-sm:p-1 max-sm:text-sm ltr:pl-0 rtl:pr-0" id="label_option_7" for="filter_24_option_ 7" role="button" tabindex="0">M</label></div>
                            </li>
                            <li>
                                <div class="flex select-none items-center gap-x-4 rounded hover:bg-gray-100 max-sm:gap-x-1 max-sm:!p-0 ltr:pl-2 rtl:pr-2"><input type="checkbox" id="filter_24_option_ 8" class="peer hidden" value="8"><label class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-navyBlue peer-checked:text-navyBlue max-sm:text-xl" role="checkbox" aria-checked="false" aria-label="L" aria-labelledby="label_option_8" tabindex="0" for="filter_24_option_ 8"></label><label class="w-full cursor-pointer p-2 text-base text-gray-900 max-sm:p-1 max-sm:text-sm ltr:pl-0 rtl:pr-0" id="label_option_8" for="filter_24_option_ 8" role="button" tabindex="0">L</label></div>
                            </li>
                            <li>
                                <div class="flex select-none items-center gap-x-4 rounded hover:bg-gray-100 max-sm:gap-x-1 max-sm:!p-0 ltr:pl-2 rtl:pr-2"><input type="checkbox" id="filter_24_option_ 9" class="peer hidden" value="9"><label class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-navyBlue peer-checked:text-navyBlue max-sm:text-xl" role="checkbox" aria-checked="false" aria-label="XL" aria-labelledby="label_option_9" tabindex="0" for="filter_24_option_ 9"></label><label class="w-full cursor-pointer p-2 text-base text-gray-900 max-sm:p-1 max-sm:text-sm ltr:pl-0 rtl:pr-0" id="label_option_9" for="filter_24_option_ 9" role="button" tabindex="0">XL</label></div>
                            </li>
                            </ul>
                            <!---->
                        </div>
                    </div>
                </div>
                <div class="border-b border-zinc-200 last:border-b-0">
                    <div>
                        <div class="flex cursor-pointer select-none items-center justify-between p-4 px-0 py-2.5 max-sm:!pb-1.5" role="button" tabindex="0">
                            <div class="flex items-center justify-between">
                            <p class="text-lg font-semibold max-sm:text-base max-sm:font-medium">Brand</p>
                            </div>
                            <span class="icon-arrow-up text-2xl" role="button" aria-label="Toggle accordion" tabindex="0"></span>
                        </div>
                        <div class="z-10 rounded-lg bg-white p-1.5 !p-0">
                            <div class="flex flex-col gap-1">
                            <div class="relative">
                                <div class="icon-search pointer-events-none absolute top-3 flex items-center text-2xl max-md:text-xl max-sm:top-2.5 ltr:left-3 rtl:right-3"></div>
                                <input type="text" class="block w-full rounded-xl border border-zinc-200 px-11 py-3.5 text-sm font-medium text-gray-900 max-md:rounded-lg max-md:px-10 max-md:py-3 max-md:font-normal max-sm:text-xs" placeholder="Search">
                            </div>
                            <!---->
                            </div>
                            <ul class="pb-3 text-base text-gray-700">
                            <li class="flex flex-col items-center justify-center gap-2 py-2"> No options available. </li>
                            </ul>
                            <!---->
                        </div>
                    </div>
                </div>
            </div>

            <!----------------------section bid card ------------->

            <div class="flex-1">
                <div class="max-md:hidden">
                    <div>
                        <div class="flex justify-between max-md:hidden">
                            <div class="relative z-[1]">
                            <div class="select-none"><button class="flex w-full max-w-[200px] cursor-pointer items-center justify-between gap-4 rounded-lg border border-zinc-200 bg-white p-3.5 text-base transition-all hover:border-gray-400 focus:border-gray-400 max-md:w-[110px] max-md:border-0 max-md:pl-2.5 max-md:pr-2.5">Expensive First <span class="icon-arrow-down text-2xl" role="presentation"></span></button></div>
                            <div class="absolute z-20 w-max rounded-[20px] bg-white shadow-[0px_10px_84px_rgba(0,0,0,0.1)] max-md:rounded-lg" style="min-width: 184px; top: 54px; left: 0px; display: none;" tag="div">
                                <ul class="py-4">
                                    <li class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100 max-sm:text-sm">From A-Z</li>
                                    <li class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100 max-sm:text-sm">From Z-A</li>
                                    <li class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100 max-sm:text-sm">Newest First</li>
                                    <li class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100 max-sm:text-sm">Oldest First</li>
                                    <li class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100 max-sm:text-sm">Cheapest First</li>
                                    <li class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100 max-sm:text-sm bg-gray-100">Expensive First</li>
                                </ul>
                            </div>
                            </div>
                            <div class="flex items-center gap-10">
                            <div class="relative">
                                <div class="select-none"><button class="flex w-full max-w-[200px] cursor-pointer items-center justify-between gap-4 rounded-lg border border-zinc-200 bg-white p-3.5 text-base transition-all hover:border-gray-400 focus:border-gray-400 max-md:w-[110px] max-md:border-0 max-md:pl-2.5 max-md:pr-2.5">12 <span class="icon-arrow-down text-2xl" role="presentation"></span></button></div>
                                <div class="absolute z-20 w-max rounded-[20px] bg-white shadow-[0px_10px_84px_rgba(0,0,0,0.1)] max-md:rounded-lg" style="min-width: 84px; top: 54px; right: 0px; display: none;" tag="div">
                                    <ul class="py-4">
                                        <li class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100 max-sm:text-sm bg-gray-100">12</li>
                                        <li class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100 max-sm:text-sm">24</li>
                                        <li class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100 max-sm:text-sm">36</li>
                                        <li class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100 max-sm:text-sm">48</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="flex items-center gap-5"><span class="cursor-pointer text-2xl icon-listing" role="button" aria-label="List" tabindex="0"></span><span class="cursor-pointer text-2xl icon-grid-view-fill" role="button" aria-label="Grid" tabindex="0"></span></div>
                            </div>
                        </div>
                        <div class="md:hidden">
                            <ul>
                            <li class="px-4 py-2.5">From A-Z</li>
                            <li class="px-4 py-2.5">From Z-A</li>
                            <li class="px-4 py-2.5">Newest First</li>
                            <li class="px-4 py-2.5">Oldest First</li>
                            <li class="px-4 py-2.5">Cheapest First</li>
                            <li class="px-4 py-2.5 bg-gray-100">Expensive First</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="live-auctions-container">
                        <!--<h2>Live <strong>Auctions</strong></h2>-->

                        <!--<div class="icon-arrow-left-stylish rtl:icon-arrow-right-stylish inline-block cursor-pointer text-2xl max-lg:hidden arrow_icon_left" onclick="slideLeft()"></div>
                        <div class="icon-arrow-right-stylish rtl:icon-arrow-left-stylish inline-block cursor-pointer text-2xl max-lg:hidden arrow_icon_right" onclick="slideRight()"></div>-->
                        <div class="live-auctions-grid scrollbar-hide" id="auctionSlider">
                            <div class="live-auction-card">
                                <div class="live-auction-img">
                                    <img src="storage/theme/13/z2mSLmpIzO7P0OdOPMiTgHwAqpXfWRdqu3evH2WU.webp" alt="Blue Siphire">
                                </div>
                                <div class="live-auction-details">
                                    <h3>Vighnaharta Gold And Rhodium Plated Alloy</h3>

                                    <div class="star-rating">★★★★★</div>
                                    <div class="price">$600 – $655</div>
                                    <div class="countdown">
                                        <div><span>894</span>DAYS</div>
                                        <div><span>18</span>HRS</div>
                                        <div><span>34</span>MIN</div>
                                        <div><span>15</span>SEC</div>
                                    </div>
                                </div>
                            </div>
                            <div class="live-auction-card">
                                <div class="live-auction-img">
                                    <img src="storage/theme/13/z2mSLmpIzO7P0OdOPMiTgHwAqpXfWRdqu3evH2WU.webp" alt="Blue Siphire">
                                </div>
                                <div class="live-auction-details">
                                    <h3>Beebeecraft Gold Plated Thereader Earrings</h3>

                                    <div class="star-rating">★★★★★</div>
                                    <div class="price">$600 – $655</div>
                                    <div class="countdown">
                                        <div><span>894</span>DAYS</div>
                                        <div><span>18</span>HRS</div>
                                        <div><span>34</span>MIN</div>
                                        <div><span>15</span>SEC</div>
                                    </div>
                                </div>
                            </div>
                            <div class="live-auction-card">
                                <div class="live-auction-img">
                                    <img src="storage/theme/13/z2mSLmpIzO7P0OdOPMiTgHwAqpXfWRdqu3evH2WU.webp" alt="Blue Siphire">
                                </div>
                                <div class="live-auction-details">
                                    <h3>Floweret Cluster Diamond Stud Earrings</h3>

                                    <div class="star-rating">★★★★★</div>
                                    <div class="price">$600 – $655</div>
                                    <div class="countdown">
                                        <div><span>894</span>DAYS</div>
                                        <div><span>18</span>HRS</div>
                                        <div><span>34</span>MIN</div>
                                        <div><span>15</span>SEC</div>
                                    </div>
                                </div>
                            </div>
                            <div class="live-auction-card">
                                <div class="live-auction-img">
                                    <img src="storage/theme/13/z2mSLmpIzO7P0OdOPMiTgHwAqpXfWRdqu3evH2WU.webp" alt="Blue Siphire">
                                </div>
                                <div class="live-auction-details">
                                    <h3>Malabar Gold and Diamonds Yellow Gold Ring</h3>

                                    <div class="star-rating">★★★★★</div>
                                    <div class="price">$600 – $655</div>
                                    <div class="countdown">
                                        <div><span>894</span>DAYS</div>
                                        <div><span>18</span>HRS</div>
                                        <div><span>34</span>MIN</div>
                                        <div><span>15</span>SEC</div>
                                    </div>
                                </div>
                            </div>
                            <div class="live-auction-card">
                                <div class="live-auction-img">
                                    <img src="storage/theme/13/z2mSLmpIzO7P0OdOPMiTgHwAqpXfWRdqu3evH2WU.webp" alt="Blue Siphire">
                                </div>
                                <div class="live-auction-details">
                                    <h3>Kisna Real Diamond Jewellery Gold Diamond...</h3>

                                    <div class="star-rating">★★★★★</div>
                                    <div class="price">$239 – $249</div>
                                    <div class="countdown">
                                        <div><span>920</span>DAYS</div>
                                        <div><span>18</span>HRS</div>
                                        <div><span>34</span>MIN</div>
                                        <div><span>15</span>SEC</div>
                                    </div>
                                </div>
                            </div>
                            <div class="live-auction-card">
                                <div class="live-auction-img">
                                    <img src="storage/theme/13/z2mSLmpIzO7P0OdOPMiTgHwAqpXfWRdqu3evH2WU.webp" alt="Blue Siphire">
                                </div>
                                <div class="live-auction-details">
                                    <h3>Sally Round Diamond Engagement Gold Ring</h3>

                                    <div class="star-rating">★★★★★</div>
                                    <div class="price">$34 – $38</div>
                                    <div class="countdown">
                                        <div><span>905</span>DAYS</div>
                                        <div><span>18</span>HRS</div>
                                        <div><span>34</span>MIN</div>
                                        <div><span>14</span>SEC</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!---->
            </div>
        </div>
    </div>

</x-shop::layouts>
