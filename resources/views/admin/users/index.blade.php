<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-users-cog mr-2 text-blue-500"></i>
                            إدارة المستخدمين
                        </h2>
                        <a href="{{ route('admin.users.create') }}" class="flex items-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                            <i class="fas fa-user-plus mr-2"></i>
                            مستخدم جديد
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto shadow-md rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">البريد الإلكتروني</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الدور</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الهاتف</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 ml-3">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    {{ $user->name }}
                                                    @if($user->id === auth()->id())
                                                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">(أنت)</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dir-ltr">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @php
                                                $role = $user->roles->first()?->name ?? 'No role';
                                                $roleColors = [
                                                    'admin' => 'bg-purple-100 text-purple-800',
                                                    'user' => 'bg-green-100 text-green-800',
                                                    'No role' => 'bg-gray-100 text-gray-800'
                                                ];
                                            @endphp
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $roleColors[$role] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $role === 'admin' ? 'مدير' : ($role === 'user' ? 'مستخدم' : 'بدون دور') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dir-ltr">{{ $user->phone_number ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex justify-end space-x-2 space-x-reverse">
                                                <a href="{{ route('admin.users.edit', $user) }}" 
                                                   class="flex items-center bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded text-sm transition duration-300">
                                                    <i class="fas fa-edit mr-1"></i>
                                                    تعديل
                                                </a>
                                                @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="flex items-center bg-red-600 hover:bg-red-700 text-white py-1 px-3 rounded text-sm transition duration-300"
                                                            onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذا المستخدم؟')">
                                                        <i class="fas fa-trash-alt mr-1"></i>
                                                        حذف
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-center">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dir-ltr {
            direction: ltr;
        }
        .table-auto {
            width: 100%;
        }
        .table-auto th, .table-auto td {
            padding: 12px 15px;
            text-align: right;
        }
        .table-auto thead th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e5e7eb;
        }
        .table-auto tbody tr:not(:last-child) {
            border-bottom: 1px solid #e5e7eb;
        }
        .table-auto tbody tr:hover {
            background-color: #f9fafb;
        }
    </style>
</x-app-layout>