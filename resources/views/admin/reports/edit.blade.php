<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-edit mr-2 text-blue-500"></i>
                            تعديل التقرير #{{ $report->getKey() }}
                        </h2>
                        <a href="{{ route('admin.reports.index') }}" class="flex items-center bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition duration-300">
                            <i class="fas fa-arrow-left mr-2"></i>
                            العودة إلى التقارير
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                خطأ!
                            </strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Report Details Card -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow-sm border border-gray-100">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                تفاصيل التقرير
                            </h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div class="bg-white p-3 rounded shadow-xs">
                                    <dt class="text-sm font-medium text-gray-500">المبلغ</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900">{{ $report->reporter->name }}</dd>
                                </div>
                                <div class="bg-white p-3 rounded shadow-xs">
                                    <dt class="text-sm font-medium text-gray-500">الموقع</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900">{{ $report->location->name }}</dd>
                                </div>
                                <div class="bg-white p-3 rounded shadow-xs">
                                    <dt class="text-sm font-medium text-gray-500">نوع النفايات</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900">{{ $report->wasteType->name }}</dd>
                                </div>
                                <div class="bg-white p-3 rounded shadow-xs">
                                    <dt class="text-sm font-medium text-gray-500">الكمية</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900">{{ $report->quantity }} {{ $report->unit }}</dd>
                                </div>
                                <div class="bg-white p-3 rounded shadow-xs">
                                    <dt class="text-sm font-medium text-gray-500">تاريخ الإنشاء</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900">{{ $report->created_at->format('Y-m-d H:i') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Update Status Card -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow-sm border border-gray-100">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                                <i class="fas fa-sync-alt mr-2 text-blue-500"></i>
                                تحديث الحالة
                            </h3>
                            <form action="{{ route('admin.reports.update', $report) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                        الحالة
                                    </label>
                                    <select name="status" id="status" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                                        <option value="pending" {{ $report->status === 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                        <option value="in_progress" {{ $report->status === 'in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
                                        <option value="resolved" {{ $report->status === 'resolved' ? 'selected' : '' }}>تم الحل</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                        ملاحظات
                                    </label>
                                    <textarea name="notes" id="notes" rows="4"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
                                    >{{ old('notes', $report->notes) }}</textarea>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" class="flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300 shadow-md">
                                        <i class="fas fa-save mr-2"></i>
                                        حفظ التغييرات
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if($report->comments->count() > 0)
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                                <i class="fas fa-history mr-2 text-blue-500"></i>
                                سجل التقرير
                            </h3>
                            <div class="space-y-4">
                                @foreach($report->comments->sortByDesc('created_at') as $comment)
                                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="flex items-center">
                                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <p class="text-sm font-medium text-gray-700">{{ $comment->user->name }}</p>
                                                </div>
                                                <p class="mt-2 text-gray-600">{{ $comment->content }}</p>
                                            </div>
                                            <span class="text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded">
                                                <i class="far fa-clock mr-1"></i>
                                                {{ $comment->created_at->format('Y-m-d H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        textarea {
            min-height: 100px;
        }
        .shadow-xs {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        select, textarea {
            transition: all 0.3s ease;
        }
        select:focus, textarea:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
    </style>
</x-app-layout>