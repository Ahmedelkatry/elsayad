 <header
            class="shrink-0 h-20 flex items-center justify-between px-6 lg:px-10 border-b border-gray-200 dark:border-white/5 bg-white/80 dark:bg-[#080808]/80 backdrop-blur-md sticky top-0 z-30 transition-colors">
            <div class="flex items-center gap-4">
                <button id="toggle-sidebar"
                    class="p-2 rounded-xl bg-gray-100 dark:bg-white/5 text-sky-500 dark:text-sky-400 hover:bg-gray-200 dark:hover:bg-sky-500/10 transition-all active:scale-95 border border-gray-200 dark:border-white/5">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>

                <!-- Quick Navigation & Dropdowns (Desktop Only) -->
                @if (Auth::user()->roles_name !== ['emp'])
                    <div class="hidden lg:flex items-center gap-3 mr-6 text-sm font-bold">
                        <!-- 1. Dropdown: التقارير -->
                        <div class="relative">
                            <button id="reports-btn" type="button" class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:text-sky-600 dark:hover:text-sky-400 transition-all border border-gray-200 dark:border-white/5">
                                <i data-lucide="file-text" class="w-4 h-4"></i>
                                <span>التقارير</span>
                                <i id="reports-chevron" data-lucide="chevron-down" class="w-3.5 h-3.5 opacity-60 transition-transform"></i>
                            </button>
                            <div id="reports-dropdown" class="absolute right-0 mt-2 w-52 bg-white dark:bg-[#121212] border border-gray-200 dark:border-white/10 rounded-2xl shadow-2xl py-2 hidden transition-all z-50 text-right">
                                <a href="{{ url('/counts') }}" class="block px-4 py-2 hover:bg-sky-500/10 hover:text-sky-600 dark:hover:text-sky-400 text-xs font-bold text-gray-700 dark:text-gray-300 transition-colors">تقرير الموظف الفردي</a>
                                <a href="{{ url('/countr') }}" class="block px-4 py-2 hover:bg-sky-500/10 hover:text-sky-600 dark:hover:text-sky-400 text-xs font-bold text-gray-700 dark:text-gray-300 transition-colors">تقرير القسم</a>
                                <a href="{{ url('/ganeral') }}" class="block px-4 py-2 hover:bg-sky-500/10 hover:text-sky-600 dark:hover:text-sky-400 text-xs font-bold text-gray-700 dark:text-gray-300 transition-colors">تقرير إجمالي عام</a>
                                <div class="my-1 border-t border-gray-100 dark:border-white/5"></div>
                                <a href="{{ route('zkts.index') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-sky-500/10 hover:text-sky-600 dark:hover:text-sky-400 text-xs font-bold text-gray-700 dark:text-gray-300 transition-colors">
                                    <i data-lucide="fingerprint" class="w-3.5 h-3.5 opacity-60"></i>
                                    تقرير الموظفين
                                </a>
                            </div>
                        </div>

                        <!-- 2. Link: سجلات الحضور IP -->
                        <a href="{{ url('/zkt') }}" class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:text-sky-600 dark:hover:text-sky-400 transition-all border border-gray-200 dark:border-white/5">
                            <i data-lucide="network" class="w-4 h-4"></i>
                            <span>سجلات الحضور (IP)</span>
                        </a>

                        <!-- 3. Dropdown: إضافة -->
                        <div class="relative">
                            <button id="add-btn" type="button" class="flex items-center gap-1.5 px-4.5 p-4 rounded-xl bg-sky-500 text-white hover:bg-sky-400 transition-all font-black shadow-lg shadow-sky-500/20">
                                <i data-lucide="plus-circle" class="w-4 h-4 text-white"></i>
                                <span>إضافة</span>
                                <i id="add-chevron" data-lucide="chevron-down" class="w-3.5 h-3.5 text-white opacity-80 transition-transform"></i>
                            </button>
                            <div id="add-dropdown" class="absolute right-0 mt-2 w-56 bg-white dark:bg-[#121212] border border-gray-200 dark:border-white/10 rounded-2xl shadow-2xl py-2 hidden transition-all z-50 text-right">
                                <div class="px-4 py-1.5 text-[9px] font-bold text-gray-400 dark:text-gray-600 uppercase tracking-widest border-b border-gray-100 dark:border-white/5 mb-1">الماليات والرواتب</div>
                                <a href="{{ url('/salf') }}" class="block px-4 py-2 hover:bg-sky-500/10 hover:text-sky-600 dark:hover:text-sky-400 text-xs font-bold text-gray-700 dark:text-gray-300 transition-colors">إضافة سلفة</a>
                                <a href="{{ url('/loans') }}" class="block px-4 py-2 hover:bg-sky-500/10 hover:text-sky-600 dark:hover:text-sky-400 text-xs font-bold text-gray-700 dark:text-gray-300 transition-colors">إضافة قرض</a>
                                <a href="{{ url('/disemp') }}" class="block px-4 py-2 hover:bg-sky-500/10 hover:text-sky-600 dark:hover:text-sky-400 text-xs font-bold text-gray-700 dark:text-gray-300 transition-colors">إضافة خصم</a>
                                <a href="{{ url('/hafz') }}" class="block px-4 py-2 hover:bg-sky-500/10 hover:text-sky-600 dark:hover:text-sky-400 text-xs font-bold text-gray-700 dark:text-gray-300 transition-colors">إضافة حافز</a>
                                <a href="{{ url('/badel_transport') }}" class="block px-4 py-2 hover:bg-sky-500/10 hover:text-sky-600 dark:hover:text-sky-400 text-xs font-bold text-gray-700 dark:text-gray-300 transition-colors">بدل مواصلات</a>
                                <a href="{{ url('/dis_transport') }}" class="block px-4 py-2 hover:bg-sky-500/10 hover:text-sky-600 dark:hover:text-sky-400 text-xs font-bold text-gray-700 dark:text-gray-300 transition-colors">خصم مواصلات</a>

                                <div class="px-4 py-1.5 text-[9px] font-bold text-gray-400 dark:text-gray-600 uppercase tracking-widest border-t border-b border-gray-100 dark:border-white/5 my-1">الطلبات والإجازات</div>
                                <a href="{{ url('/vacations') }}" class="block px-4 py-2 hover:bg-sky-500/10 hover:text-sky-600 dark:hover:text-sky-400 text-xs font-bold text-gray-700 dark:text-gray-300 transition-colors">إضافة إجازة</a>
                                <a href="{{ url('/ple') }}" class="block px-4 py-2 hover:bg-sky-500/10 hover:text-sky-600 dark:hover:text-sky-400 text-xs font-bold text-gray-700 dark:text-gray-300 transition-colors">إضافة إذن</a>
                                <a href="{{ url('/forget_finger') }}" class="block px-4 py-2 hover:bg-sky-500/10 hover:text-sky-600 dark:hover:text-sky-400 text-xs font-bold text-gray-700 dark:text-gray-300 transition-colors">إدارة البصمات</a>
                                <a href="{{ url('/plzot') }}" class="block px-4 py-2 hover:bg-sky-500/10 hover:text-sky-600 dark:hover:text-sky-400 text-xs font-bold text-gray-700 dark:text-gray-300 transition-colors">الوقت الإضافي</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4 sm:gap-6">
                <div
                    class="hidden md:flex items-center gap-2 px-4 py-2 bg-green-100 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-full transition-colors">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <span
                        class="text-[10px] text-green-600 dark:text-green-500 font-bold uppercase tracking-widest">System
                        Secure</span>
                </div>



                <button id="theme-toggle"
                    class="relative p-2 text-gray-500 dark:text-gray-400 hover:text-sky-600 dark:hover:text-sky-400 transition-all focus:outline-none">
                    <i data-lucide="sun" class="w-6 h-6 hidden dark:block"></i>
                    <i data-lucide="moon" class="w-6 h-6 block dark:hidden"></i>
                </button>

                <div class="relative inline-block" id="notif-wrapper">
                    <button id="notif-button"
                        class="relative p-2 text-gray-500 dark:text-gray-400 hover:text-sky-600 dark:hover:text-sky-400 transition-all focus:outline-none">
                        <i data-lucide="bell" class="w-6 h-6"></i>
                        <span id="notif-ping" class="absolute top-2 right-2 {{ Auth::user()->unreadNotifications->count() > 0 ? 'flex' : 'hidden' }} h-3 w-3">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                            <span
                                class="relative inline-flex rounded-full h-3 w-3 bg-sky-500 border-2 border-white dark:border-[#080808]"></span>
                        </span>
                    </button>

                    <div id="notif-dropdown"
                        class="hidden absolute left-0 mt-4 w-72 sm:w-80 bg-white dark:bg-[#121212] border border-gray-200 dark:border-white/10 rounded-2xl shadow-2xl z-50 overflow-hidden transform origin-top-left transition-all">
                        <div
                            class="px-5 py-4 border-b border-gray-200 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-[#181818] transition-colors rounded-t-2xl">
                            <span class="text-sm font-bold text-gray-900 dark:text-white">الإشعارات</span>
                            <span id="unreadNotificationsCount"
                                class="text-[10px] bg-sky-500 text-white px-2 py-0.5 rounded-full font-bold uppercase">{{ Auth::user()->unreadNotifications->count() }} New</span>
                        </div>

                        <div id="notificationsList" class="max-h-[350px] overflow-y-auto custom-scrollbar">
                            @foreach (Auth::user()->unreadNotifications as $noti)
                                <a href="{{ route('show.talb', ['id' => $noti->data['id'], 'type' => $noti->data['type']]) }}"
                                    class="flex items-start gap-4 px-5 py-4 hover:bg-gray-50 dark:hover:bg-white/[0.03] transition-colors border-b border-gray-100 dark:border-white/5 group">
                                    <div class="relative">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-sky-500/10 flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0 group-hover:scale-110 transition-transform font-black">
                                            {{ mb_substr($noti->data['user'], 0, 1) }}
                                        </div>
                                        <div
                                            class="absolute -bottom-1 -right-1 w-4 h-4 bg-sky-500 rounded-full border-2 border-white dark:border-[#121212] flex items-center justify-center">
                                            <i data-lucide="bell" class="w-2 h-2 text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0 text-right">
                                        <div class="flex justify-between items-center mb-1">
                                            <p class="text-xs font-bold text-gray-900 dark:text-gray-200">{{ $noti->data['user'] }}</p>
                                            <span class="text-[9px] text-gray-400 font-medium">{{ $noti->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-[10px] text-gray-500 leading-relaxed">{{ $noti->data['masg'] }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        <a href="{{ url('MarkAsRead_all') }}"
                            class="block py-4 text-center text-[10px] font-bold text-sky-600 dark:text-sky-400 hover:bg-gray-50 dark:hover:bg-white/[0.05] transition-colors uppercase tracking-[0.2em] border-t border-gray-200 dark:border-white/5">
                            تعيين الكل كمقروء
                        </a>
                    </div>
                </div>
            </div>
        </header>
        <audio id="notificationSound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" preload="auto"></audio>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var lastUnreadCount = {{ Auth::user()->unreadNotifications->count() }};
                
                // Toggle header dropdowns on click
                const reportsBtn = document.getElementById('reports-btn');
                const reportsDropdown = document.getElementById('reports-dropdown');
                const reportsChevron = document.getElementById('reports-chevron');

                const addBtn = document.getElementById('add-btn');
                const addDropdown = document.getElementById('add-dropdown');
                const addChevron = document.getElementById('add-chevron');

                if (reportsBtn && reportsDropdown) {
                    reportsBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        reportsDropdown.classList.toggle('hidden');
                        if (reportsChevron) reportsChevron.classList.toggle('rotate-180');
                        // Close add dropdown
                        if (addDropdown) {
                            addDropdown.classList.add('hidden');
                            if (addChevron) addChevron.classList.remove('rotate-180');
                        }
                    });
                }

                if (addBtn && addDropdown) {
                    addBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        addDropdown.classList.toggle('hidden');
                        if (addChevron) addChevron.classList.toggle('rotate-180');
                        // Close reports dropdown
                        if (reportsDropdown) {
                            reportsDropdown.classList.add('hidden');
                            if (reportsChevron) reportsChevron.classList.remove('rotate-180');
                        }
                    });
                }

                // Close on click outside
                document.addEventListener('click', function() {
                    if (reportsDropdown) {
                        reportsDropdown.classList.add('hidden');
                        if (reportsChevron) reportsChevron.classList.remove('rotate-180');
                    }
                    if (addDropdown) {
                        addDropdown.classList.add('hidden');
                        if (addChevron) addChevron.classList.remove('rotate-180');
                    }
                });

                function fetchNotifications() {
                    fetch("{{ route('notifications.latest') }}")
                        .then(response => response.json())
                        .then(data => {
                            if (data.unreadCount > lastUnreadCount) {
                                // Play sound
                                var sound = document.getElementById('notificationSound');
                                if (sound) {
                                    sound.play().catch(function(error) {
                                        console.log("Sound play failed:", error);
                                    });
                                }
                            }
                            
                            if (data.unreadCount !== lastUnreadCount) {
                                // Update Badge
                                var countElem = document.getElementById('unreadNotificationsCount');
                                var pingElem = document.getElementById('notif-ping');
                                if (countElem) countElem.innerText = data.unreadCount + ' New';
                                if (pingElem) {
                                    if (data.unreadCount > 0) pingElem.classList.remove('hidden');
                                    else pingElem.classList.add('hidden');
                                }
                                
                                // Rebuild list
                                var listHtml = '';
                                data.notifications.forEach(function(noti) {
                                    var firstChar = noti.user ? noti.user.charAt(0) : 'N';
                                    listHtml += `
                                        <a href="${noti.url}"
                                            class="flex items-start gap-4 px-5 py-4 hover:bg-gray-50 dark:hover:bg-white/[0.03] transition-colors border-b border-gray-100 dark:border-white/5 group">
                                            <div class="relative">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-sky-100 dark:bg-sky-500/10 flex items-center justify-center text-sky-600 dark:text-sky-500 shrink-0 group-hover:scale-110 transition-transform font-black">
                                                    ${firstChar}
                                                </div>
                                                <div
                                                    class="absolute -bottom-1 -right-1 w-4 h-4 bg-sky-500 rounded-full border-2 border-white dark:border-[#121212] flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell w-2 h-2 text-white"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0 text-right">
                                                <div class="flex justify-between items-center mb-1">
                                                    <p class="text-xs font-bold text-gray-900 dark:text-gray-200">${noti.user}</p>
                                                    <span class="text-[9px] text-gray-400 font-medium">${noti.created_at}</span>
                                                </div>
                                                <p class="text-[10px] text-gray-500 leading-relaxed">${noti.masg}</p>
                                            </div>
                                        </a>
                                    `;
                                });
                                var listElem = document.getElementById('notificationsList');
                                if (listElem) listElem.innerHTML = listHtml;
                            }
                            lastUnreadCount = data.unreadCount;
                        })
                        .catch(error => console.error("Error fetching notifications:", error));
                }

                // Poll every 10 seconds
                setInterval(fetchNotifications, 10000);
            });
        </script>
