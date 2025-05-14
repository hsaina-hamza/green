<x-role-app>
    <x-slot name="header">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                @class([
                    'text-purple-600' => Auth::user()->role === 'admin',
                    'text-blue-600' => Auth::user()->role === 'worker',
                    'text-green-600' => Auth::user()->role === 'user'
                ])>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
            <h2 @class([
                'font-semibold text-xl leading-tight',
                'text-purple-800' => Auth::user()->role === 'admin',
                'text-blue-800' => Auth::user()->role === 'worker',
                'text-green-800' => Auth::user()->role === 'user'
            ])>
                {{ __('نصائح للحفاظ على البيئة') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($tips as $tip)
                    <x-role-container class="hover:shadow-md transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-1 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        @class([
                                            'text-purple-500' => Auth::user()->role === 'admin',
                                            'text-blue-500' => Auth::user()->role === 'worker',
                                            'text-green-500' => Auth::user()->role === 'user'
                                        ])>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div class="text-right">
                                    <h3 @class([
                                        'text-lg font-medium mb-2',
                                        'text-purple-900' => Auth::user()->role === 'admin',
                                        'text-blue-900' => Auth::user()->role === 'worker',
                                        'text-green-900' => Auth::user()->role === 'user'
                                    ])>
                                        {{ $tip['title'] }}
                                    </h3>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        {{ $tip['description'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </x-role-container>
                @endforeach
            </div>

            <div class="mt-10">
                <x-role-container class="border-t-4"
                    @class([
                        'border-purple-500' => Auth::user()->role === 'admin',
                        'border-blue-500' => Auth::user()->role === 'worker',
                        'border-green-500' => Auth::user()->role === 'user'
                    ])>
                    <div class="p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    @class([
                                        'text-purple-600' => Auth::user()->role === 'admin',
                                        'text-blue-600' => Auth::user()->role === 'worker',
                                        'text-green-600' => Auth::user()->role === 'user'
                                    ])>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="text-right">
                                @if(Auth::user()->role === 'admin')
                                    <h3 class="text-lg font-medium text-purple-900">إجراءات المدير</h3>
                                    <p class="mt-2 text-gray-600 text-sm">إدارة نصائح الحفاظ على البيئة ومراقبة فعاليتها.</p>
                                @elseif(Auth::user()->role === 'worker')
                                    <h3 class="text-lg font-medium text-blue-900">موارد العاملين</h3>
                                    <p class="mt-2 text-gray-600 text-sm">الوصول إلى أدوات وإرشادات لإدارة النفايات.</p>
                                @else
                                    <h3 class="text-lg font-medium text-green-900">شارك معنا</h3>
                                    <p class="mt-2 text-gray-600 text-sm">تعلم كيف يمكنك المساهمة في جهود الحفاظ على البيئة.</p>
                                @endif

                                <div class="mt-5 text-left">
                                    <x-role-button href="{{ route('waste-reports.create') }}" class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        {{ __('الإبلاغ عن مشكلة نفايات') }}
                                    </x-role-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-role-container>
            </div>
        </div>
    </div>
</x-role-app>