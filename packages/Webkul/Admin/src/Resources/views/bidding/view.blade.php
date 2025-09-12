<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.bidding.view.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.bidding.view.before') !!}

    <!-- Page Header -->
    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <div class="grid gap-1.5">
            <p class="text-xl font-bold leading-6 text-gray-800 dark:text-white">
                @lang('admin::app.bidding.view.title')
            </p>
        </div>

        <div class="flex items-center gap-x-2.5">
            <!-- Back Button -->
            <a href="{{ route('admin.bidding.index') }}"
                class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800">
                @lang('admin::app.bidding.view.back-btn')
            </a>

            <!-- Edit Button -->
            @if (bouncer()->hasPermission('bidding.edit'))
                <a href="{{ route('admin.bidding.edit', $biddingProduct->bid_pro_id) }}" class="primary-button">
                    @lang('admin::app.bidding.view.edit-btn')
                </a>
            @endif
        </div>
    </div>

    <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
        <!-- Main Content Column -->
        <div class="flex-1 max-xl:flex-auto">
            <!-- Product Information -->
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900 mb-4">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('admin::app.bidding.view.product-info')
                </p>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Product Name -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-300">
                            @lang('admin::app.bidding.view.name')
                        </label>
                        <p class="text-base text-gray-800 dark:text-white">
                            {{ $biddingProduct->product_name }}
                        </p>
                    </div>

                    <!-- Starting Price -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-300">
                            @lang('admin::app.bidding.view.starting-price')
                        </label>
                        <p class="text-base text-gray-800 dark:text-white">
                            {{ core()->formatPrice($biddingProduct->price) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Auction Timing -->
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900 mb-4">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('admin::app.bidding.view.auction-timing')
                </p>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Start Date -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-300">
                            @lang('admin::app.bidding.view.start-date')
                        </label>
                        <p class="text-base text-gray-800 dark:text-white">
                            {{ $biddingProduct->start_date }}
                        </p>
                    </div>

                    <!-- Start Time -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-300">
                            @lang('admin::app.bidding.view.start-time')
                        </label>
                        <p class="text-base text-gray-800 dark:text-white">
                            {{ $biddingProduct->start_time }}
                        </p>
                    </div>

                    <!-- End Date -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-300">
                            @lang('admin::app.bidding.view.end-date')
                        </label>
                        <p class="text-base text-gray-800 dark:text-white">
                            {{ $biddingProduct->end_date }}
                        </p>
                    </div>

                    <!-- End Time -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-300">
                            @lang('admin::app.bidding.view.end-time')
                        </label>
                        <p class="text-base text-gray-800 dark:text-white">
                            {{ $biddingProduct->end_time }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="w-[360px] max-w-full max-sm:w-full">
            <!-- Status -->
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('admin::app.bidding.view.status')
                </p>

                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-300">
                        @lang('admin::app.bidding.view.status-label')
                    </label>
                    <p class="text-base text-gray-800 dark:text-white">
                        @php
                            $statusLabels = [
                                'active' => trans('admin::app.bidding.view.status-active'),
                                'inactive' => trans('admin::app.bidding.view.status-inactive'),
                                'paused' => trans('admin::app.bidding.view.status-paused'),
                                'deleted' => trans('admin::app.bidding.view.status-deleted'),
                            ];
                        @endphp
                        {{ $statusLabels[$biddingProduct->status] ?? ucfirst($biddingProduct->status) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {!! view_render_event('bagisto.admin.bidding.view.after') !!}
</x-admin::layouts>
