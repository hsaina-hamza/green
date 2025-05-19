<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-user-plus mr-2 text-blue-500"></i>
                            إضافة موظف جديد
                        </h2>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition duration-300">
                            <i class="fas fa-arrow-right ml-2"></i>
                            العودة للموظفين
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">خطأ!</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-user mr-1 text-blue-500"></i>
                                    الاسم الكامل
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border text-right"
                                       placeholder="أدخل الاسم الكامل">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-envelope mr-1 text-blue-500"></i>
                                    البريد الإلكتروني
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border text-right"
                                       placeholder="example@domain.com">
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-lock mr-1 text-blue-500"></i>
                                    كلمة المرور
                                </label>
                                <input type="password" name="password" id="password" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border text-right"
                                       placeholder="أدخل كلمة المرور">
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-lock mr-1 text-blue-500"></i>
                                    تأكيد كلمة المرور
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border text-right"
                                       placeholder="أعد إدخال كلمة المرور">
                            </div>

                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-phone mr-1 text-blue-500"></i>
                                    رقم الهاتف
                                </label>
                                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border text-right"
                                       placeholder="9665XXXXXXXX">
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-user-tag mr-1 text-blue-500"></i>
                                    الدور
                                </label>
                                <select name="role" id="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border text-right">
                                    <option value="">اختر الدور</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                            @if($role == 'admin')
                                                مدير
                                            @elseif($role == 'worker')
                                                عامل
                                            @else
                                                مستخدم
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end pt-6">
                            <button type="submit" class="flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300 shadow-md">
                                <i class="fas fa-save ml-2"></i>
                                حفظ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
