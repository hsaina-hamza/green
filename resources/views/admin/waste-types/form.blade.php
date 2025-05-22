<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ isset($wasteType) ? __('تعديل نوع النفايات') : __('إضافة نوع نفايات جديد') }}
            </h2>
            <a href="{{ route('admin.waste-types.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                <i class="fas fa-arrow-right ml-2"></i>
                {{ __('عودة') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ isset($wasteType) ? route('admin.waste-types.update', $wasteType) : route('admin.waste-types.store') }}">
                        @csrf
                        @if(isset($wasteType))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-label for="name" :value="__('الاسم بالإنجليزية')" />
                                <x-input id="name" type="text" name="name" :value="old('name', $wasteType->name ?? '')" required autofocus class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Arabic Name -->
                            <div>
                                <x-label for="name_ar" :value="__('الاسم بالعربية')" />
                                <x-input id="name_ar" type="text" name="name_ar" :value="old('name_ar', $wasteType->name_ar ?? '')" required class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('name_ar')" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div>
                                <x-label for="description" :value="__('الوصف بالإنجليزية')" />
                                <textarea id="description" name="description" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="3">{{ old('description', $wasteType->description ?? '') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <!-- Arabic Description -->
                            <div>
                                <x-label for="description_ar" :value="__('الوصف بالعربية')" />
                                <textarea id="description_ar" name="description_ar" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="3">{{ old('description_ar', $wasteType->description_ar ?? '') }}</textarea>
                                <x-input-error :messages="$errors->get('description_ar')" class="mt-2" />
                            </div>

                            <!-- Icon -->
                            <div>
                                <x-label for="icon" :value="__('الأيقونة (Font Awesome)')" />
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                        fas fa-
                                    </span>
                                    <x-input id="icon" type="text" name="icon" :value="old('icon', isset($wasteType) ? str_replace('fas fa-', '', $wasteType->icon) : '')" required class="rounded-none rounded-l-md" placeholder="trash-alt" />
                                </div>
                                <x-input-error :messages="$errors->get('icon')" class="mt-2" />
                                <div class="mt-2 text-sm text-gray-500">
                                    {{ __('مثال: trash-alt, recycle, leaf') }}
                                </div>
                            </div>

                            <!-- Color -->
                            <div>
                                <x-label for="color" :value="__('اللون')" />
                                <x-input id="color" type="color" name="color" :value="old('color', $wasteType->color ?? '#000000')" required class="mt-1 block w-full h-10" />
                                <x-input-error :messages="$errors->get('color')" class="mt-2" />
                            </div>

                            <!-- Is Active -->
                            <div class="col-span-2">
                                <label class="inline-flex items-center mt-3">
                                    <input type="checkbox" name="is_active" class="form-checkbox h-5 w-5 text-gray-600" {{ old('is_active', $wasteType->is_active ?? true) ? 'checked' : '' }}>
                                    <span class="mr-2 text-gray-700">{{ __('نشط') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-button class="mr-4">
                                {{ isset($wasteType) ? __('تحديث') : __('إضافة') }}
                            </x-button>
                            <a href="{{ route('admin.waste-types.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('إلغاء') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Preview icon as user types
        const iconInput = document.getElementById('icon');
        const iconPreview = document.createElement('i');
        iconPreview.className = 'fas fa-' + iconInput.value;
        iconInput.parentNode.appendChild(iconPreview);

        iconInput.addEventListener('input', function() {
            iconPreview.className = 'fas fa-' + this.value;
        });

        // Update icon color when color input changes
        const colorInput = document.getElementById('color');
        iconPreview.style.color = colorInput.value;

        colorInput.addEventListener('input', function() {
            iconPreview.style.color = this.value;
        });
    </script>
    @endpush
</x-app-layout>
