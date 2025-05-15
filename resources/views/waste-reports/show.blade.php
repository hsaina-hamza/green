<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('تفاصيل بلاغ النفايات') }}
            </h2>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                @can('update', $wasteReport)
                    <a href="{{ route('waste-reports.edit', $wasteReport) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 flex items-center justify-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        {{ __('تعديل البلاغ') }}
                    </a>
                @endcan
                <a href="{{ route('waste-reports.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 flex items-center justify-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    {{ __('عودة للقائمة') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Animated Success Alert -->
            @if (session('success'))
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform translate-y-4"
                     class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-lg shadow-lg flex items-center justify-between">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-500 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-emerald-700 font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Main Content Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl transition-all duration-300 hover:shadow-2xl">
                <div class="p-6 md:p-8 text-gray-900">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Report Information Section -->
                        <div class="space-y-6">
                            <div class="flex items-center mb-2">
                                <div class="h-10 w-1 bg-gradient-to-b from-blue-500 to-blue-300 rounded-full mr-3"></div>
                                <h3 class="text-xl font-semibold text-gray-800">{{ __('معلومات البلاغ') }}</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Waste Type -->
                                <div class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-xl border border-gray-100 hover:border-blue-100 transition-all duration-300 shadow-sm hover:shadow-md">
                                    <dt class="text-xs font-medium text-gray-500 tracking-wider">{{ __('نوع النفايات') }}</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-800">{{ $wasteReport->waste_type }}</dd>
                                </div>
                                
                                <!-- Quantity -->
                                <div class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-xl border border-gray-100 hover:border-blue-100 transition-all duration-300 shadow-sm hover:shadow-md">
                                    <dt class="text-xs font-medium text-gray-500 tracking-wider">{{ __('الكمية') }}</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-800">{{ $wasteReport->quantity }} {{ __($wasteReport->unit) }}</dd>
                                </div>
                                
                                <!-- Location -->
                                <div class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-xl border border-gray-100 hover:border-blue-100 transition-all duration-300 shadow-sm hover:shadow-md col-span-2">
                                    <dt class="text-xs font-medium text-gray-500 tracking-wider">{{ __('الموقع') }}</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-800">{{ $wasteReport->location->name }}</dd>
                                </div>
                                
                                <!-- Status -->
                                <div class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-xl border border-gray-100 hover:border-blue-100 transition-all duration-300 shadow-sm hover:shadow-md">
                                    <dt class="text-xs font-medium text-gray-500 tracking-wider">{{ __('حالة البلاغ') }}</dt>
                                    <dd class="mt-1">
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                            @if($wasteReport->status === 'resolved') bg-gradient-to-r from-emerald-100 to-emerald-50 text-emerald-800
                                            @elseif($wasteReport->status === 'in_progress') bg-gradient-to-r from-amber-100 to-amber-50 text-amber-800
                                            @else bg-gradient-to-r from-gray-100 to-gray-50 text-gray-800 @endif">
                                            {{ __(ucfirst(str_replace('_', ' ', $wasteReport->status))) }}
                                        </span>
                                    </dd>
                                </div>
                                
                                <!-- Reporter -->
                                <div class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-xl border border-gray-100 hover:border-blue-100 transition-all duration-300 shadow-sm hover:shadow-md">
                                    <dt class="text-xs font-medium text-gray-500 tracking-wider">{{ __('مقدم البلاغ') }}</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-800">{{ $wasteReport->reporter ? $wasteReport->reporter->name : 'مجهول' }}</dd>
                                </div>
                                
                                <!-- Dates -->
                                <div class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-xl border border-gray-100 hover:border-blue-100 transition-all duration-300 shadow-sm hover:shadow-md">
                                    <dt class="text-xs font-medium text-gray-500 tracking-wider">{{ __('تاريخ الإبلاغ') }}</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-800">{{ $wasteReport->created_at->translatedFormat('d F Y H:i') }}</dd>
                                </div>
                                <div class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-xl border border-gray-100 hover:border-blue-100 transition-all duration-300 shadow-sm hover:shadow-md">
                                    <dt class="text-xs font-medium text-gray-500 tracking-wider">{{ __('آخر تحديث') }}</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-800">{{ $wasteReport->updated_at->translatedFormat('d F Y H:i') }}</dd>
                                </div>
                            </div>

                            <!-- Status Update Form -->
                            @can('updateStatus', $wasteReport)
                                <div class="mt-6 bg-gradient-to-br from-blue-50 to-blue-100 p-5 rounded-xl border border-blue-200 shadow-sm">
                                    <div class="flex items-center mb-3">
                                        <div class="h-8 w-1 bg-gradient-to-b from-blue-500 to-blue-300 rounded-full mr-3"></div>
                                        <h4 class="text-lg font-semibold text-blue-800">{{ __('تحديث حالة البلاغ') }}</h4>
                                    </div>
                                    <form action="{{ route('waste-reports.update-status', $wasteReport) }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm w-full">
                                            <option value="pending" {{ $wasteReport->status === 'pending' ? 'selected' : '' }}>{{ __('معلق') }}</option>
                                            <option value="in_progress" {{ $wasteReport->status === 'in_progress' ? 'selected' : '' }}>{{ __('قيد المعالجة') }}</option>
                                            <option value="resolved" {{ $wasteReport->status === 'resolved' ? 'selected' : '' }}>{{ __('تم الحل') }}</option>
                                        </select>
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 flex items-center justify-center shadow-md hover:shadow-lg whitespace-nowrap transform hover:-translate-y-0.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            {{ __('تأكيد التحديث') }}
                                        </button>
                                    </form>
                                </div>
                            @endcan
                        </div>

                        <!-- Description and Image Section -->
                        <div class="space-y-6">
                            @if($wasteReport->description)
                                <div class="flex items-center mb-2">
                                    <div class="h-10 w-1 bg-gradient-to-b from-blue-500 to-blue-300 rounded-full mr-3"></div>
                                    <h3 class="text-xl font-semibold text-gray-800">{{ __('وصف البلاغ') }}</h3>
                                </div>
                                <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
                                    <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $wasteReport->description }}</p>
                                </div>
                            @endif

                            @if($wasteReport->image_path)
                                <div class="flex items-center mb-2">
                                    <div class="h-10 w-1 bg-gradient-to-b from-blue-500 to-blue-300 rounded-full mr-3"></div>
                                    <h3 class="text-xl font-semibold text-gray-800">{{ __('صورة البلاغ') }}</h3>
                                </div>
                                <div class="relative group overflow-hidden rounded-xl shadow-lg transition-all duration-500 hover:shadow-xl">
                                    <img src="{{ Storage::url($wasteReport->image_path) }}" 
                                         alt="{{ __('صورة البلاغ') }}"
                                         class="w-full h-auto max-h-96 object-cover transition-transform duration-500 group-hover:scale-105">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                                        <span class="text-white text-sm font-medium">{{ __('صورة توضيحية للبلاغ') }}</span>
                                    </div>
                                    <a href="{{ Storage::url($wasteReport->image_path) }}" target="_blank" 
                                       class="absolute top-2 right-2 bg-white/80 hover:bg-white text-gray-800 p-2 rounded-full shadow-md transition-all duration-300 opacity-0 group-hover:opacity-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Delete Button -->
                    @can('delete', $wasteReport)
                        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                            <form action="{{ route('waste-reports.destroy', $wasteReport) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 flex items-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                                    onclick="return confirm('{{ __('هل أنت متأكد من رغبتك في حذف هذا البلاغ؟ سيتم حذف جميع البيانات المرتبطة به.') }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ __('حذف البلاغ') }}
                                </button>
                            </form>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>