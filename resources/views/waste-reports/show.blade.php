<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('تفاصيل بلاغ النفايات') }}
            </h2>
            <div class="flex space-x-4">
                @can('update', $wasteReport)
                    <a href="{{ route('waste-reports.edit', $wasteReport) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 flex items-center shadow-md hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        {{ __('تعديل البلاغ') }}
                    </a>
                @endcan
                <a href="{{ route('waste-reports.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 flex items-center shadow-md hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    {{ __('عودة للقائمة') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded-lg relative flex items-center shadow-sm" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="mr-2">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                <div class="p-8 text-gray-900">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Report Information Section -->
                        <div>
                            <div class="flex items-center mb-6">
                                <div class="h-10 w-1 bg-blue-500 rounded-full mr-3"></div>
                                <h3 class="text-xl font-semibold text-gray-800">{{ __('معلومات البلاغ') }}</h3>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 hover:border-blue-100 transition-all duration-300">
                                    <dt class="text-xs font-medium text-gray-500 tracking-wider">{{ __('نوع النفايات') }}</dt>
                                    <dd class="mt-1 text-base font-medium text-gray-800">{{ $wasteReport->waste_type }}</dd>
                                </div>
                                
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 hover:border-blue-100 transition-all duration-300">
                                    <dt class="text-xs font-medium text-gray-500 tracking-wider">{{ __('الكمية') }}</dt>
                                    <dd class="mt-1 text-base font-medium text-gray-800">{{ $wasteReport->quantity }} {{ __($wasteReport->unit) }}</dd>
                                </div>
                                
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 hover:border-blue-100 transition-all duration-300">
                                    <dt class="text-xs font-medium text-gray-500 tracking-wider">{{ __('الموقع') }}</dt>
                                    <dd class="mt-1 text-base font-medium text-gray-800">{{ $wasteReport->location }}</dd>
                                </div>
                                
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 hover:border-blue-100 transition-all duration-300">
                                    <dt class="text-xs font-medium text-gray-500 tracking-wider">{{ __('حالة البلاغ') }}</dt>
                                    <dd class="mt-1">
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                            @if($wasteReport->status === 'resolved') bg-emerald-100 text-emerald-800
                                            @elseif($wasteReport->status === 'in_progress') bg-amber-100 text-amber-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ __(ucfirst(str_replace('_', ' ', $wasteReport->status))) }}
                                        </span>
                                    </dd>
                                </div>
                                
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 hover:border-blue-100 transition-all duration-300">
                                    <dt class="text-xs font-medium text-gray-500 tracking-wider">{{ __('مقدم البلاغ') }}</dt>
                                    <dd class="mt-1 text-base font-medium text-gray-800">{{ $wasteReport->user ? $wasteReport->user->name : 'مجهول' }}</dd>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 hover:border-blue-100 transition-all duration-300">
                                        <dt class="text-xs font-medium text-gray-500 tracking-wider">{{ __('تاريخ الإبلاغ') }}</dt>
                                        <dd class="mt-1 text-sm font-medium text-gray-800">{{ $wasteReport->created_at->translatedFormat('d F Y H:i') }}</dd>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 hover:border-blue-100 transition-all duration-300">
                                        <dt class="text-xs font-medium text-gray-500 tracking-wider">{{ __('آخر تحديث') }}</dt>
                                        <dd class="mt-1 text-sm font-medium text-gray-800">{{ $wasteReport->updated_at->translatedFormat('d F Y H:i') }}</dd>
                                    </div>
                                </div>
                            </div>

                            @can('updateStatus', $wasteReport)
                                <div class="mt-8 bg-blue-50 p-5 rounded-xl border border-blue-100">
                                    <div class="flex items-center mb-3">
                                        <div class="h-8 w-1 bg-blue-500 rounded-full mr-3"></div>
                                        <h4 class="text-lg font-semibold text-blue-800">{{ __('تحديث حالة البلاغ') }}</h4>
                                    </div>
                                    <form action="{{ route('waste-reports.update-status', $wasteReport) }}" method="POST" class="flex gap-3">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm w-full">
                                            <option value="pending" {{ $wasteReport->status === 'pending' ? 'selected' : '' }}>{{ __('معلق') }}</option>
                                            <option value="in_progress" {{ $wasteReport->status === 'in_progress' ? 'selected' : '' }}>{{ __('قيد المعالجة') }}</option>
                                            <option value="resolved" {{ $wasteReport->status === 'resolved' ? 'selected' : '' }}>{{ __('تم الحل') }}</option>
                                        </select>
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 flex items-center shadow-md hover:shadow-lg whitespace-nowrap">
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
                        <div>
                            @if($wasteReport->description)
                                <div class="flex items-center mb-6">
                                    <div class="h-10 w-1 bg-blue-500 rounded-full mr-3"></div>
                                    <h3 class="text-xl font-semibold text-gray-800">{{ __('وصف البلاغ') }}</h3>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-5 mb-8 border border-gray-100 shadow-sm">
                                    <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $wasteReport->description }}</p>
                                </div>
                            @endif

                            @if($wasteReport->image_path)
                                <div class="flex items-center mb-6">
                                    <div class="h-10 w-1 bg-blue-500 rounded-full mr-3"></div>
                                    <h3 class="text-xl font-semibold text-gray-800">{{ __('صورة البلاغ') }}</h3>
                                </div>
                                <div class="relative group overflow-hidden rounded-xl shadow-lg">
                                    <img src="{{ Storage::url($wasteReport->image_path) }}" 
                                         alt="{{ __('صورة البلاغ') }}"
                                         class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                                        <span class="text-white text-sm font-medium">{{ __('صورة توضيحية للبلاغ') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @can('delete', $wasteReport)
                        <div class="mt-10 pt-6 border-t border-gray-200 flex justify-end">
                            <form action="{{ route('waste-reports.destroy', $wasteReport) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 flex items-center shadow-md hover:shadow-lg"
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