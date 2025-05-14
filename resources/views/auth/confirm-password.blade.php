<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50">
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <!-- Logo/Header -->
            <div class="flex justify-center mb-8">
                <x-application-logo class="w-20 h-20 text-gray-500" />
            </div>

            <!-- Information Message -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-start">
                <i class="fas fa-shield-alt text-blue-500 mt-1 mr-3"></i>
                <p class="text-sm text-gray-700">
                    {{ __('هذه منطقة آمنة في التطبيق. يرجى تأكيد كلمة المرور الخاصة بك قبل المتابعة.') }}
                </p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
                @csrf

                <!-- Password Field -->
                <div>
                    <x-input-label for="password" :value="__('كلمة المرور')" class="flex items-center">
                        <i class="fas fa-lock mr-2 text-gray-500"></i>
                    </x-input-label>

                    <x-text-input 
                        id="password" 
                        class="block mt-1 w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        type="password"
                        name="password"
                        required 
                        autocomplete="current-password"
                        placeholder="••••••••" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                    </x-input-error>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end mt-6">
                    <x-primary-button class="flex items-center bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded-lg transition duration-300 shadow-md">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ __('تأكيد') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .text-input {
            transition: all 0.3s ease;
        }
        .text-input:focus {
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
            border-color: #10b981;
        }
        .primary-button:hover {
            transform: translateY(-1px);
        }
    </style>
</x-guest-layout>