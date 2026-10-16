<!DOCTYPE html>
<html lang="ar" dir="rtl" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="لوحة تحكم لإدارة نظام حضور وغياب الموظفين  Pixel Security.">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

    <script src="{{ URL::asset('admin/tailwindcss.js') }}"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
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

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="{{ URL::asset('admin/css.css') }}">

    @yield('css')

</head>

<body class="flex h-screen overflow-hidden bg-gray-50 dark:bg-[#050505] text-gray-800 dark:text-[#e5e7eb]">

    <div id="mobile-overlay"
        onclick="document.getElementById('sidebar').classList.remove('mobile-show'); this.classList.add('hidden');"
        class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm hidden lg:hidden transition-all duration-300"></div>

    @include('layouts_new.sidebar')

    <main id="main-content" class="flex-1 flex flex-col h-full overflow-y-auto mr-0 lg:mr-64 transition-all">

        @include('layouts_new.head')

        <div class="flex-1 p-6 lg:p-5">

            @yield('page-header')
            @yield('content')


        </div>
        @include('layouts_new.footer')

    </main>


    @if (Auth::user()->roles_name ==["owner"])
    <!-- AI Chat Widget -->
    <div id="ai-chat-widget" class="fixed bottom-6 left-6 z-50 flex flex-col items-end no-print" dir="rtl">
        <!-- Chat Window -->
        <div id="ai-chat-window" class="hidden w-[350px] sm:w-[400px] bg-white dark:bg-[#1a1a1a] rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-800 mb-4 overflow-hidden flex-col h-[500px] max-h-[80vh]">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4 flex justify-between items-center text-white shrink-0">
                <div class="flex items-center gap-2">
                    <i class="fa fa-robot text-xl"></i>
                    <h3 class="font-bold text-sm">المساعد الذكي (Pixel AI)</h3>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="clearAiChat()" class="hover:text-gray-200 transition-colors text-xs bg-white/10 px-2 py-1 rounded-lg hover:bg-white/20" title="مسح المحادثة">
                        <i class="fa fa-trash ml-1"></i>
                    </button>
                    <button onclick="toggleAiChat()" class="hover:text-gray-200 transition-colors">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <!-- Messages Area -->
            <div id="ai-chat-messages" class="flex-1 p-4 overflow-y-auto flex flex-col gap-3 bg-gray-50/50 dark:bg-[#111] text-sm scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-700">
                <div class="self-start bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-3 rounded-2xl rounded-tr-none shadow-sm max-w-[85%] text-gray-800 dark:text-gray-200 leading-relaxed">
                    مرحباً بك! 🤖<br>أنا المساعد الذكي. يمكنك سؤالي عن غياب الموظفين أو رواتبهم، مثلاً:<br><span class="text-blue-500 font-bold"></span>
                </div>
            </div>
            <!-- Input Area -->
            <div class="p-3 bg-white dark:bg-[#1a1a1a] border-t border-gray-100 dark:border-gray-800 flex gap-2 shrink-0">
                <input type="text" id="ai-chat-input" placeholder="اسألني هنا..." class="flex-1 bg-gray-100 dark:bg-gray-800 border-none rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none text-gray-800 dark:text-gray-200 text-sm" onkeypress="if(event.key === 'Enter') sendAiChatMessage()">
                <button onclick="sendAiChatMessage()" class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl w-10 h-10 flex items-center justify-center transition-colors">
                    <i class="fa fa-paper-plane"></i>
                </button>
            </div>
        </div>

        <!-- Floating Button -->
        <button onclick="toggleAiChat()" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group">
            <i class="fa fa-comment-dots text-2xl"></i>
            <!-- Tooltip -->
            <span class="absolute left-16 top-1/2 -translate-y-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">اسأل المساعد الذكي</span>
        </button>
    </div>
    @endif

    <script>
        function toggleAiChat() {
            const chatWindow = document.getElementById('ai-chat-window');
            chatWindow.classList.toggle('hidden');
            chatWindow.classList.toggle('flex');
            if(!chatWindow.classList.contains('hidden')) {
                document.getElementById('ai-chat-input').focus();
            }
        }

        function clearAiChat() {
            if (!confirm('هل تريد مسح المحادثة؟')) return;
            const messagesContainer = document.getElementById('ai-chat-messages');
            messagesContainer.innerHTML = `
                <div class="self-start bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-3 rounded-2xl rounded-tr-none shadow-sm max-w-[85%] text-gray-800 dark:text-gray-200 leading-relaxed">
                    مرحباً بك! 🤖<br>أنا المساعد الذكي. يمكنك سؤالي عن غياب الموظفين أو رواتبهم، مثلاً:<br><span class="text-blue-500 font-bold"></span>
                </div>
            `;
        }

        async function sendAiChatMessage() {
            const input = document.getElementById('ai-chat-input');
            const message = input.value.trim();
            if (!message) return;

            const messagesContainer = document.getElementById('ai-chat-messages');

            // Add User Message
            messagesContainer.innerHTML += `
                <div class="self-end bg-blue-600 text-white p-3 rounded-2xl rounded-tl-none shadow-sm max-w-[85%] leading-relaxed">
                    ${message}
                </div>
            `;
            input.value = '';
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            // Add Loading indicator
            const loadingId = 'loading-' + Date.now();
            messagesContainer.innerHTML += `
                <div id="${loadingId}" class="self-start bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-3 rounded-2xl rounded-tr-none shadow-sm flex gap-1 items-center h-10">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                </div>
            `;
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            try {
                const response = await fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message: message })
                });

                const data = await response.json();
                document.getElementById(loadingId)?.remove();

                if(response.ok && data.success) {
                    // Convert markdown bold to html bold for basic formatting
                    let formattedResponse = data.response.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                    formattedResponse = formattedResponse.replace(/\n/g, '<br>');

                    messagesContainer.innerHTML += `
                        <div class="self-start bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-3 rounded-2xl rounded-tr-none shadow-sm max-w-[90%] text-gray-800 dark:text-gray-200 leading-relaxed">
                            ${formattedResponse}
                        </div>
                    `;
                } else {
                    throw new Error(data.error || 'حدث خطأ غير معروف في الخادم');
                }
            } catch (error) {
                document.getElementById(loadingId)?.remove();
                let errorMsg = error.message;
                if (errorMsg.includes('quota') || errorMsg.includes('429')) {
                    errorMsg = 'تجاوزت الحد المسموح للأسئلة في الدقيقة بالنسخة المجانية. يرجى الانتظار 15 ثانية والمحاولة مجدداً. ⏳';
                }
                messagesContainer.innerHTML += `
                    <div class="self-start bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400 border border-red-100 dark:border-red-900/50 p-3 rounded-2xl rounded-tr-none shadow-sm max-w-[85%] text-xs leading-relaxed">
                        <i class="fa fa-exclamation-triangle ml-1"></i> عذراً، لم أتمكن من إتمام الطلب.
                        <br><span class="opacity-75">${errorMsg}</span>
                    </div>
                `;
            }
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

    </script>

    <script>
        // تتبع الموقع بصمت في الخلفية للموظفين كل 60 ثانية
        @if(Auth::check() && Auth::user()->roles_name == ["emp"])
            function sendSilentLocationUpdate() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const lat = position.coords.latitude;
                        const lon = position.coords.longitude;

                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        // إرسال الإحداثيات مباشرة للسيرفر دون تدخل الموظف
                        fetch('{{ route("location_request.respond") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                request_id: 'silent', // نحدد له معرف خاص 'silent'
                                lat: lat,
                                lon: lon,
                                numid: '{{ Auth::user()->num }}'
                            })
                        }).catch(err => console.log('Silent tracking error:', err));
                    }, function(err) {}, { enableHighAccuracy: true });
                }
            }

            // تشغيل التتبع كل دقيقة
            setInterval(sendSilentLocationUpdate, 60000);
            sendSilentLocationUpdate(); // تشغيل فوري عند فتح الموقع
        @endif
    </script>

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('admin/js.js') }}?v=1.0.3"></script>
    @yield('js')


</body>
</html>
