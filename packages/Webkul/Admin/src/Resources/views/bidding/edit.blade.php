<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.bidding.edit.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.bidding.edit.before') !!}

    <x-admin::form method="PUT" action="{{ route('admin.bidding.update', $biddingProduct->bid_pro_id) }}"
        enctype="multipart/form-data">
        <!-- Page Header -->
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <div class="grid gap-1.5">
                <p class="text-xl font-bold leading-6 text-gray-800 dark:text-white">
                    @lang('admin::app.bidding.edit.title')
                </p>
            </div>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a href="{{ route('admin.bidding.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800">
                    @lang('admin::app.bidding.edit.back-btn')
                </a>

                <!-- Save Button -->
                <button type="submit" class="primary-button">
                    @lang('admin::app.bidding.edit.update-btn')
                </button>
            </div>
        </div>

        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Main Content Column -->
            <div class="flex-1 max-xl:flex-auto">
                <!-- Product Information -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900 mb-4">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.bidding.edit.product-info')
                    </p>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Product Name -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.bidding.edit.name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control type="text" name="product_name"
                                value="{{ old('product_name', $biddingProduct->product_name) }}"
                                placeholder="@lang('admin::app.bidding.edit.name-placeholder')" rules="required" />

                            <x-admin::form.control-group.error control-name="product_name" />
                        </x-admin::form.control-group>

                        <!-- Starting Price -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.bidding.edit.starting-price')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control type="number" name="price"
                                value="{{ old('price', $biddingProduct->price) }}" min="0" step="0.01"
                                placeholder="@lang('admin::app.bidding.edit.starting-price-placeholder')" rules="required|decimal" />

                            <x-admin::form.control-group.error control-name="price" />
                        </x-admin::form.control-group>

                        <!-- Minimum Increment -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.bidding.edit.minimum-increment')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control type="number" name="minimum_increment"
                                value="{{ old('minimum_increment', $biddingProduct->minimum_increment ?? 0) }}"
                                min="0" step="0.01" placeholder="@lang('admin::app.bidding.edit.minimum-increment-placeholder')"
                                rules="required|decimal|min:0" />

                            <x-admin::form.control-group.error control-name="minimum_increment" />
                        </x-admin::form.control-group>

                        <!-- Reserve Price -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.bidding.edit.reserve-price')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control type="number" name="reserve_price"
                                value="{{ old('reserve_price', $biddingProduct->reserve_price ?? 0) }}" min="0"
                                step="0.01" placeholder="@lang('admin::app.bidding.edit.reserve-price-placeholder')" rules="decimal|min:0" />

                            <x-admin::form.control-group.error control-name="reserve_price" />
                        </x-admin::form.control-group>
                    </div>
                </div>

                <!-- Auction Timing -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900 mb-4">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.bidding.edit.auction-timing')
                    </p>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Start Date -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.bidding.edit.start-date')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control type="date" name="start_date"
                                value="{{ old('start_date', $biddingProduct->start_date) }}" rules="required" />

                            <x-admin::form.control-group.error control-name="start_date" />
                        </x-admin::form.control-group>

                        <!-- Start Time -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.bidding.edit.start-time')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control type="time" name="start_time"
                                value="{{ old('start_time', $biddingProduct->start_time) }}" rules="required" />

                            <x-admin::form.control-group.error control-name="start_time" />
                        </x-admin::form.control-group>

                        <!-- End Date -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.bidding.edit.end-date')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control type="date" name="end_date"
                                value="{{ old('end_date', $biddingProduct->end_date) }}" rules="required" />

                            <x-admin::form.control-group.error control-name="end_date" />
                        </x-admin::form.control-group>

                        <!-- End Time -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.bidding.edit.end-time')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control type="time" name="end_time"
                                value="{{ old('end_time', $biddingProduct->end_time) }}" rules="required" />

                            <x-admin::form.control-group.error control-name="end_time" />
                        </x-admin::form.control-group>
                    </div>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="w-[360px] max-w-full max-sm:w-full">
                <!-- Status -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.bidding.edit.status')
                    </p>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.bidding.edit.status-label')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control type="select" name="status" rules="required"
                            :value="old('status', $biddingProduct->status)">
                            <option value="active">
                                @lang('admin::app.bidding.edit.status-active')
                            </option>
                            <option value="inactive">
                                @lang('admin::app.bidding.edit.status-inactive')
                            </option>
                            <option value="paused">
                                @lang('admin::app.bidding.edit.status-paused')
                            </option>
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="status" />
                    </x-admin::form.control-group>
                </div>
            </div>
        </div>
    </x-admin::form>

    {!! view_render_event('bagisto.admin.bidding.edit.after') !!}

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Set the selected option for status
                const statusSelect = document.querySelector('select[name="status"]');
                const currentStatus = "{{ old('status', $biddingProduct->status) }}";

                if (statusSelect && currentStatus) {
                    Array.from(statusSelect.options).forEach(option => {
                        if (option.value === currentStatus) {
                            option.selected = true;
                        }
                    });
                }
            });
        </script>
    @endpush
</x-admin::layouts>
