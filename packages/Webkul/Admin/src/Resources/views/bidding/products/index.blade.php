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

            @if (bouncer()->hasPermission('catalog.products.create'))
                <v-create-product-form>
                    <a href="{{ route('admin.bidding.products.create') }}" class="primary-button">
                        @lang('admin::app.bidding.bids.index.create-btn')
                    </a>
                </v-create-product-form>
            @endif

            {!! view_render_event('bagisto.admin.bidding.products.create.after') !!}
        </div>
    </div>

    {!! view_render_event('bagisto.admin.catalog.products.list.before') !!}
    

</x-admin::layouts>
