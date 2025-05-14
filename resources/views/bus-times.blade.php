<x-app-layout dir="rtl">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('مواعيد الحافلات') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-3xl font-bold text-green-700 mb-8 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    جدول الحافلات المحلية
                </h1>

                <div class="space-y-8">
                    <!-- Morning Routes -->
                    <section>
                        <h3 class="text-xl font-semibold text-green-600 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            خطوط الصباح
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-right">الخط</th>
                                        <th class="px-4 py-2 text-right">وقت المغادرة</th>
                                        <th class="px-4 py-2 text-right">وقت الوصول</th>
                                        <th class="px-4 py-2 text-right">التكرار</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-2 text-right">الخط 1</td>
                                        <td class="px-4 py-2 text-right">6:00 صباحاً</td>
                                        <td class="px-4 py-2 text-right">7:30 صباحاً</td>
                                        <td class="px-4 py-2 text-right">كل 15 دقيقة</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2 text-right">الخط 2</td>
                                        <td class="px-4 py-2 text-right">6:30 صباحاً</td>
                                        <td class="px-4 py-2 text-right">8:00 صباحاً</td>
                                        <td class="px-4 py-2 text-right">كل 20 دقيقة</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- Afternoon Routes -->
                    <section>
                        <h3 class="text-xl font-semibold text-green-600 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                            خطوط الظهيرة
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-right">الخط</th>
                                        <th class="px-4 py-2 text-right">وقت المغادرة</th>
                                        <th class="px-4 py-2 text-right">وقت الوصول</th>
                                        <th class="px-4 py-2 text-right">التكرار</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-2 text-right">الخط 1</td>
                                        <td class="px-4 py-2 text-right">12:00 ظهراً</td>
                                        <td class="px-4 py-2 text-right">1:30 ظهراً</td>
                                        <td class="px-4 py-2 text-right">كل 15 دقيقة</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2 text-right">الخط 2</td>
                                        <td class="px-4 py-2 text-right">12:30 ظهراً</td>
                                        <td class="px-4 py-2 text-right">2:00 ظهراً</td>
                                        <td class="px-4 py-2 text-right">كل 20 دقيقة</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- Evening Routes -->
                    <section>
                        <h3 class="text-xl font-semibold text-green-600 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            خطوط المساء
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-right">الخط</th>
                                        <th class="px-4 py-2 text-right">وقت المغادرة</th>
                                        <th class="px-4 py-2 text-right">وقت الوصول</th>
                                        <th class="px-4 py-2 text-right">التكرار</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-2 text-right">الخط 1</td>
                                        <td class="px-4 py-2 text-right">5:00 مساءً</td>
                                        <td class="px-4 py-2 text-right">6:30 مساءً</td>
                                        <td class="px-4 py-2 text-right">كل 15 دقيقة</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2 text-right">الخط 2</td>
                                        <td class="px-4 py-2 text-right">5:30 مساءً</td>
                                        <td class="px-4 py-2 text-right">7:00 مساءً</td>
                                        <td class="px-4 py-2 text-right">كل 20 دقيقة</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <div class="mt-8 p-4 bg-green-50 rounded-lg border border-green-100">
                        <p class="text-green-800 flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>
                                <strong>ملاحظة:</strong> قد يختلف الجدول في أيام العطل والأعياد. يرجى مراجعة سلطة النقل المحلية للحصول على التحديثات في الوقت الفعلي.
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>