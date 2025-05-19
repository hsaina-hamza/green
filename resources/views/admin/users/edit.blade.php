<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-user-edit ml-3 text-blue-500"></i>
                            تعديل الموظف: {{ $user->name }}
                        </h2>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition">
                            <i class="fas fa-arrow-right ml-2"></i>
                            العودة إلى الموظفين
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                            <strong class="font-bold block mb-2">حدثت الأخطاء التالية:</strong>
                            <ul class="list-disc pr-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pr-3">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pr-3">
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور (اتركها فارغة للحفاظ على كلمة المرور الحالية)</label>
                                <div class="relative">
                                    <input type="password" name="password" id="password"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pr-3">
                                    <button type="button" onclick="togglePassword('password')" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة المرور</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pr-3">
                                    <button type="button" onclick="togglePassword('password_confirmation')" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">الدور</label>
                                <select name="role" id="role" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pr-3">
                                    <option value="">اختر دورًا</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
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

                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pr-3">
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition">
                                <i class="fas fa-save ml-2"></i>
                                تحديث الموظف
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

    <style>
        [dir="rtl"] input, [dir="rtl"] select {
            padding-right: 0.75rem;
            padding-left: 2.5rem;
        }
        [dir="rtl"] .fa-eye, [dir="rtl"] .fa-eye-slash {
            left: 0.75rem;
            right: auto;
        }
    </style>
</x-app-layout>