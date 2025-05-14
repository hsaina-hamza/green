<x-role-layout :title="'نصائح بيئية'" :rtl="true">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-8">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-green-700 mb-4">🌿 نصائح للحفاظ على البيئة</h1>
            <p class="text-lg text-gray-600">خطوات بسيطة يمكنك اتخاذها يومياً لحماية كوكبنا</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Water Conservation -->
            <section class="bg-green-50 p-6 rounded-xl hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-3 rounded-full mr-4">
                        <span class="text-2xl">💧</span>
                    </div>
                    <h3 class="text-xl font-semibold text-green-700">توفير المياه</h3>
                </div>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <span class="text-green-500 ml-2">•</span>
                        <span>أغلق الصنبور أثناء تنظيف الأسنان</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-500 ml-2">•</span>
                        <span>أصلح التسريبات فوراً - قطرة واحدة في الثانية تهدر أكثر من 3,000 جالون سنوياً!</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-500 ml-2">•</span>
                        <span>استخدم المكنسة بدلاً من خرطوم المياه لتنظيف الممرات والأرصفة</span>
                    </li>
                </ul>
            </section>

            <!-- Energy Saving -->
            <section class="bg-blue-50 p-6 rounded-xl hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 p-3 rounded-full mr-4">
                        <span class="text-2xl">🔌</span>
                    </div>
                    <h3 class="text-xl font-semibold text-blue-700">ترشيد استهلاك الطاقة</h3>
                </div>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <span class="text-blue-500 ml-2">•</span>
                        <span>استبدل المصابيح التقليدية بمصابيح LED</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-500 ml-2">•</span>
                        <span>افصل الشواحن والأجهزة عند عدم استخدامها</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-500 ml-2">•</span>
                        <span>استخدم الضوء الطبيعي خلال النهار كلما أمكن</span>
                    </li>
                </ul>
            </section>

            <!-- Reduce, Reuse, Recycle -->
            <section class="bg-purple-50 p-6 rounded-xl hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 p-3 rounded-full mr-4">
                        <span class="text-2xl">♻️</span>
                    </div>
                    <h3 class="text-xl font-semibold text-purple-700">تقليل، إعادة استخدام، إعادة تدوير</h3>
                </div>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <span class="text-purple-500 ml-2">•</span>
                        <span>تجنب البلاستيك أحادي الاستخدام؛ استخدم البدائل القابلة لإعادة الاستخدام</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-purple-500 ml-2">•</span>
                        <span>تبرع بالملابس والإلكترونيات القديمة أو أعد استخدامها</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-purple-500 ml-2">•</span>
                        <span>افرز المواد القابلة لإعادة التدوير بشكل صحيح</span>
                    </li>
                </ul>
            </section>

            <!-- Nature Protection -->
            <section class="bg-emerald-50 p-6 rounded-xl hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center mb-4">
                    <div class="bg-emerald-100 p-3 rounded-full mr-4">
                        <span class="text-2xl">🌳</span>
                    </div>
                    <h3 class="text-xl font-semibold text-emerald-700">حماية الطبيعة</h3>
                </div>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <span class="text-emerald-500 ml-2">•</span>
                        <span>ازرع الأشجار أو النباتات المحلية في فناء منزلك</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-emerald-500 ml-2">•</span>
                        <span>ادعم جهود الحفاظ على البيئة المحلية</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-emerald-500 ml-2">•</span>
                        <span>ابق على المسارات المخصصة عند التنزه</span>
                    </li>
                </ul>
            </section>

            <!-- Green Transportation -->
            <section class="bg-amber-50 p-6 rounded-xl hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center mb-4">
                    <div class="bg-amber-100 p-3 rounded-full mr-4">
                        <span class="text-2xl">🚲</span>
                    </div>
                    <h3 class="text-xl font-semibold text-amber-700">النقل الأخضر</h3>
                </div>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <span class="text-amber-500 ml-2">•</span>
                        <span>امشِ، استخدم الدراجة، أو شارك السيارة</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-amber-500 ml-2">•</span>
                        <span>استخدم وسائل النقل العام للحد من الانبعاثات</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-amber-500 ml-2">•</span>
                        <span>حافظ على صيانة سيارتك لكفاءة أفضل</span>
                    </li>
                </ul>
            </section>

            <!-- Eco-friendly Shopping -->
            <section class="bg-teal-50 p-6 rounded-xl hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center mb-4">
                    <div class="bg-teal-100 p-3 rounded-full mr-4">
                        <span class="text-2xl">🛒</span>
                    </div>
                    <h3 class="text-xl font-semibold text-teal-700">التسوق الصديق للبيئة</h3>
                </div>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <span class="text-teal-500 ml-2">•</span>
                        <span>اشترِ بكميات كبيرة لتقليل نفايات التغليف</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-teal-500 ml-2">•</span>
                        <span>ادعم العلامات التجارية الصديقة للبيئة</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-teal-500 ml-2">•</span>
                        <span>اختر المنتجات ذات التغليف القابل للتحلل</span>
                    </li>
                </ul>
            </section>
        </div>

        <div class="mt-12 p-6 bg-gradient-to-r from-green-500 to-teal-500 rounded-xl text-center shadow-lg">
            <p class="text-white text-xl font-medium">
                "أكبر تهديد لكوكبنا هو الاعتقاد بأن شخصاً آخر سينقذه"
                <span class="block text-green-100 text-lg mt-2">- روبرت سوان</span>
            </p>
        </div>

        {{-- <div class="mt-8 text-center">
            <a href="#" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd" />
                </svg>
                تحميل دليل الحفاظ على البيئة
            </a>
        </div> --}}
    </div>
</x-role-layout>