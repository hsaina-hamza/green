<x-app-layout dir="rtl">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ __('تفاصيل الجدول') }}
            </h2>
            <div class="flex space-x-reverse space-x-4">
                @can('update', $schedule)
                    <a href="{{ route('admin.schedules.edit', $schedule) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('تعديل الجدول') }}
                    </a>
                @endcan
                <a href="{{ route('admin.schedules.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    {{ __('العودة للقائمة') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Schedule Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Collection Details -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4 pb-2 border-b border-gray-200 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                {{ __('تفاصيل الجمع') }}
                            </h3>
                            <dl class="grid grid-cols-1 gap-5">
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('الموقع') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <a href="{{ route('sites.show', $schedule->site) }}" class="text-green-600 hover:text-green-800 flex items-center">
                                            {{ $schedule->site->name }}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </dd>
                                    <dd class="text-sm text-gray-500 mt-1 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $schedule->site->address }}
                                    </dd>
                                </div>

                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('رقم الشاحنة') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                        </svg>
                                        <span class="mr-2">{{ $schedule->truck_number }}</span>
                                    </dd>
                                </div>

                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('وقت الجمع') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="mr-2">{{ $schedule->scheduled_time->translatedFormat('d M Y H:i') }}</span>
                                    </dd>
                                    <dd class="text-sm text-gray-500 mt-1">{{ $schedule->scheduled_time->diffForHumans() }}</dd>
                                </div>

                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('التكرار') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @switch($schedule->frequency)
                                            @case('once') مرة واحدة @break
                                            @case('daily') يومي @break
                                            @case('weekly') أسبوعي @break
                                            @case('biweekly') كل أسبوعين @break
                                            @case('monthly') شهري @break
                                        @endswitch
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Additional Information -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4 pb-2 border-b border-gray-200 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('معلومات إضافية') }}
                            </h3>
                            <dl class="grid grid-cols-1 gap-5">
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('الحالة') }}</dt>
                                    <dd class="mt-1">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($schedule->scheduled_time->isFuture()) bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $schedule->scheduled_time->isFuture() ? 'قادم' : 'منتهي' }}
                                        </span>
                                    </dd>
                                </div>

                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('تاريخ الإنشاء') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $schedule->created_at->translatedFormat('d M Y H:i') }}</dd>
                                    <dd class="text-sm text-gray-500">{{ $schedule->created_at->diffForHumans() }}</dd>
                                </div>

                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('آخر تحديث') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $schedule->updated_at->translatedFormat('d M Y H:i') }}</dd>
                                    <dd class="text-sm text-gray-500">{{ $schedule->updated_at->diffForHumans() }}</dd>
                                </div>

                                @if($schedule->notes)
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">{{ __('ملاحظات') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $schedule->notes }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    @can('delete', $schedule)
                        <div class="mt-8 pt-6 border-t border-gray-200 text-left">
                            <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <x-danger-button onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذا الجدول؟')" class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    {{ __('حذف الجدول') }}
                                </x-danger-button>
                            </form>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>