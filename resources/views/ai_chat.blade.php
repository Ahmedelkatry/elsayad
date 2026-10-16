@extends('layouts_new.app')

@section('title', 'المساعد الذكي - Pixel AI')

@section('page-header')
<div class="mb-6">
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
            <i class="fa fa-robot text-xl text-white"></i>
        </div>
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">المساعد الذكي</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Pixel AI - اسأل عن الموظفين، الرواتب، الغياب والمزيد</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="flex flex-col h-[calc(100vh-12rem)] bg-white dark:bg-[#1a1a1a] rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
    <!-- Chat Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4 flex justify-between items-center text-white shrink-0">
        <div class="flex items-center gap-2">
            <i class="fa fa-robot text-xl"></i>
            <h3 class="font-bold">المساعد الذكي (Pixel AI)</h3>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="clearAiChat()" class="hover:text-gray-200 transition-colors text-xs bg-white/10 px-3 py-1.5 rounded-lg hover:bg-white/20" title="مسح المحادثة">
                <i class="fa fa-trash ml-1"></i> مسح
            </button>
            <button onclick="openAiSettingsPage()" class="hover:text-gray-200 transition-colors text-xs bg-white/10 px-3 py-1.5 rounded-lg hover:bg-white/20" title="الإعدادات">
                <i class="fa fa-cogs ml-1"></i> إعدادات
            </button>
        </div>
    </div>

    <!-- Messages Area -->
    <div id="ai-chat-messages" class="flex-1 p-4 overflow-y-auto flex flex-col gap-3 bg-gray-50/50 dark:bg-[#111] text-sm scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-700">
        <div class="self-start bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-3 rounded-2xl rounded-tr-none shadow-sm max-w-[85%] text-gray-800 dark:text-gray-200 leading-relaxed">
            مرحباً بك! 🤖<br>أنا المساعد الذكي. يمكنك سؤالي عن غياب الموظفين أو رواتبهم، مثلاً:
            <div class="mt-2 flex flex-wrap gap-2">
                <span onclick="quickQuestion('كم عدد الموظفين')" class="inline-block bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2.5 py-1 rounded-lg text-xs cursor-pointer hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">كم عدد الموظفين</span>
                <span onclick="quickQuestion('غياب اليوم')" class="inline-block bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2.5 py-1 rounded-lg text-xs cursor-pointer hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">غياب اليوم</span>
                <span onclick="quickQuestion('مرتب احمد رضا شهر 5')" class="inline-block bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2.5 py-1 rounded-lg text-xs cursor-pointer hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">مرتب احمد رضا</span>
                <span onclick="quickQuestion('عدد الأقسام')" class="inline-block bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2.5 py-1 rounded-lg text-xs cursor-pointer hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">عدد الأقسام</span>
            </div>
        </div>
    </div>

    <!-- AI Settings Panel (collapsible) -->
    <div id="ai-settings-panel" class="hidden border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 p-4">
        <div class="space-y-3 text-sm text-gray-800 dark:text-gray-200 max-w-lg">
            <div class="flex items-center justify-between">
                <h4 class="font-bold text-xs">إعدادات الذكاء الاصطناعي</h4>
                <button onclick="toggleAiSettings()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-xs">إغلاق <i class="fa fa-times mr-1"></i></button>
            </div>
            <div>
                <label class="block mb-1 font-bold text-xs">محرك الذكاء الاصطناعي</label>
                <select id="ai-provider" onchange="toggleProviderFields()" class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2 outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <option value="ollama">محلي (Ollama) - مجاني</option>
                    <option value="gemini">جوجل (Gemini) - مفتاح API</option>
                    <option value="openrouter">OpenRouter - مفتاح API</option>
                </select>
            </div>
            <div>
                <label class="block mb-1 font-bold text-xs">نموذج Ollama</label>
                <input type="text" id="ai-ollama-model" placeholder="qwen2.5-coder:7b" class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2 outline-none focus:ring-2 focus:ring-blue-500 text-sm text-left" dir="ltr">
            </div>
            <div id="field-gemini" class="hidden">
                <label class="block mb-1 font-bold text-xs">مفتاح جوجل (Gemini API Key)</label>
                <input type="password" id="ai-gemini-key" placeholder="AIzaSy..." class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2 outline-none focus:ring-2 focus:ring-blue-500 text-sm text-left" dir="ltr">
            </div>
            <div id="field-openrouter" class="hidden">
                <label class="block mb-1 font-bold text-xs">مفتاح OpenRouter (API Key)</label>
                <input type="password" id="ai-openrouter-key" placeholder="sk-or-v1-..." class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2 outline-none focus:ring-2 focus:ring-blue-500 text-sm text-left" dir="ltr">
                <p class="text-xs text-gray-400 mt-1">احصل على مفتاحك من <a href="https://openrouter.ai/keys" target="_blank" class="text-blue-400 underline">openrouter.ai</a></p>
            </div>
            <button onclick="saveAiSettings()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl transition-colors text-sm">
                حفظ الإعدادات
            </button>
        </div>
    </div>

    <!-- Input Area -->
    <div class="p-3 bg-white dark:bg-[#1a1a1a] border-t border-gray-100 dark:border-gray-800 flex gap-2 shrink-0">
        <button onclick="toggleAiSettings()" class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-xl w-10 h-10 flex items-center justify-center transition-colors" title="الإعدادات">
            <i class="fa fa-cogs"></i>
        </button>
        <input type="text" id="ai-chat-input" placeholder="اسألني هنا..." class="flex-1 bg-gray-100 dark:bg-gray-800 border-none rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none text-gray-800 dark:text-gray-200 text-sm" onkeypress="if(event.key === 'Enter') sendAiChatMessage()">
        <button onclick="sendAiChatMessage()" class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl w-10 h-10 flex items-center justify-center transition-colors">
            <i class="fa fa-paper-plane"></i>
        </button>
    </div>
</div>

<script>
    function quickQuestion(text) {
        document.getElementById('ai-chat-input').value = text;
        sendAiChatMessage();
    }

    function clearAiChat() {
        if (!confirm('هل تريد مسح المحادثة؟')) return;
        const messagesContainer = document.getElementById('ai-chat-messages');
        messagesContainer.innerHTML = `
            <div class="self-start bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-3 rounded-2xl rounded-tr-none shadow-sm max-w-[85%] text-gray-800 dark:text-gray-200 leading-relaxed">
                مرحباً بك! 🤖<br>أنا المساعد الذكي. يمكنك سؤالي عن غياب الموظفين أو رواتبهم، مثلاً:
                <div class="mt-2 flex flex-wrap gap-2">
                    <span onclick="quickQuestion('كم عدد الموظفين')" class="inline-block bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2.5 py-1 rounded-lg text-xs cursor-pointer hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">كم عدد الموظفين</span>
                    <span onclick="quickQuestion('غياب اليوم')" class="inline-block bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2.5 py-1 rounded-lg text-xs cursor-pointer hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">غياب اليوم</span>
                    <span onclick="quickQuestion('مرتب احمد رضا شهر 5')" class="inline-block bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2.5 py-1 rounded-lg text-xs cursor-pointer hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">مرتب احمد رضا</span>
                    <span onclick="quickQuestion('عدد الأقسام')" class="inline-block bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2.5 py-1 rounded-lg text-xs cursor-pointer hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">عدد الأقسام</span>
                </div>
            </div>
        `;
    }

    function toggleAiSettings() {
        const panel = document.getElementById('ai-settings-panel');
        panel.classList.toggle('hidden');
        if (!panel.classList.contains('hidden')) {
            loadAiSettings();
        }
    }

    function openAiSettingsPage() {
        const panel = document.getElementById('ai-settings-panel');
        panel.classList.remove('hidden');
        loadAiSettings();
    }

    function loadAiSettings() {
        fetch('/chat/settings')
            .then(res => res.json())
            .then(data => {
                document.getElementById('ai-provider').value = data.provider || 'ollama';
                document.getElementById('ai-gemini-key').value = data.gemini_api_key || '';
                document.getElementById('ai-openrouter-key').value = data.openrouter_api_key || '';
                document.getElementById('ai-ollama-model').value = data.ollama_model || 'qwen2.5-coder:7b';
                toggleProviderFields();
            });
    }

    function toggleProviderFields() {
        const provider = document.getElementById('ai-provider').value;
        document.getElementById('field-gemini').style.display = (provider === 'gemini') ? 'block' : 'none';
        document.getElementById('field-openrouter').style.display = (provider === 'openrouter') ? 'block' : 'none';
    }

    function saveAiSettings() {
        const provider = document.getElementById('ai-provider').value;
        const geminiKey = document.getElementById('ai-gemini-key').value;
        const openrouterKey = document.getElementById('ai-openrouter-key').value;
        const ollamaModel = document.getElementById('ai-ollama-model').value;

        fetch('/chat/settings', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                provider: provider,
                gemini_api_key: geminiKey,
                openrouter_api_key: openrouterKey,
                ollama_model: ollamaModel
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('تم حفظ الإعدادات بنجاح!');
                document.getElementById('ai-settings-panel').classList.add('hidden');
            } else {
                alert('حدث خطأ أثناء الحفظ.');
            }
        })
        .catch(err => {
            alert('فشل الاتصال بالخادم.');
        });
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

    // Focus input on page load
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('ai-chat-input').focus();
    });
</script>
@endsection
