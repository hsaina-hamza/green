<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenMorocco - من أجل بيئة نظيفة</title>
    <link rel="icon" type="image/ozen.png" href="hsaina.png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --primary-light: #d1fae5;
        }
        
        body {
            font-family: 'Tajawal', sans-serif;
        }
        
        /* Navigation */
        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            transform: translateY(-2px);
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -4px;
            right: 0;
            width: 100%;
            height: 2px;
            background-color: white;
        }
        
        /* Cards */
        .service-card {
            transition: all 0.3s ease;
            border-top: 4px solid var(--primary);
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
        }
        
        /* Alerts */
        .alert {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            width: 350px;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        
        .alert.show {
            opacity: 1;
            transform: translateX(0);
        }
        
        .alert-success {
            background-color: #ecfdf5;
            border-right: 4px solid var(--primary);
            color: #065f46;
        }
        
        .alert-error {
            background-color: #fef2f2;
            border-right: 4px solid #ef4444;
            color: #991b1b;
        }
        
        .alert-warning {
            background-color: #fffbeb;
            border-right: 4px solid #f59e0b;
            color: #92400e;
        }
        
        .alert-info {
            background-color: #eff6ff;
            border-right: 4px solid #3b82f6;
            color: #1e40af;
        }
        
        .alert-close {
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
        }
        
        .alert-close:hover {
            opacity: 1;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .alert {
                width: 90%;
                right: 5%;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Alert Container -->
    <div id="alertContainer"></div>
    
    <!-- Navigation -->
    <header class="navbar text-white sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('images/ozen.png') }}" class="h-10 w-10" alt="GM">
                        <span class="mr-2 text-2xl font-bold hidden md:block">GM</span>
                    </a>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobileMenuButton" class="text-white focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:block">
                    <ul class="flex space-x-1 space-x-reverse">
                        <li>
                            <a href="{{ route('home') }}" class="nav-link px-4 py-2 {{ request()->routeIs('home') ? 'active' : '' }}">
                                <i class="fas fa-home ml-2"></i>الرئيسية
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('conservationTips.tips') }}" class="nav-link px-4 py-2 {{ request()->routeIs('conservationTips.tips') ? 'active' : '' }}">
                                <i class="fas fa-lightbulb ml-2"></i>نصائح بيئية
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('waste-map') }}" class="nav-link px-4 py-2 {{ request()->routeIs('waste-map') ? 'active' : '' }}">
                                <i class="fas fa-map ml-2"></i>خريطة النفايات
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('bus-times.index') }}" class="nav-link px-4 py-2 {{ request()->routeIs('bus-times.index') ? 'active' : '' }}">
                                <i class="fas fa-bus ml-2"></i>مواعيد الحافلات
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('waste-reports.create') }}" class="nav-link px-4 py-2 {{ request()->routeIs('waste-reports.create') ? 'active' : '' }}">
                                <i class="fas fa-trash ml-2"></i>تبليغ عن نفايات
                            </a>
                        </li>
                        @auth
                            <li>
                                <a href="{{ route('dashboard') }}" class="nav-link px-4 py-2 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-chart-line ml-2"></i>لوحة التحكم
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('login') }}" class="nav-link px-4 py-2 {{ request()->routeIs('login') ? 'active' : '' }}">
                                    <i class="fas fa-sign-in-alt ml-2"></i>تسجيل الدخول
                                </a>
                            </li>
                        @endauth
                    </ul>
                </nav>
            </div>
            
            <!-- Mobile Navigation -->
            <div id="mobileMenu" class="hidden md:hidden mt-4 pb-2">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('home') }}" class="block px-3 py-2 rounded hover:bg-green-700">
                            <i class="fas fa-home ml-2"></i>الرئيسية
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('conservationTips.tips') }}" class="block px-3 py-2 rounded hover:bg-green-700">
                            <i class="fas fa-lightbulb ml-2"></i>نصائح بيئية
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('waste-map') }}" class="block px-3 py-2 rounded hover:bg-green-700">
                            <i class="fas fa-map ml-2"></i>خريطة النفايات
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('bus-times.index') }}" class="block px-3 py-2 rounded hover:bg-green-700">
                            <i class="fas fa-bus ml-2"></i>مواعيد الحافلات
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('waste-reports.create') }}" class="block px-3 py-2 rounded hover:bg-green-700">
                            <i class="fas fa-trash ml-2"></i>تبليغ عن نفايات
                        </a>
                    </li>
                    @auth
                        <li>
                            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded hover:bg-green-700">
                                <i class="fas fa-chart-line ml-2"></i>لوحة التحكم
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('login') }}" class="block px-3 py-2 rounded hover:bg-green-700">
                                <i class="fas fa-sign-in-alt ml-2"></i>تسجيل الدخول
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-green-500 to-green-600 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">معاً من أجل بيئة نظيفة</h1>
            <p class="text-xl mb-8 max-w-3xl mx-auto">انضم إلى جهودنا المجتمعية للحفاظ على البيئة، والإبلاغ عن مواقع النفايات، وإحداث تأثير إيجابي على كوكبنا للأجيال القادمة.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @auth
                    <a href="{{ route('waste-reports.create') }}" class="btn-primary px-8 py-3 rounded-lg text-lg font-medium shadow-lg inline-flex items-center justify-center">
                        <i class="fas fa-trash-alt ml-2"></i>الإبلاغ عن موقع نفايات
                    </a>
                @else
                    <button onclick="showLoginAlert()" class="btn-primary px-8 py-3 rounded-lg text-lg font-medium shadow-lg inline-flex items-center justify-center">
                        <i class="fas fa-trash-alt ml-2"></i>الإبلاغ عن موقع نفايات
                    </button>
                @endauth
                <a href="{{ route('conservationTips.tips') }}" class="bg-white text-green-600 px-8 py-3 rounded-lg hover:bg-gray-100 font-medium text-lg shadow-lg transition inline-flex items-center justify-center">
                    <i class="fas fa-lightbulb ml-2"></i>نصائح بيئية
                </a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-12 text-center">خدماتنا</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Waste Reporting -->
                <div class="service-card bg-white p-6 rounded-lg shadow-md hover:shadow-lg">
                    <div class="text-green-600 bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-trash-alt text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-center">الإبلاغ عن نفايات</h3>
                    <p class="text-gray-600 mb-4 text-center">الإبلاغ عن مواقع النفايات غير القانونية مع الصور وبيانات الموقع للمساعدة في تنظيف مجتمعنا.</p>
                    <div class="text-center">
                        @auth
                            <a href="{{ route('waste-reports.create') }}" class="inline-block bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 font-medium">
                                <i class="fas fa-plus ml-2"></i>إبلاغ الآن
                            </a>
                        @else
                            <button onclick="showLoginAlert()" class="inline-block bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 font-medium">
                                <i class="fas fa-plus ml-2"></i>إبلاغ الآن
                            </button>
                        @endauth
                    </div>
                </div>
                
                <!-- Waste Map -->
                <div class="service-card bg-white p-6 rounded-lg shadow-md hover:shadow-lg">
                    <div class="text-green-600 bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-map-marked-alt text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-center">خريطة النفايات</h3>
                    <p class="text-gray-600 mb-4 text-center">عرض مواقع النفايات المبلغ عنها على خريطة تفاعلية وتتبع تقدم التنظيف.</p>
                    <div class="text-center">
                        <a href="{{ route('waste-map') }}" class="inline-block bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 font-medium">
                            <i class="fas fa-map ml-2"></i>عرض الخريطة
                        </a>
                    </div>
                </div>
                
                <!-- Bus Times -->
                <div class="service-card bg-white p-6 rounded-lg shadow-md hover:shadow-lg">
                    <div class="text-green-600 bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-bus text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-center">مواعيد الحافلات</h3>
                    <p class="text-gray-600 mb-4 text-center">تحقق من جداول الحافلات المحلية لتقليل بصمتك الكربونية باستخدام وسائل النقل العام.</p>
                    <div class="text-center">
                        <a href="{{ route('bus-times.index') }}" class="inline-block bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 font-medium">
                            <i class="fas fa-clock ml-2"></i>عرض المواعيد
                        </a>
                    </div>
                </div>
                
                <!-- Conservation Tips -->
                <div class="service-card bg-white p-6 rounded-lg shadow-md hover:shadow-lg">
                    <div class="text-green-600 bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-leaf text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-center">نصائح بيئية</h3>
                    <p class="text-gray-600 mb-4 text-center">تعلم كيفية تقليل النفايات وحماية بيئتنا مع نصائح عملية للحياة اليومية.</p>
                    <div class="text-center">
                        <a href="{{ route('conservationTips.tips') }}" class="inline-block bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 font-medium">
                            <i class="fas fa-book-open ml-2"></i>معرفة المزيد
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Join Us Section -->
    <section class="py-16 bg-gradient-to-r from-green-500 to-green-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-6">انضم إلى مهمتنا البيئية</h2>
            <p class="text-xl mb-8 max-w-3xl mx-auto">قم بإنشاء حساب للإبلاغ عن مواقع النفايات، وتتبع تقاريرك، وكن جزءًا من مجتمعنا المكرس للحفاظ على البيئة.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="bg-white text-green-600 px-8 py-3 rounded-lg hover:bg-gray-100 font-medium text-lg shadow-lg transition">
                        إنشاء حساب
                    </a>
                @endif
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="bg-transparent hover:bg-white text-white hover:text-green-700 px-8 py-3 rounded-lg border-2 border-white font-medium text-lg shadow-lg transition">
                        تسجيل الدخول
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Recent Reports Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-12 text-center">أحدث التقارير</h2>
            
            @if(count($recentReports ?? []) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($recentReports as $report)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:scale-105">
                        <div class="h-48 bg-gray-200 overflow-hidden">
                            @if($report->image_url)
                                <img src="{{ Storage::url($report->image_url) }}" alt="صورة التقرير" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <i class="fas fa-image text-4xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-2">{{ $report->title ?? 'تقرير نفايات' }}</h3>
                            <p class="text-gray-600 mb-2">
                                <i class="fas fa-map-marker-alt ml-2"></i>{{ $report->location ?? '' }}
                            </p>
                            @php
                                $statusClass = '';
                                if($report->status == 'new') $statusClass = 'bg-red-100 text-red-800';
                                elseif($report->status == 'in_progress') $statusClass = 'bg-yellow-100 text-yellow-800';
                                elseif($report->status == 'cleaned') $statusClass = 'bg-green-100 text-green-800';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }} mb-4 inline-block">
                                {{ $report->status ?? '' }}
                            </span>
                            <div class="text-center">
                                <a href="{{ route('waste-reports.show', ['id' => $report->id]) }}" class="inline-block bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 font-medium">
                                    <i class="fas fa-eye ml-2"></i>عرض التقرير
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center bg-white p-8 rounded-lg shadow-md max-w-2xl mx-auto">
                    <i class="fas fa-clipboard text-5xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">لا توجد تقارير متاحة</h3>
                    <p class="text-gray-600 mb-4">كن أول من يبلغ عن موقع نفايات وساعد في تنظيف مجتمعك!</p>
                    @auth
                        <a href="{{ route('waste-reports.create') }}" class="inline-block bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 font-medium">
                            <i class="fas fa-plus ml-2"></i>إبلاغ عن نفايات
                        </a>
                    @else
                        <button onclick="showLoginAlert()" class="inline-block bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 font-medium">
                            <i class="fas fa-plus ml-2"></i>إبلاغ عن نفايات
                        </button>
                    @endauth
                </div>
            @endif
            
            @if(count($recentReports ?? []) > 0)
                <div class="text-center mt-12">
                    <a href="{{ route('waste-map') }}" class="inline-block bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 font-medium text-lg shadow-lg transition">
                        <i class="fas fa-map-marked-alt ml-2"></i>عرض جميع التقارير
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-green-800 text-white pt-12 pb-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- About -->
                <div>
                    <h4 class="text-xl font-semibold mb-4 flex items-center">
                        <img src="{{ asset('images/ozen.png') }}" class="h-8 w-8 ml-2" alt="شعار GreenMorocco">
                        GreenMorocco
                    </h4>
                    <p class="text-gray-300">مكرّسة للحفاظ على البيئة وإدارة النفايات بشكلٍ صحيح، من أجل مستقبل أنظف وأكثر اخضرارًا.</p>
                    {{-- <div class="flex mt-4 space-x-4 space-x-reverse">
                        <a href="#" class="text-gray-300 hover:text-white transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div> --}}
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h5 class="text-lg font-semibold mb-4 border-b pb-2 border-green-700">روابط سريعة</h5>
                    <ul class="space-y-3">
                        <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition flex items-center"><i class="fas fa-chevron-left ml-2 text-xs"></i>الرئيسية</a></li>
                        <li><a href="{{ route('conservationTips.tips') }}" class="text-gray-300 hover:text-white transition flex items-center"><i class="fas fa-chevron-left ml-2 text-xs"></i>نصائح بيئية</a></li>
                        <li><a href="{{ route('waste-map') }}" class="text-gray-300 hover:text-white transition flex items-center"><i class="fas fa-chevron-left ml-2 text-xs"></i>خريطة النفايات</a></li>
                        <li><a href="{{ route('bus-times.index') }}" class="text-gray-300 hover:text-white transition flex items-center"><i class="fas fa-chevron-left ml-2 text-xs"></i>مواعيد الحافلات</a></li>
                    </ul>
                </div>
                
                <!-- Services -->
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
                
                <!-- Contact -->
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
            
            <div class="border-t border-green-700 pt-6 text-center text-gray-300">
                <p>&copy; {{ date('Y') }} GreenMorocco. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuButton').addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });
        
        // Alert system
        function showAlert(type, message) {
            const alertContainer = document.getElementById('alertContainer');
            const alertId = 'alert-' + Date.now();
            let icon, colorClass;
            
            switch(type) {
                case 'success':
                    icon = 'fa-check-circle';
                    colorClass = 'alert-success';
                    break;
                case 'error':
                    icon = 'fa-exclamation-circle';
                    colorClass = 'alert-error';
                    break;
                case 'warning':
                    icon = 'fa-exclamation-triangle';
                    colorClass = 'alert-warning';
                    break;
                case 'info':
                    icon = 'fa-info-circle';
                    colorClass = 'alert-info';
                    break;
                default:
                    icon = 'fa-info-circle';
                    colorClass = 'alert-info';
            }
            
            const alertElement = document.createElement('div');
            alertElement.id = alertId;
            alertElement.className = `alert ${colorClass}`;
            alertElement.innerHTML = `
                <div class="flex-shrink-0">
                    <i class="fas ${icon} text-xl"></i>
                </div>
                <div class="mr-3 flex-1">
                    <div class="font-medium">${message}</div>
                </div>
                <div class="alert-close" onclick="closeAlert('${alertId}')">
                    <i class="fas fa-times"></i>
                </div>
            `;
            
            alertContainer.appendChild(alertElement);
            
            // Trigger animation
            setTimeout(() => {
                alertElement.classList.add('show');
            }, 10);
            
            // Auto-close after 5 seconds
            setTimeout(() => {
                closeAlert(alertId);
            }, 5000);
        }
        
        function closeAlert(id) {
            const alert = document.getElementById(id);
            if (alert) {
                alert.classList.remove('show');
                setTimeout(() => {
                    alert.remove();
                }, 500);
            }
        }
        
        function showLoginAlert() {
            showAlert('info', 'يرجى تسجيل الدخول للإبلاغ عن موقع نفايات.');
        }
        
        // Show welcome alert
        setTimeout(() => {
            showAlert('success', 'مرحباً بك في GreenMorocco! معاً من أجل بيئة أنظف.');
        }, 1000);
    </script>
</body>
</html>