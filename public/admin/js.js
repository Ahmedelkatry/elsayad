// Tailwind configuration
tailwind.config = {
    darkMode: 'class'
};

// Theme check
if (localStorage.theme === 'light') {
    document.documentElement.classList.remove('dark');
} else {
    document.documentElement.classList.add('dark');
}
document.addEventListener('DOMContentLoaded', () => {
    // Theme Toggle
    const themeToggleBtn = document.getElementById('theme-toggle');
    themeToggleBtn.addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');
        localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    });

    // Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const toggleBtn = document.getElementById('toggle-sidebar');
    const mobileOverlay = document.getElementById('mobile-overlay');
    const closeMobileMenuBtn = document.getElementById('close-mobile-menu');

    function updateToggleIcon() {
        if (!toggleBtn) return;
        const icon = toggleBtn.querySelector('i');
        if (!icon) return;
        const isHidden = window.innerWidth < 1024 ? !sidebar.classList.contains('mobile-show') : sidebar.classList.contains('sidebar-hidden');
        icon.setAttribute('data-lucide', isHidden ? 'menu' : 'arrow-left-to-line');
        lucide.createIcons();
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            if (window.innerWidth < 1024) {
                sidebar.classList.toggle('mobile-show');
                mobileOverlay.classList.toggle('hidden');
            } else {
                sidebar.classList.toggle('sidebar-hidden');
                mainContent.classList.toggle('content-full');
            }
            updateToggleIcon();
        });
    }

    // Close mobile sidebar
    const closeMobileSidebar = () => {
        if (sidebar) {
            sidebar.classList.remove('mobile-show');
        }
        if (mobileOverlay) {
            mobileOverlay.classList.add('hidden');
        }
        updateToggleIcon();
    };

    if (closeMobileMenuBtn) {
        closeMobileMenuBtn.addEventListener('click', closeMobileSidebar);
    }

    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', closeMobileSidebar);
    }

    // Notifications Dropdown Toggle
    const notifBtn = document.getElementById('notif-button');
    const notifDropdown = document.getElementById('notif-dropdown');
    
    if (notifBtn && notifDropdown) {
        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown.classList.toggle('hidden');
        });
        document.addEventListener('click', (e) => {
            if (!notifDropdown.contains(e.target) && e.target !== notifBtn)
                notifDropdown.classList.add('hidden');
        });

        let lastUnreadCount = parseInt(document.getElementById('unread-badge')?.querySelector('span.relative')?.textContent || '0');

        // Professional Notification Sound (System Notification Chime using Web Audio API)
        function playNotificationSound() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                
                // Sound effect chime
                const playChime = (time, freq, type, duration) => {
                    const osc = audioCtx.createOscillator();
                    const gainNode = audioCtx.createGain();
                    osc.type = type;
                    osc.frequency.setValueAtTime(freq, time);
                    gainNode.gain.setValueAtTime(0.08, time);
                    gainNode.gain.exponentialRampToValueAtTime(0.0001, time + duration);
                    osc.connect(gainNode);
                    gainNode.connect(audioCtx.destination);
                    osc.start(time);
                    osc.stop(time + duration);
                };

                const now = audioCtx.currentTime;
                // Double chime (E6 then A6)
                playChime(now, 1318.51, 'sine', 0.15); // E6
                playChime(now + 0.12, 1760.00, 'sine', 0.35); // A6
            } catch (e) {
                console.error("Web Audio API not supported or blocked by user gesture:", e);
            }
        }

        // Polling function for live notifications
        function pollNotifications() {
            fetch('/notifications/api/latest')
                .then(response => response.json())
                .then(data => {
                    // Update Badge
                    const badge = document.getElementById('unread-badge');
                    const dropdownLabel = document.getElementById('dropdown-unread-label');
                    
                    if (data.unread_count > 0) {
                        if (badge) {
                            badge.classList.remove('hidden');
                            badge.querySelector('span.relative').textContent = data.unread_count > 9 ? '9+' : data.unread_count;
                        }
                        if (dropdownLabel) {
                            dropdownLabel.classList.remove('hidden');
                            dropdownLabel.textContent = `${data.unread_count} جديد`;
                        }
                        
                        // Play sound only if unread count increased
                        if (data.unread_count > lastUnreadCount) {
                            playNotificationSound();
                        }
                    } else {
                        if (badge) badge.classList.add('hidden');
                        if (dropdownLabel) dropdownLabel.classList.add('hidden');
                    }
                    
                    lastUnreadCount = data.unread_count;

                    // Update List
                    const listContainer = document.getElementById('dropdown-notif-list');
                    if (listContainer && data.notifications) {
                        if (data.notifications.length === 0) {
                            listContainer.innerHTML = `
                                <div class="px-5 py-8 text-center text-gray-400 text-xs font-bold">
                                    <i data-lucide="bell-off" class="w-8 h-8 mx-auto mb-2 opacity-30"></i>
                                    لا توجد إشعارات
                                </div>
                            `;
                        } else {
                            let html = '';
                            data.notifications.forEach(notif => {
                                const isReadClass = notif.is_read ? '' : 'bg-yellow-50/50 dark:bg-yellow-500/5';
                                const iconClass = notif.type === 'contract' 
                                    ? 'bg-blue-100 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400' 
                                    : 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400';
                                const iconName = notif.type === 'contract' ? 'file-signature' : 'banknote';
                                const unreadDot = notif.is_read ? '' : `<span class="w-2 h-2 rounded-full bg-yellow-500 flex-shrink-0 ml-1 unread-dot" data-id="${notif.id}"></span>`;

                                html += `
                                    <a href="/contracts/${notif.contract_id || 0}" data-notif-id="${notif.id}"
                                        class="flex items-start gap-4 px-5 py-4 hover:bg-gray-50 dark:hover:bg-white/[0.03] transition-colors border-b border-gray-100 dark:border-white/5 group ${isReadClass} notification-item">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 ${iconClass}">
                                            <i data-lucide="${iconName}" class="w-5 h-5"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-center mb-0.5">
                                                <p class="text-xs font-bold text-gray-900 dark:text-gray-200 truncate">${notif.title}</p>
                                                ${unreadDot}
                                            </div>
                                            <p class="text-[10px] text-gray-500 leading-relaxed line-clamp-2">${notif.body}</p>
                                            <p class="text-[9px] text-gray-400 mt-1">${notif.time}</p>
                                        </div>
                                    </a>
                                `;
                            });
                            listContainer.innerHTML = html;
                        }
                        lucide.createIcons();
                        setupDropdownMarkReadEvents();
                    }
                })
                .catch(err => console.error('Error fetching notifications:', err));
        }

        // Setup click events on notification items in dropdown to mark read via AJAX
        function setupDropdownMarkReadEvents() {
            document.querySelectorAll('.notification-item').forEach(item => {
                // Ensure event is only attached once
                if (item.dataset.hasListener) return;
                item.dataset.hasListener = "true";

                item.addEventListener('click', function(e) {
                    const notifId = this.dataset.notifId;
                    const unreadDot = this.querySelector('.unread-dot');
                    
                    if (unreadDot) {
                        // Mark as read via API without page reload
                        fetch(`/notifications/${notifId}/read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                                document.querySelector('input[name="_token"]')?.value || ''
                            }
                        })
                        .then(() => {
                            // Update local UI immediately
                            unreadDot.remove();
                            this.classList.remove('bg-sky-50/50', 'dark:bg-sky-500/10');
                            
                            // Decrease counters
                            const badge = document.getElementById('unread-badge');
                            const dropdownLabel = document.getElementById('dropdown-unread-label');
                            
                            if (lastUnreadCount > 0) {
                                lastUnreadCount--;
                                if (lastUnreadCount > 0) {
                                    if (badge) badge.querySelector('span.relative').textContent = lastUnreadCount > 9 ? '9+' : lastUnreadCount;
                                    if (dropdownLabel) dropdownLabel.textContent = `${lastUnreadCount} جديد`;
                                } else {
                                    if (badge) badge.classList.add('hidden');
                                    if (dropdownLabel) dropdownLabel.classList.add('hidden');
                                }
                            }
                        })
                        .catch(err => console.error('Error marking as read:', err));
                    }
                });
            });
        }

        // Poll every 10 seconds
        setInterval(pollNotifications, 10000);
    }

    // Modals Logic
    const openModal = (modal) => {
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.querySelector('.modal-content').classList.remove('scale-95');
        modal.querySelector('.modal-content').classList.add('scale-100');
    };
    const closeModal = (modal) => {
        modal.classList.add('opacity-0', 'pointer-events-none');
        modal.querySelector('.modal-content').classList.remove('scale-100');
        modal.querySelector('.modal-content').classList.add('scale-95');
    };

    document.querySelectorAll('[data-modal-target]').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-modal-target');
            const targetModal = document.getElementById(targetId);
            if (targetModal) openModal(targetModal);
        });
    });

    document.querySelectorAll('.close-modal-btn, .cancel-modal-btn, .modal-backdrop').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const parentModal = btn.closest('.pixel-modal');
            if (parentModal) closeModal(parentModal);
        });
    });

    // Table Enhancer Engine
    class PixelTableEnhancer {
        constructor(tableId, rowsPerPage = 5) {
            this.table = document.getElementById(tableId);
            if (!this.table) return;
            this.rowsPerPage = rowsPerPage;
            this.currentPage = 1;
            this.tbody = this.table.querySelector('tbody');
            this.originalRows = Array.from(this.tbody.querySelectorAll('tr'));
            this.filteredRows = [...this.originalRows];
            this.currentSortCol = -1;
            this.sortAsc = true;
            this.tableName = tableId;
            this.init();
        }

        init() {
            this.applyStyles();
            this.injectControls();
            this.setupSorting();
            this.render();
        }

        applyStyles() {
            const wrapper = this.table.parentElement;
            wrapper.className = "stat-card rounded-[2rem] overflow-hidden bg-white dark:bg-[#111] border border-gray-200 dark:border-white/5 shadow-xl dark:shadow-2xl mb-12 overflow-x-auto transition-colors duration-300";
            this.table.className = "w-full text-right text-sm min-w-[600px]";

            const thead = this.table.querySelector('thead');
            thead.className = "bg-gradient-to-r from-sky-600 to-sky-700 text-white text-[11px] uppercase tracking-widest border-b border-sky-800 transition-colors duration-300 select-none shadow-sm";

            thead.querySelectorAll('th').forEach((th, index) => {
                const originalText = th.innerText;
                th.dataset.index = index;
                th.className = "px-8 py-5 text-white font-bold whitespace-nowrap cursor-pointer hover:bg-sky-700/60 transition-colors group";
                th.innerHTML = `
                            <div class="flex items-center gap-2 justify-start">
                                <span>${originalText}</span>
                                <i data-lucide="chevrons-up-down" class="w-4 h-4 opacity-70 group-hover:opacity-100 transition-opacity text-white"></i>
                            </div>
                        `;
            });
            this.tbody.className = "divide-y divide-gray-100 dark:divide-white/5 transition-colors duration-300";
        }

        setupSorting() {
            const headers = this.table.querySelectorAll('th');
            headers.forEach(th => {
                th.addEventListener('click', () => {
                    const colIndex = parseInt(th.dataset.index);
                    if (this.currentSortCol === colIndex) {
                        this.sortAsc = !this.sortAsc;
                    } else {
                        this.currentSortCol = colIndex;
                        this.sortAsc = true;
                    }
                    this.filteredRows.sort((rowA, rowB) => {
                        const cellA = rowA.children[colIndex].innerText.trim();
                        const cellB = rowB.children[colIndex].innerText.trim();
                        return this.sortAsc ? cellA.localeCompare(cellB, 'ar') : cellB.localeCompare(cellA, 'ar');
                    });
                    this.currentPage = 1;
                    this.render();
                });
            });
        }

        exportToExcel() {
            const headers = Array.from(this.table.querySelectorAll('thead th span')).map(span => span.innerText.trim());
            const data = this.filteredRows.map(row => Array.from(row.children).map(cell => cell.innerText.trim()));
            let csvContent = "\uFEFF" + headers.join(",") + "\n";
            data.forEach(row => {
                const cleanRow = row.map(text => `"${text.replace(/"/g, '""')}"`);
                csvContent += cleanRow.join(",") + "\n";
            });

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement("a");
            const url = URL.createObjectURL(blob);
            const date = new Date().toISOString().split('T')[0];
            link.setAttribute("href", url);
            link.setAttribute("download", `${this.tableName}_Export_${date}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        injectControls() {
            const header = document.createElement('div');
            header.className = "p-6 border-b border-gray-200 dark:border-white/5 flex flex-col md:flex-row justify-between items-center gap-4 transition-colors duration-300";
            header.innerHTML = `
                        <div class="flex flex-wrap items-center gap-4 w-full md:w-auto justify-between md:justify-start">
                            <div class="flex items-center gap-3">
                                <span class="text-gray-800 dark:text-white text-xs font-bold">عرض</span>
                                <select class="rows-select bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-sky-600 dark:text-sky-400 rounded-lg px-2 py-1 outline-none text-xs transition-colors duration-300">
                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <button class="export-excel-btn flex items-center gap-2 px-3 py-1.5 bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-500 hover:bg-green-200 dark:hover:bg-green-500/20 border border-green-200 dark:border-green-500/20 rounded-lg text-xs font-bold transition-all active:scale-95">
                                <i data-lucide="sheet" class="w-4 h-4"></i> إكسيل
                            </button>
                        </div>
                        <div class="relative w-full md:w-64">
                            <input type="text" class="search-input w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white pr-4 pl-4 py-2 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors duration-300" placeholder="ابحث في هذا الجدول...">
                        </div>
                    `;
            this.table.before(header);

            const footer = document.createElement('div');
            footer.className = "p-5 border-t border-gray-200 dark:border-white/5 flex flex-col md:flex-row items-center justify-between bg-gray-50 dark:bg-transparent gap-4 transition-colors duration-300 rounded-b-[2rem]";
            footer.innerHTML = `
                        <span class="info-text text-[10px] text-gray-500 font-bold uppercase tracking-widest text-center"></span>
                        <div class="pagination-btns flex gap-1 flex-wrap justify-center"></div>
                    `;
            this.table.after(footer);

            header.querySelector('.export-excel-btn').addEventListener('click', () => this.exportToExcel());
            header.querySelector('.search-input').addEventListener('input', (e) => this.filter(e.target.value));
            header.querySelector('.rows-select').addEventListener('change', (e) => {
                this.rowsPerPage = parseInt(e.target.value);
                this.currentPage = 1;
                this.render();
            });
            this.paginationContainer = footer.querySelector('.pagination-btns');
            this.infoText = footer.querySelector('.info-text');
        }

        filter(query) {
            this.filteredRows = this.originalRows.filter(row => row.innerText.toLowerCase().includes(query.toLowerCase()));
            this.currentPage = 1;
            this.render();
        }

        render() {
            const start = (this.currentPage - 1) * this.rowsPerPage;
            const end = start + this.rowsPerPage;
            this.tbody.innerHTML = "";
            const visibleRows = this.filteredRows.slice(start, end);

            visibleRows.forEach(row => {
                row.className = "hover:bg-sky-50/70 dark:hover:bg-sky-500/10 transition-colors duration-200 cursor-default";
                row.querySelectorAll('td').forEach(td => td.className = "px-8 py-5 text-gray-700 dark:text-gray-300 font-medium whitespace-nowrap");
                this.tbody.appendChild(row);
            });

            if (visibleRows.length === 0) {
                this.tbody.innerHTML = `<tr><td colspan="100%" class="p-10 text-center text-gray-500 dark:text-gray-600">لا توجد سجلات مطابقة</td></tr>`;
            }
            this.renderPagination();
            lucide.createIcons();
        }

        renderPagination() {
            const totalPages = Math.ceil(this.filteredRows.length / this.rowsPerPage);
            this.paginationContainer.innerHTML = "";
            this.infoText.innerText = `السجلات: ${this.filteredRows.length} | صفحة ${this.currentPage} من ${totalPages || 1}`;
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.innerText = i;
                btn.className = `w-7 h-7 rounded-lg text-[10px] font-bold transition-all ${this.currentPage === i ? 'bg-sky-500 text-white shadow-lg shadow-sky-500/20' : 'bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-500 hover:bg-gray-300 dark:hover:bg-white/10'}`;
                btn.onclick = () => { this.currentPage = i; this.render(); };
                this.paginationContainer.appendChild(btn);
            }
        }
    }

    // تشغيل محرك الجداول
    new PixelTableEnhancer('t1', 10);
    new PixelTableEnhancer('t2', 10);
    new PixelTableEnhancer('t3', 10);

    new PixelTableEnhancer('t4', 10);
    new PixelTableEnhancer('t5', 10);

    // جدول الأقساط - يشتغل بس لو الصفحة موجودة
    if (document.getElementById('t-inst')) {
        new PixelTableEnhancer('t-inst', 15);
    }

    lucide.createIcons();
    tailwind.config = { darkMode: 'class' }
    if (localStorage.theme === 'light') {
        document.documentElement.classList.remove('dark');
    } else {
        document.documentElement.classList.add('dark');
    }
    // تحديد جميع الروابط داخل السايدبار (بما فيها روابط الدروب داون إذا أردت)
    const sidebarLinks = document.querySelectorAll('#sidebar nav a');

    sidebarLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            // إذا كان الرابط لا يؤدي لصفحة أخرى (لأغراض المعاينة فقط)
            // e.preventDefault();

            // 1. إزالة كلاس active-link من جميع الروابط
            sidebarLinks.forEach(l => {
                l.classList.remove('active-link');
                // إعادة كلاسات الحالة العادية (اللون الرمادي وتأثيرات التمرير)
                l.classList.add('text-gray-500', 'hover:text-sky-400', 'hover:bg-sky-500/10');

                // إزالة الخط العريض من النص إن وجد
                const span = l.querySelector('span');
                if (span) span.classList.remove('font-bold');
            });

            // 2. إضافة التنسيق النشط للرابط الذي تم الضغط عليه
            this.classList.add('active-link');
            // إزالة كلاسات الحالة العادية حتى لا تتعارض مع التنسيق النشط
            this.classList.remove('text-gray-500', 'hover:text-sky-400', 'hover:bg-sky-500/10');

            // جعل النص عريضاً
            const span = this.querySelector('span');
            if (span) span.classList.add('font-bold');
        });
    });
});
