<x-admin::layouts>
    <x-slot:title>
        <h1>Bidding Products</h1>
    </x-slot>

    <!-- Page Header -->
    <div class="grid gap-2.5">
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <div class="grid gap-1.5">
                <p class="text-xl font-bold leading-6 text-gray-800 dark:text-white">
                    Save Bidding Product
                </p>
            </div>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('admin.bidding.products.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    Back
                </a>

                <!-- Save Button -->
                <button type="submit" form="bidding-product-form" class="primary-button">
                    Save Bidding Product
                </button>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form id="bidding-product-form" action="{{ route('admin.bidding.products.store') }}" method="POST" enctype="multipart/form-data" class="mt-5">
        @csrf

        <div class="grid gap-5">
            <!-- General Information -->
            <x-admin::accordion>
                <x-slot:title>General Information</x-slot:title>

                <x-admin::form.control-group>
                    <x-admin::form.control-group.label>
                        Product Name
                    </x-admin::form.control-group.label>
                    <x-admin::form.control-group.control
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                    />
                </x-admin::form.control-group>

                <x-admin::form.control-group>
                    <x-admin::form.control-group.label>
                        Category
                    </x-admin::form.control-group.label>
                    <x-admin::form.control-group.control
                        type="text"
                        name="category"
                        value="{{ old('category') }}"
                        required
                    />
                </x-admin::form.control-group>

                <x-admin::form.control-group>
                    <x-admin::form.control-group.label>
                        Status
                    </x-admin::form.control-group.label>
                    <x-admin::form.control-group.control
                        type="select"
                        name="status"
                        :options="['active' => 'Active', 'inactive' => 'Inactive']"
                        value="{{ old('status', 'active') }}"
                    />
                </x-admin::form.control-group>
            </x-admin::accordion>

            <!-- Pricing -->
            <x-admin::accordion>
                <x-slot:title>Pricing</x-slot:title>

                <x-admin::form.control-group>
                    <x-admin::form.control-group.label>
                        Price
                    </x-admin::form.control-group.label>
                    <x-admin::form.control-group.control
                        type="number"
                        name="price"
                        value="{{ old('price') }}"
                        step="0.01"
                        required
                    />
                </x-admin::form.control-group>

                <x-admin::form.control-group>
                    <x-admin::form.control-group.label>
                        Starting Bid
                    </x-admin::form.control-group.label>
                    <x-admin::form.control-group.control
                        type="number"
                        name="starting_bid"
                        value="{{ old('starting_bid') }}"
                        step="0.01"
                        required
                    />
                </x-admin::form.control-group>
            </x-admin::accordion>

            <!-- Images -->
            <x-admin::accordion>
                <x-slot:title>Product Images</x-slot:title>

                <x-admin::form.control-group>
                    <x-admin::form.control-group.label>
                        Upload Images
                    </x-admin::form.control-group.label>
                    <x-admin::form.control-group.control
                        type="file"
                        name="images[]"
                        multiple
                        accept="image/*"
                    />
                </x-admin::form.control-group>
            </x-admin::accordion>

            <!-- Description -->
            <x-admin::accordion>
                <x-slot:title>Description</x-slot:title>

                <x-admin::form.control-group>
                    <x-admin::form.control-group.control
                        type="textarea"
                        name="description"
                        value="{{ old('description') }}"
                        rows="5"
                    />
                </x-admin::form.control-group>
            </x-admin::accordion>
        </div>
    </form>
</x-admin::layouts>
