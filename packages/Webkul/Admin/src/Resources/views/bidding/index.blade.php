<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.bidding.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">

        <h2 class="text-2xl font-medium max-md:text-xl max-sm:text-base">
            @lang('admin::app.bidding.title')
        </h2>

        <div class="flex items-center gap-x-2.5">
            @if (bouncer()->hasPermission('bidding.create'))
                <a href="{{ route('admin.catalog.products.index') }}" class="primary-button">
                    @lang('admin::app.bidding.index.create-btn')
                </a>
            @endif
        </div>
    </div>

    {!! view_render_event('bagisto.admin.bidding.list.before') !!}

    <!-- Bidding Products Datagrid -->
    <x-admin::datagrid :src="route('admin.bidding.index')" :isMultiRow="true" />

    {!! view_render_event('bagisto.admin.bidding.list.after') !!}
</x-admin::layouts>
