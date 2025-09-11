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
                <a href="{{ route('admin.bidding.create') }}" class="primary-button">
                    @lang('admin::app.bidding.index.create-btn')
                </a>
            @endif
        </div>
    </div>

    {!! view_render_event('bagisto.admin.bidding.list.before') !!}

    <!-- Bidding Products Datagrid -->
    <x-admin::datagrid :src="route('admin.bidding.index')" :isMultiRow="true" />

    <!-- Edit Modal -->
    <x-admin::modal ref="editBiddingModal">
        <x-slot:title>
            @lang('admin::app.bidding.edit-title')
        </x-slot:title>

        <x-slot:content>
            <form @submit.prevent="submitEdit">
                <!-- Product Name -->
                <x-admin::form.control-group>
                    <x-admin::form.control-group.label class="required">
                        @lang('admin::app.bidding.index.datagrid.product_name')
                    </x-admin::form.control-group.label>

                    <input type="text" v-model="editForm.name" class="control" required />
                    <x-admin::form.control-group.error control-name="name" />
                </x-admin::form.control-group>

                <!-- Starting Price -->
                <x-admin::form.control-group>
                    <x-admin::form.control-group.label class="required">
                        @lang('admin::app.bidding.index.datagrid.starting_price')
                    </x-admin::form.control-group.label>

                    <input type="number" v-model="editForm.starting_price" class="control" step="0.01"
                        min="0" required />
                    <x-admin::form.control-group.error control-name="starting_price" />
                </x-admin::form.control-group>

                <!-- Status -->
                <x-admin::form.control-group>
                    <x-admin::form.control-group.label class="required">
                        @lang('admin::app.bidding.index.datagrid.status')
                    </x-admin::form.control-group.label>

                    <select v-model="editForm.status" class="control" required>
                        <option value="1">@lang('admin::app.bidding.index.datagrid.active')</option>
                        <option value="0">@lang('admin::app.bidding.index.datagrid.inactive')</option>
                    </select>
                    <x-admin::form.control-group.error control-name="status" />
                </x-admin::form.control-group>

                <div class="flex justify-end gap-2 mt-4">
                    <x-admin::button button-type="button" class="transparent-button"
                        @click="$refs.editBiddingModal.toggle()" :title="trans('admin::app.bidding.index.datagrid.cancel-btn')" />
                    <x-admin::button type="submit" class="primary-button" :title="trans('admin::app.bidding.index.datagrid.save-btn')" />
                </div>
            </form>
        </x-slot:content>
    </x-admin::modal>

    {!! view_render_event('bagisto.admin.bidding.list.after') !!}

    @push('scripts')
        <script>
            app.$axios = axios;

            document.addEventListener('DOMContentLoaded', function() {
                if (!app) return;

                // reactive form data
                app.editForm = {
                    id: null,
                    name: '',
                    starting_price: 0,
                    status: 1
                };

                // Open modal with data
                window.openEditModal = function(id) {
                    app.$axios.get(`/admin/bidding/edit/${id}`)
                        .then(response => {
                            const data = response.data; // your controller should return JSON
                            app.editForm.id = data.bid_pro_id;
                            app.editForm.name = data.name;
                            app.editForm.starting_price = data.starting_price;
                            app.editForm.status = data.status;

                            app.$refs.editBiddingModal.toggle();
                        })
                        .catch(err => {
                            console.error('Failed to fetch bidding data:', err);
                        });
                }

                // Submit updated bidding
                app.submitEdit = function() {
                    app.$axios.post(`/admin/bidding/edit/${app.editForm.id}`, app.editForm)
                        .then(res => {
                            app.$refs.editBiddingModal.toggle();
                            app.$emitter.emit('add-flash', {
                                type: 'success',
                                message: res.data.message
                            });
                            // optionally reload datagrid
                        })
                        .catch(err => {
                            console.error('Failed to update bidding:', err);
                        });
                }
            });
        </script>
    @endpush
</x-admin::layouts>
