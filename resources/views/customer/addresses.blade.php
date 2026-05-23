@extends('layouts.app')

@section('title', __('general.addresses') . ' - ' . config('app.name'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ 
    showModal: false, 
    editMode: false,
    formAction: '{{ route('customer.addresses.store') }}',
    formMethod: 'POST',
    formData: {
        id: null, label: 'home', full_name: '', phone: '',
        address_line_1: '', address_line_2: '', city: '',
        district: '', division: '', postal_code: '', is_default: false
    },
    openAdd() {
        this.editMode = false;
        this.formAction = '{{ route('customer.addresses.store') }}';
        this.formMethod = 'POST';
        this.formData = { label: 'home', full_name: '', phone: '', address_line_1: '', address_line_2: '', city: '', district: '', division: '', postal_code: '', is_default: false };
        this.showModal = true;
    },
    openEdit(address) {
        this.editMode = true;
        this.formAction = '/account/addresses/' + address.id;
        this.formMethod = 'PUT';
        this.formData = { ...address, is_default: address.is_default == 1 };
        this.showModal = true;
    }
}">

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('general.addresses') }}</h1>
        <button @click="openAdd()" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition">
            {{ __('general.add_new_address') }}
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-6">
        {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-6">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($addresses->count())
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach($addresses as $address)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 relative group">
            <div class="flex items-start justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wide bg-primary-100 text-primary-700 px-2.5 py-1 rounded-full">
                    {{ $address->label->label() ?? $address->label }}
                </span>
                @if($address->is_default)
                <span class="text-xs text-green-600 font-medium">{{ __('general.default') }}</span>
                @endif
            </div>
            <p class="font-semibold text-gray-900">{{ $address->full_name }}</p>
            <p class="text-gray-500 text-sm mt-1">{{ $address->phone }}</p>
            <p class="text-gray-500 text-sm">{{ $address->address_line_1 }}@if($address->address_line_2), {{ $address->address_line_2 }}@endif</p>
            <p class="text-gray-500 text-sm">{{ $address->city }}, {{ $address->district }}, {{ $address->division }}</p>
            @if($address->postal_code)
            <p class="text-gray-400 text-sm">{{ $address->postal_code }}</p>
            @endif

            <div class="mt-4 pt-4 border-t border-gray-50 flex gap-3 opacity-0 group-hover:opacity-100 transition">
                <button @click="openEdit({{ Js::from($address) }})" class="text-sm text-primary-600 hover:text-primary-800 font-medium">{{ __('general.edit') }}</button>
                <form action="{{ route('customer.addresses.destroy', $address) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('general.delete_address_confirm') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">{{ __('general.delete') }}</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-sm p-16 text-center text-gray-400">
        <svg class="w-16 h-16 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        <p class="font-medium">{{ __('general.no_addresses_yet') }}</p>
        <p class="text-sm mt-1">{{ __('general.add_address_to_speed_up') }}</p>
    </div>
    @endif

    <!-- Address Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" @click="showModal = false" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showModal" x-transition.scale class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="formAction" method="POST">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title" x-text="editMode ? '{{ __('general.edit') }}' : '{{ __('general.add_new_address') }}'"></h3>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('general.label') }}</label>
                                <select name="label" x-model="formData.label" class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                                    <option value="home">{{ __('general.home') }}</option>
                                    <option value="office">{{ __('general.office') }}</option>
                                    <option value="other">{{ __('general.other') }}</option>
                                </select>
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" name="full_name" x-model="formData.full_name" required class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="text" name="phone" x-model="formData.phone" required class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                                <input type="text" name="address_line_1" x-model="formData.address_line_1" required class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2 (Optional)</label>
                                <input type="text" name="address_line_2" x-model="formData.address_line_2" class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input type="text" name="city" x-model="formData.city" required class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                                <input type="text" name="postal_code" x-model="formData.postal_code" class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">District</label>
                                <input type="text" name="district" x-model="formData.district" required class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Division</label>
                                <input type="text" name="division" x-model="formData.division" required class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                            </div>
                            <div class="col-span-2 mt-2">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="is_default" value="1" x-model="formData.is_default" class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    <span class="text-sm text-gray-700">{{ __('general.set_as_default') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('general.save_address_btn') }}
                        </button>
                        <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('general.cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
