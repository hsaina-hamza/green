<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('جداول الحافلات') }}
        </h2>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(Auth::check() && Auth::user()->hasAnyRole(['admin', 'worker']))
                        <div class="mb-4">
                            <a href="{{ route('bus-times.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                إضافة جدول جديد
                            </a>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border-b">الموقع</th>
                                    <th class="py-2 px-4 border-b">المسار</th>
                                    <th class="py-2 px-4 border-b">وقت المغادرة</th>
                                    <th class="py-2 px-4 border-b">وقت الوصول</th>
                                    <th class="py-2 px-4 border-b">التكرار</th>
                                    @if(Auth::check() && Auth::user()->hasAnyRole(['admin', 'worker']))
                                        <th class="py-2 px-4 border-b">الإجراءات</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($busSchedules as $schedule)
                                    <tr>
                                        <td class="py-2 px-4 border-b text-center">{{ $schedule->location->name }}</td>
                                        <td class="py-2 px-4 border-b text-center">{{ $schedule->route }}</td>
                                        <td class="py-2 px-4 border-b text-center">{{ $schedule->departure_time }}</td>
                                        <td class="py-2 px-4 border-b text-center">{{ $schedule->arrival_time }}</td>
                                        <td class="py-2 px-4 border-b text-center">{{ $schedule->frequency }}</td>
                                        @if(Auth::check() && Auth::user()->hasAnyRole(['admin', 'worker']))
                                            <td class="py-2 px-4 border-b text-center">
                                                <a href="{{ route('bus-times.edit', $schedule) }}" class="text-blue-600 hover:text-blue-900 mx-1">
                                                    تعديل
                                                </a>
                                                <form action="{{ route('bus-times.destroy', $schedule) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 mx-1" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                                        حذف
                                                    </button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ Auth::check() && Auth::user()->hasAnyRole(['admin', 'worker']) ? '6' : '5' }}" class="py-4 px-4 border-b text-center">
                                            لا توجد جداول حالياً.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
