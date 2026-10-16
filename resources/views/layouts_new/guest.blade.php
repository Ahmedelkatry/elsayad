<!DOCTYPE html>
<html lang="ar" dir="rtl" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="تسجيل الدخول - Pixel Security">
    <title>@yield('title') - Pixel Security</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Scripts -->
    <script src="{{ URL::asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('admin/tailwindcss.js') }}"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            bg: '#050505',
                            card: '#080808',
                            border: 'rgba(255, 255, 255, 0.05)'
                        },
                        yellow: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                            950: '#082f49'
                        }
                    }
                }
            }
        }
    </script>
    <script src="{{ URL::asset('admin/icons.js') }}"></script>

    <link rel="stylesheet" href="{{ URL::asset('admin/css.css') }}">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Inter', 'Cairo', sans-serif;
        }

        .glass-card {
            background: rgba(8, 8, 8, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .animated-bg {
            background: radial-gradient(circle at 50% 50%, #1a1a1a 0%, #050505 100%);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .blob {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(234, 179, 8, 0.05) 0%, rgba(234, 179, 8, 0) 70%);
            filter: blur(80px);
            border-radius: 50%;
            z-index: -1;
            animation: move 20s infinite alternate;
        }

        @keyframes move {
            from { transform: translate(-10%, -10%); }
            to { transform: translate(10%, 10%); }
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6 overflow-hidden bg-dark-bg text-gray-200">
    
    <div class="animated-bg"></div>
    <div class="blob" style="top: -100px; left: -100px;"></div>
    <div class="blob" style="bottom: -100px; right: -100px; animation-delay: -5s; background: radial-gradient(circle, rgba(234, 179, 8, 0.03) 0%, rgba(234, 179, 8, 0) 70%);"></div>

    <div class="w-full max-w-[440px] z-10">
        @yield('content')
    </div>

    <script src="{{ URL::asset('admin/js.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
</body>

</html>
