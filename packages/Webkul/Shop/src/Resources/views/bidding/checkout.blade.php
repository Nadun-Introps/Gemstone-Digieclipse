<!-- Page Layout -->
<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.bidding.checkout.title')
    </x-slot>

    <!-- Checkout Content -->
    <section class="max-w-6xl mx-auto p-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Review Your Bid</h2>

            <!-- Bid Summary -->
            <div class="border-b border-gray-200 pb-4 mb-6">
                <h3 class="text-lg font-semibold mb-2">Bid Details</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600">Product:</p>
                        <p class="font-semibold">{{ $bid['product_name'] }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Bid Amount:</p>
                        <p class="font-semibold">Rs. {{ number_format($bid['bid_amount'], 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Auction Ends:</p>
                        <p class="font-semibold">{{ date('F jS, Y g:i A', strtotime($bid['auction_end'])) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Bid ID:</p>
                        <p class="font-semibold">{{ $bid['sku'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Address Section -->
            {!! view_render_event('bagisto.shop.checkout.onepage.address.before') !!}

            <!-- Accordion Blade Component -->
            <x-shop::accordion
                class="mb-7 overflow-hidden rounded-xl !border-b-0 max-md:mb-0 max-md:rounded-lg max-md:!border-none max-md:!bg-gray-100">
                <!-- Accordion Header Component Slot -->
                <x-slot:header
                    class="!p-0 max-md:!mb-0 max-md:rounded-t-md max-md:!p-3 max-md:text-sm max-md:font-medium max-sm:!p-2">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-medium max-md:text-base">
                            @lang('shop::app.checkout.onepage.address.title')
                        </h2>
                    </div>
                </x-slot>

                <!-- Accordion Content Component Slot -->
                <x-slot:content
                    class="mt-8 !p-0 max-md:mt-0 max-md:rounded-t-none max-md:border max-md:border-t-0 max-md:!p-4">
                    <!-- Customer Address Vue Component -->
                    <v-bidding-checkout-address :customer="{{ auth()->guard('customer')->user() ? 'true' : 'false' }}"
                        @processing="stepForward" @processed="stepProcessed">
                        <!-- Billing Address Shimmer -->
                        <x-shop::shimmer.checkout.onepage.address />
                    </v-bidding-checkout-address>
                </x-slot:content>
            </x-shop::accordion>

            {!! view_render_event('bagisto.shop.checkout.onepage.address.after') !!}

            <!-- Payment Information -->
            <div class="mb-6 mt-8">
                <h3 class="text-lg font-semibold mb-4">Payment Information</h3>
                <p class="text-gray-600 mb-4">
                    By completing this checkout, you agree to pay the bid amount.
                    Your bid will be recorded immediately after payment confirmation.
                </p>

                <!-- Stripe Payment Element -->
                <div id="stripe-payment-section" class="bg-gray-100 p-4 rounded-lg">
                    <div id="stripe-payment-element">
                        <!-- Stripe will inject the Payment Element here -->
                    </div>
                    <div id="stripe-payment-errors" class="text-red-500 text-sm mt-2"></div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('shop.bidding.checkout.cancel') }}"
                    class="text-red-600 hover:text-red-800 font-semibold">
                    Cancel Bid
                </a>

                <form action="{{ route('shop.bidding.checkout.process') }}" method="POST" id="bidding-checkout-form">
                    @csrf
                    <input type="hidden" name="billing_address_id" id="billing_address_id" value="">
                    <button type="submit" class="bg-navyBlue text-white px-6 py-2 rounded-md hover:bg-blue-700"
                        id="confirm-pay-btn" disabled>
                        Confirm & Pay
                    </button>
                </form>
            </div>
        </div>
    </section>

    @pushOnce('scripts')
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            const stripe = Stripe('{{ app(\Webkul\Payment\Payment\Stripe::class)->getPublishableKey() }}');

            // Initialize Stripe
            async function initializeStripe() {
                try {
                    const response = await fetch('{{ route('shop.stripe.bidding.process_payment') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const {
                        clientSecret,
                        paymentIntentId
                    } = await response.json();

                    const elements = stripe.elements({
                        clientSecret
                    });
                    const paymentElement = elements.create('payment');
                    paymentElement.mount('#stripe-payment-element');

                    // Handle form submission
                    const form = document.getElementById('bidding-checkout-form');
                    form.addEventListener('submit', async (event) => {
                        event.preventDefault();

                        const button = document.getElementById('confirm-pay-btn');
                        button.disabled = true;
                        button.innerHTML = 'Processing...';

                        const {
                            error
                        } = await stripe.confirmPayment({
                            elements,
                            confirmParams: {
                                return_url: '{{ route('shop.stripe.bidding.success') }}?payment_intent=' +
                                    paymentIntentId,
                            },
                        });

                        if (error) {
                            document.getElementById('stripe-payment-errors').textContent = error.message;
                            button.disabled = false;
                            button.innerHTML = 'Confirm & Pay';
                        }
                    });

                } catch (error) {
                    console.error('Error initializing Stripe:', error);
                }
            }

            // Initialize when page loads
            document.addEventListener('DOMContentLoaded', initializeStripe);
        </script>

        <script
            type="text/x-template"
            id="v-bidding-checkout-address-template"
        >
            <template v-if="isLoading">
                <!-- Billing Address Shimmer -->
                <x-shop::shimmer.checkout.onepage.address />
            </template>

            <template v-else>
                <!-- Saved Addresses -->
                <template v-if="!activeAddressForm && customerSavedAddresses.billing.length">
                    <x-shop::form
                        v-slot="{ meta, errors, handleSubmit }"
                        as="div"
                    >
                        <form @submit="handleSubmit($event, addAddressToBid)">
                            <!-- Billing Address Header -->
                            <div class="mb-4 flex items-center justify-between max-md:mb-2">
                                <h2 class="text-xl font-medium max-sm:text-base max-sm:font-normal">
                                    @lang('shop::app.checkout.onepage.address.billing-address')
                                </h2>
                            </div>

                            <!-- Saved Customer Addresses Cards -->
                            <div class="mb-2 grid grid-cols-2 gap-5 max-1060:grid-cols-[1fr] max-lg:grid-cols-2 max-md:mt-2 max-md:grid-cols-1">
                                <div
                                    class="relative max-w-[414px] cursor-pointer select-none rounded-xl border border-zinc-200 p-0 max-md:flex-wrap max-md:rounded-lg"
                                    v-for="address in customerSavedAddresses.billing"
                                >
                                    <!-- Actions -->
                                    <div class="absolute top-5 flex gap-2 ltr:right-5 rtl:left-5">
                                        <x-shop::form.control-group class="!mb-0 flex items-center gap-2.5">
                                            <x-shop::form.control-group.control
                                                type="radio"
                                                name="billing.id"
                                                ::id="`billing_address_id_${address.id}`"
                                                ::for="`billing_address_id_${address.id}`"
                                                ::value="address.id"
                                                v-model="selectedAddresses.billing_address_id"
                                                rules="required"
                                                label="{{ trans('shop::app.checkout.onepage.address.billing-address') }}"
                                                @change="enablePaymentButton"
                                            />
                                        </x-shop::form.control-group>

                                        <!-- Edit Icon -->
                                        <span
                                            class="icon-edit cursor-pointer text-2xl"
                                            @click="
                                                selectedAddressForEdit = address;
                                                activeAddressForm = 'billing';
                                                saveAddress = address.address_type == 'customer'
                                            "
                                        ></span>
                                    </div>

                                    <!-- Details -->
                                    <label
                                        class="block cursor-pointer rounded-xl p-5 max-md:rounded-lg"
                                        :for="`billing_address_id_${address.id}`"
                                    >
                                        <span class="icon-checkout-address text-6xl text-navyBlue max-sm:text-5xl"></span>

                                        <div class="flex items-center justify-between">
                                            <p class="text-base font-medium">
                                                @{{ address.first_name + ' ' + address.last_name }}

                                                <template v-if="address.company_name">
                                                    (@{{ address.company_name }})
                                                </template>
                                            </p>
                                        </div>

                                        <p class="mt-6 text-sm text-zinc-500 max-md:mt-2 max-sm:mt-0">
                                            <template v-if="address.address">
                                                @{{ address.address.join(', ') }},
                                            </template>

                                            @{{ address.city }},
                                            @{{ address.state }}, @{{ address.country }},
                                            @{{ address.postcode }}
                                        </p>
                                    </label>
                                </div>

                                <!-- New Address Card -->
                                <div
                                    class="flex max-w-[414px] cursor-pointer items-center justify-center rounded-xl border border-zinc-200 p-5 max-md:flex-wrap max-md:rounded-lg"
                                    @click="activeAddressForm = 'billing'"
                                >
                                    <div
                                        class="flex items-center gap-x-2.5"
                                        role="button"
                                        tabindex="0"
                                    >
                                        <span
                                            class="icon-plus rounded-full border border-black p-2.5 text-3xl max-sm:p-2"
                                            role="presentation"
                                        ></span>

                                        <p class="text-base">@lang('shop::app.checkout.onepage.address.add-new-address')</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Error Message Block -->
                            <x-shop::form.control-group.error name="billing.id" />
                        </form>
                    </x-shop::form>
                </template>

                <!-- Create/Edit Address Form -->
                <template v-else>
                    <x-shop::form
                        v-slot="{ meta, errors, handleSubmit }"
                        as="div"
                    >
                        <form @submit="handleSubmit($event, updateOrCreateAddress)">
                            <!-- Billing Address Header -->
                            <div class="mb-4 flex items-center justify-between">
                                <h2 class="text-xl font-medium max-md:text-base max-sm:font-normal">
                                    @lang('shop::app.checkout.onepage.address.billing-address')
                                </h2>

                                <span
                                    class="flex cursor-pointer justify-end"
                                    v-show="customerSavedAddresses.billing.length"
                                    @click="selectedAddressForEdit = null; activeAddressForm = null"
                                >
                                    <span class="icon-arrow-left text-2xl max-md:hidden"></span>

                                    @lang('shop::app.checkout.onepage.address.back')
                                </span>
                            </div>

                            <!-- Address Form Vue Component -->
                            <v-checkout-address-form
                                control-name="billing"
                                :address="selectedAddressForEdit || undefined"
                            ></v-checkout-address-form>

                            <!-- Save Address to Address Book Checkbox -->
                            <x-shop::form.control-group class="!mb-0 flex items-center gap-2.5">
                                <x-shop::form.control-group.control
                                    type="checkbox"
                                    name="billing.save_address"
                                    id="save_address"
                                    for="save_address"
                                    value="1"
                                    v-model="saveAddress"
                                    @change="saveAddress = ! saveAddress"
                                />

                                <label
                                    class="cursor-pointer select-none text-base text-zinc-500 max-md:text-sm max-sm:text-xs ltr:pl-0 rtl:pr-0"
                                    for="save_address"
                                >
                                    @lang('shop::app.checkout.onepage.address.save-address')
                                </label>
                            </x-shop::form.control-group>

                            <!-- Save Button -->
                            <div class="mt-4 flex justify-end">
                                <x-shop::button
                                    class="primary-button rounded-2xl px-11 py-3 max-md:rounded-lg max-sm:w-full max-sm:max-w-full max-sm:py-1.5"
                                    :title="trans('shop::app.checkout.onepage.address.save')"
                                    ::loading="isStoring"
                                    ::disabled="isStoring"
                                />
                            </div>
                        </form>
                    </x-shop::form>
                </template>
            </template>
        </script>

        <script type="module">
            app.component('v-bidding-checkout-address', {
                template: '#v-bidding-checkout-address-template',

                props: ['customer'],

                data() {
                    return {
                        customerSavedAddresses: {
                            'billing': [],
                        },

                        activeAddressForm: null,

                        selectedAddressForEdit: null,

                        saveAddress: false,

                        selectedAddresses: {
                            billing_address_id: null,
                        },

                        isLoading: true,

                        isStoring: false,
                    }
                },

                mounted() {
                    if (this.customer) {
                        this.getCustomerSavedAddresses();
                    } else {
                        this.isLoading = false;
                        this.activeAddressForm = 'billing';
                    }
                },

                methods: {
                    getCustomerSavedAddresses() {
                        this.$axios.get('{{ route('shop.api.customers.account.addresses.index') }}')
                            .then(response => {
                                this.customerSavedAddresses.billing = structuredClone(response.data.data);

                                if (!this.customerSavedAddresses.billing.length) {
                                    this.activeAddressForm = 'billing';
                                }

                                this.isLoading = false;
                            })
                            .catch((error) => {
                                console.error(error);
                                this.isLoading = false;
                                this.activeAddressForm = 'billing';
                            });
                    },

                    enablePaymentButton() {
                        if (this.selectedAddresses.billing_address_id) {
                            document.getElementById('billing_address_id').value = this.selectedAddresses
                                .billing_address_id;
                            document.getElementById('confirm-pay-btn').disabled = false;
                        }
                    },

                    updateOrCreateAddress(params, {
                        setErrors
                    }) {
                        params = params.billing;

                        if (!this.customer) {
                            // For guest users, just set the address and enable payment
                            document.getElementById('confirm-pay-btn').disabled = false;
                            this.activeAddressForm = null;
                            return;
                        }

                        let address = this.customerSavedAddresses.billing.find(address => {
                            return address.id == params.id;
                        });

                        if (!address) {
                            if (params.save_address) {
                                this.createCustomerAddress(params, {
                                        setErrors
                                    })
                                    .then((response) => {
                                        this.addAddressToList(response.data.data);
                                        this.enablePaymentButton();
                                    })
                                    .catch((error) => {});
                            } else {
                                this.addAddressToList(params);
                                this.enablePaymentButton();
                            }

                            return;
                        }

                        if (params.save_address) {
                            if (address.address_type == 'customer') {
                                this.updateCustomerAddress(params.id, params, {
                                        setErrors
                                    })
                                    .then((response) => {
                                        this.updateAddressInList(response.data.data);
                                        this.enablePaymentButton();
                                    })
                                    .catch((error) => {});
                            } else {
                                this.removeAddressFromList(params);

                                this.createCustomerAddress(params, {
                                        setErrors
                                    })
                                    .then((response) => {
                                        this.addAddressToList(response.data.data);
                                        this.enablePaymentButton();
                                    })
                                    .catch((error) => {});
                            }
                        } else {
                            this.updateAddressInList(params);
                            this.enablePaymentButton();
                        }
                    },

                    addAddressToList(address) {
                        this.customerSavedAddresses.billing.unshift(address);
                        this.selectedAddresses.billing_address_id = address.id;
                        this.activeAddressForm = null;
                    },

                    updateAddressInList(params) {
                        this.customerSavedAddresses.billing.forEach((address, index) => {
                            if (address.id == params.id) {
                                params = {
                                    ...address,
                                    ...params,
                                };

                                this.customerSavedAddresses.billing[index] = params;
                                this.selectedAddresses.billing_address_id = params.id;
                                this.activeAddressForm = null;
                            }
                        });
                    },

                    removeAddressFromList(params) {
                        this.customerSavedAddresses.billing = this.customerSavedAddresses.billing.filter(address =>
                            address.id != params.id);
                    },

                    createCustomerAddress(params, {
                        setErrors
                    }) {
                        this.isStoring = true;

                        return this.$axios.post('{{ route('shop.api.customers.account.addresses.store') }}', params)
                            .then((response) => {
                                this.isStoring = false;
                                return response;
                            })
                            .catch(error => {
                                this.isStoring = false;

                                if (error.response.status == 422) {
                                    let errors = {};

                                    Object.keys(error.response.data.errors).forEach(key => {
                                        errors['billing.' + key] = error.response.data.errors[key];
                                    });

                                    setErrors(errors);
                                }

                                return Promise.reject(error);
                            });
                    },

                    updateCustomerAddress(id, params, {
                        setErrors
                    }) {
                        this.isStoring = true;

                        return this.$axios.put('{{ route('shop.api.customers.account.addresses.update') }}/' + id,
                                params)
                            .then((response) => {
                                this.isStoring = false;
                                return response;
                            })
                            .catch(error => {
                                this.isStoring = false;

                                if (error.response.status == 422) {
                                    let errors = {};

                                    Object.keys(error.response.data.errors).forEach(key => {
                                        errors['billing.' + key] = error.response.data.errors[key];
                                    });

                                    setErrors(errors);
                                }

                                return Promise.reject(error);
                            });
                    },

                    addAddressToBid(params, {
                        setErrors
                    }) {
                        document.getElementById('billing_address_id').value = this.selectedAddresses.billing_address_id;
                        document.getElementById('confirm-pay-btn').disabled = false;
                    },
                }
            });
        </script>

        @include('shop::checkout.onepage.address.form')
    @endPushOnce
</x-shop::layouts>
