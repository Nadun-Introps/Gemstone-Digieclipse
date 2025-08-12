<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.bidding.bids.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.bidding.bids.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            <!-- Export Modal -->
            <x-admin::datagrid.export :src="route('admin.bidding.products.index')" />

            {!! view_render_event('bagisto.admin.bidding.products.create.before') !!}

            @if (bouncer()->hasPermission('bidding.products.create'))
                <v-create-product-form>
                    <a href="{{ route('admin.bidding.products.create') }}" class="primary-button">
                        @lang('admin::app.bidding.bids.index.create-btn')
                    </a>
                </v-create-product-form>
            @endif

            {!! view_render_event('bagisto.admin.bidding.products.create.after') !!}
        </div>
    </div>

    {!! view_render_event('bagisto.admin.bidding.products.list.before') !!}

    <!-- Custom table -->
    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="border border-gray-300 px-4 py-2">Product</th>
                <th class="border border-gray-300 px-4 py-2">Category</th>
                <th class="border border-gray-300 px-4 py-2">Price</th>
                <th class="border border-gray-300 px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $product->product }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $product->category }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ number_format($product->price, 2) }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    <a href="" class="text-blue-600 hover:underline mr-2">
                        <i class="icon-edit"></i> Edit
                    </a>
                    <form action="" method="POST" class="inline-block" onsubmit="return confirm('Are you sure want to delete this product?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">
                            <i class="icon-delete"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4">No products found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $products->links() }} {{-- Pagination links --}}
    </div>

    {!! view_render_event('bagisto.admin.bidding.products.list.after') !!}
</x-admin::layouts>
