<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenMorocco</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="bg-green-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">GM</h1>
            </div>
            <nav>
                <ul class="flex space-x-4 space-x-reverse">
                    <li><a href="{{ route('home') }}" class="hover:underline px-3 py-2 rounded hover:bg-green-600 transition"><i class="fas fa-home ml-2"></i>الرئيسية</a></li>
                    <li><a href="{{ route('conservationTips.tips') }}" class="hover:underline px-3 py-2 rounded hover:bg-green-600 transition"><i class="fas fa-lightbulb ml-2"></i>نصائح بيئية</a></li>
                    <li><a href="{{ route('waste-map') }}" class="hover:underline px-3 py-2 rounded hover:bg-green-600 transition"><i class="fas fa-map ml-2"></i>خريطة النفايات</a></li>
                    <li><a href="{{ route('bus-times.index') }}" class="hover:underline px-3 py-2 rounded hover:bg-green-600 transition"><i class="fas fa-bus ml-2"></i>مواعيد الحافلات</a></li>
                    <li><a href="{{ route('waste-reports.create') }}" class="hover:underline px-3 py-2 rounded hover:bg-green-600 transition"><i class="fas fa-trash ml-2"></i>تبليغ عن نفايات</a></li>
                    @if (Route::has('login'))
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="hover:underline px-3 py-2 rounded hover:bg-green-600 transition"><i class="fas fa-chart-line ml-2"></i>لوحة التحكم</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:underline px-3 py-2 rounded hover:bg-green-600 transition"><i class="fas fa-sign-in-alt ml-2"></i>تسجيل الدخول</a></li>
                        @endauth
                    @endif
                </ul>
            </nav>
        </div>
    </header>

    <section class="bg-green-100 py-16">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-bold mb-4">معاً من أجل بيئة نظيفة</h2>
            <p class="mb-8">انضم إلى جهودنا المجتمعية للحفاظ على البيئة، والإبلاغ عن مواقع النفايات، وإحداث تأثير إيجابي على كوكبنا للأجيال القادمة.</p>
            <div>
                @auth
                    <a href="{{ route('waste-reports.create') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 mr-4 font-medium text-lg shadow-md transition flex items-center inline-flex">
                        <i class="fas fa-trash-alt ml-2"></i>الإبلاغ عن موقع نفايات
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 mr-4 font-medium text-lg shadow-md transition flex items-center inline-flex" 
                       onclick="alert('يرجى تسجيل الدخول للإبلاغ عن موقع نفايات.')">
                        <i class="fas fa-trash-alt ml-2"></i>الإبلاغ عن موقع نفايات
                    </a>
                @endauth
                <a href="{{ route('conservationTips.tips') }}" class="bg-transparent hover:bg-white text-green-700 hover:text-green-700 px-6 py-3 rounded-lg border-2 border-green-600 font-medium text-lg shadow-md transition flex items-center inline-flex">
                    <i class="fas fa-lightbulb ml-2"></i>نصائح بيئية
                </a>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="container mx-auto">
            <h2 class="text-2xl font-bold mb-8 text-center">خدماتنا</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <i class="fas fa-trash-alt text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">الإبلاغ عن نفايات</h3>
                    <p class="text-gray-600 mb-4">الإبلاغ عن مواقع النفايات غير القانونية مع الصور وبيانات الموقع للمساعدة في تنظيف مجتمعنا.</p>
                    @auth
                        <a href="{{ route('waste-reports.create') }}" class="mt-2 inline-block bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 font-medium">
                            <i class="fas fa-plus ml-2"></i>إبلاغ الآن
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="mt-2 inline-block bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 font-medium"
                           onclick="alert('يرجى تسجيل الدخول للإبلاغ عن موقع نفايات.')">
                            <i class="fas fa-plus ml-2"></i>إبلاغ الآن
                        </a>
                    @endauth
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <i class="fas fa-map-marked-alt text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">خريطة النفايات</h3>
                    <p class="text-gray-600 mb-4">عرض مواقع النفايات المبلغ عنها على خريطة تفاعلية وتتبع تقدم التنظيف.</p>
                    <a href="{{ route('waste-map') }}" class="mt-2 inline-block bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 font-medium">
                        <i class="fas fa-map ml-2"></i>عرض الخريطة
                    </a>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <i class="fas fa-bus text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">مواعيد الحافلات</h3>
                    <p class="text-gray-600 mb-4">تحقق من جداول الحافلات المحلية لتقليل بصمتك الكربونية باستخدام وسائل النقل العام.</p>
                    <a href="{{ route('bus-times.index') }}" class="mt-2 inline-block bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 font-medium">
                        <i class="fas fa-clock ml-2"></i>عرض المواعيد
                    </a>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <i class="fas fa-leaf text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">نصائح بيئية</h3>
                    <p class="text-gray-600 mb-4">تعلم كيفية تقليل النفايات وحماية بيئتنا مع نصائح عملية للحياة اليومية.</p>
                    <a href="{{ route('conservationTips.tips') }}" class="mt-2 inline-block bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 font-medium">
                        <i class="fas fa-book-open ml-2"></i>معرفة المزيد
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-green-600 text-white py-16">
        <div class="container mx-auto text-center">
            <h2 class="text-2xl font-bold mb-4">انضم إلى مهمتنا البيئية</h2>
            <p class="mb-8">قم بإنشاء حساب للإبلاغ عن مواقع النفايات، وتتبع تقاريرك، وكن جزءًا من مجتمعنا المكرس للحفاظ على البيئة.</p>
            <div>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="bg-white text-green-600 px-6 py-3 rounded-lg hover:bg-gray-100 mr-4 font-medium text-lg shadow-md transition">إنشاء حساب</a>
                @endif
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="bg-transparent hover:bg-white text-white hover:text-green-700 px-6 py-3 rounded-lg border-2 border-white font-medium text-lg shadow-md transition">تسجيل الدخول</a>
                @endif
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="container mx-auto">
            <h2 class="text-2xl font-bold mb-8 text-center">أحدث التقارير</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($recentReports ?? [] as $report)
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="bg-gray-200 h-48 mb-4 rounded-lg flex items-center justify-center">
                        @if($report->image_url)
                            <img src="{{ Storage::url($report->image_url) }}" alt="صورة التقرير" class="h-full w-full object-cover rounded-lg">
                        @else
                            <i class="fas fa-image text-4xl text-gray-400"></i>
                        @endif
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ $report->title ?? 'تقرير نفايات' }}</h3>
                    <p class="text-gray-600 mb-2">{{ $report->location ?? '' }}</p>
                    <p class="text-green-600 font-semibold mb-4">{{ $report->status ?? '' }}</p>
                    <a href="{{ route('waste-reports.show', ['id' => $report->id]) }}" class="inline-block bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 font-medium">
                        <i class="fas fa-eye ml-2"></i>عرض التقرير
                    </a>
                </div>
                @empty
                <div class="col-span-3 text-center text-gray-500">
                    <i class="fas fa-clipboard text-4xl mb-2"></i>
                    <p>لا توجد تقارير متاحة حتى الآن. كن أول من يبلغ عن موقع نفايات!</p>
                </div>
                @endforelse
            </div>
            <div class="text-center mt-8">
                <a href="{{ route('waste-map') }}" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-medium text-lg shadow-md transition">
                    <i class="fas fa-map-marked-alt ml-2"></i>عرض جميع التقارير
                </a>
            </div>
        </div>
    </section>

    <footer class="bg-green-800 text-white py-12">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h4 class="text-xl font-semibold mb-4">GreenMorocco</h4>
                    <p class="text-gray-300">مكرّسة للحفاظ على البيئة وإدارة النفايات بشكلٍ صحيح، من أجل مستقبل أنظف وأكثر اخضرارًا.</p>
                </div>
                <div>
                    <h5 class="text-lg font-semibold mb-4 border-b pb-2 border-green-700">روابط سريعة</h5>
                    <ul class="space-y-3">
                        <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition flex items-center"><i class="fas fa-chevron-left ml-2 text-xs"></i>الرئيسية</a></li>
                        <li><a href="{{ route('conservationTips.tips') }}" class="text-gray-300 hover:text-white transition flex items-center"><i class="fas fa-chevron-left ml-2 text-xs"></i>نصائح بيئية</a></li>
                        <li><a href="{{ route('waste-map') }}" class="text-gray-300 hover:text-white transition flex items-center"><i class="fas fa-chevron-left ml-2 text-xs"></i>خريطة النفايات</a></li>
                        <li><a href="{{ route('bus-times.index') }}" class="text-gray-300 hover:text-white transition flex items-center"><i class="fas fa-chevron-left ml-2 text-xs"></i>مواعيد الحافلات</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-lg font-semibold mb-4 border-b pb-2 border-green-700">خدماتنا</h5>
                    <ul class="space-y-3">
                        <li><a href="{{ route('waste-reports.create') }}" class="text-gray-300 hover:text-white transition flex items-center"><i class="fas fa-chevron-left ml-2 text-xs"></i>الإبلاغ عن نفايات</a></li>
                        @if (Route::has('register'))
                            <li><a href="{{ route('register') }}" class="text-gray-300 hover:text-white transition flex items-center"><i class="fas fa-chevron-left ml-2 text-xs"></i>إنشاء حساب</a></li>
                        @endif
                        @if (Route::has('login'))
                            <li><a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition flex items-center"><i class="fas fa-chevron-left ml-2 text-xs"></i>تسجيل الدخول</a></li>
                        @endif
                    </ul>
                </div>
                <div>
                    <h5 class="text-lg font-semibold mb-4 border-b pb-2 border-green-700">اتصل بنا</h5>
                    <ul class="space-y-3">
                        <li>
                            <a href="mailto:contact@greenmorocco.org" class="text-gray-300 hover:text-white transition flex items-center">
                                <i class="fas fa-envelope ml-2"></i>hhsaina@greenmorocco.org
                            </a>
                        </li>
                        <li>
                            <a href="tel:+212123456789" class="text-gray-300 hover:text-white transition flex items-center">
                                <i class="fas fa-phone ml-2"></i>(+212) 718-324-286
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-300 hover:text-white transition flex items-center">
                                <i class="fas fa-map-marker-alt ml-2"></i>123 شارع الأخضر، كلميم
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
