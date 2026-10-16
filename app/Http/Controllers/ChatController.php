<?php

namespace App\Http\Controllers;

use App\Models\count;
use App\Models\employees;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    private function getTenantSettingsPath(): string
    {
        $dir = storage_path('app/ai_settings');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir . '/ai_settings.json';
    }

    private function loadSettings()
    {
        $path = $this->getTenantSettingsPath();
        $defaults = [
            'provider'           => 'ollama',
            'gemini_api_key'     => '',
            'openrouter_api_key' => '',
            'ollama_model'       => 'qwen2.5-coder:7b',
            'telegram_bot_token' => ''
        ];
        if (file_exists($path)) {
            return array_merge($defaults, json_decode(file_get_contents($path), true) ?: []);
        }
        return $defaults;
    }

    public function getSettings()
    {
        return response()->json($this->loadSettings());
    }

    public function saveSettings(Request $request)
    {
        $settings = [
            'provider'           => $request->input('provider', 'ollama'),
            'gemini_api_key'     => $request->input('gemini_api_key', ''),
            'openrouter_api_key' => $request->input('openrouter_api_key', ''),
            'ollama_model'       => $request->input('ollama_model', 'qwen2.5-coder:7b'),
            'telegram_bot_token' => $request->input('telegram_bot_token', '')
        ];
        file_put_contents($this->getTenantSettingsPath(), json_encode($settings, JSON_PRETTY_PRINT));
        return response()->json(['success' => true]);
    }

    private function generateAndExecuteSQL($userQuestion, $provider, $geminiApiKey, $ollamaModel, $openrouterApiKey = '')
    {
        $tables = [
            'employees', 'sections', 'shifts', 'vacations', 'loans', 'salves', 
            'pleases', 'disemps', 'hafzs', 'counts', 'zkts', 'plzots', 'purchases', 
            'taminats', 'dis_transports', 'badel_transports', 'eids', 'hafzeids', 
            'evaluations', 'forget_fingers', 'location_requests', 'change_shifts', 
            'sup_shifts', 'shift_weeks', 'ipzkts', 'head_sections', 'counts_emps',
            'nozkts', 'tlbpleases'
        ];
        $simplifiedSchema = "";
        foreach ($tables as $table) {
            $columns = \Illuminate\Support\Facades\Schema::getColumnListing($table);
            if (!empty($columns)) {
                $simplifiedSchema .= "Table $table: " . implode(', ', $columns) . "\n";
            }
        }

        $sqlPrompt = "أنت مبرمج قواعد بيانات محترف. لديك الجداول وأسماء أعمدتها التالية:\n$simplifiedSchema\n
        ملاحظات هامة حول العلاقات بين الجداول وبنية الأعمدة:
        - يربط جدول الموظفين (employees) بالجداول الأخرى باستخدام الحقل 'employees_id' أو 'numid'.
        - جدول البصمات الخام (zkts) يحتوي على حركات البصمة (الحقول: date للتاريخ والوقت، numid لرقم بصمة الموظف، name للاسم، status للحالة).
        - جدول الحسابات اليومية (counts) يحتوي على ملخص الحضور اليومي للموظف (الحقل 'date' للتاريخ، 'employees_id' للموظف).
        - جدول السلف (salves) يحتوي على السلف الخاصة بالموظفين (يرتبط بـ employees_id). العمود المسؤول عن تاريخ السلفة هو 'date'، والعمود المسؤول عن قيمة السلفة هو 'salf'.
        - جدول القروض (loans) يحتوي على القروض الخاصة بالموظفين (يرتبط بـ employees_id). الأعمدة المسؤولة عن تاريخ القرض هي 'date_start' و'date_end' (تنبيه: لا يوجد عمود باسم 'date' في جدول loans). والعمود المسؤول عن قيمة القرض هو 'mony'.
        - جدول الخصومات (disemps) يحتوي على الخصومات الخاصة بالموظفين (يرتبط بـ employees_id).
        - جدول الأذونات (pleases) يحتوي على أذونات الموظفين (يرتبط بـ employees_id).
        - جدول قبول العمل الإضافي/الأوفر تايم (plzots) يحتوي على الأوفر تايم الموافق عليه للموظفين (يرتبط بـ employees_id).
        - جدول الإجازات (vacations) يحتوي على إجازات الموظفين (يرتبط بـ employees_id).
        
        تنبيه هام جداً:
        - عند الاستعلام من الجداول الفرعية التي ترتبط بالموظف (مثل `disemps` للخصومات، `salves` للسلف، `pleases` للأذونات، `vacations` للإجازات، `loans` للقروض، إلخ)، **يجب دائماً عمل `JOIN` مع جدول الموظفين `employees`** واختيار حقل اسم الموظف (`employees.name`) لكي يتم عرض الأسماء الفعلية للموظفين في الإجابة بدلاً من مجرد أرقام الـ ID الخاصة بهم.
        - عند السؤال عن \"سلفة\" (سلف)، استخدم جدول 'salves' والعمود 'date' وقيمة السلفة في عمود 'salf'.
        - عند السؤال عن \"قرض\" (قروض)، استخدم جدول 'loans' والعمودين 'date_start' و'date_end'. لا تخلط بين الجدولين أبداً!
        - عند البحث عن اسم الموظف في جدول الموظفين (employees) في استعلام الـ SQL، لا تستخدم '=' أبداً بل استخدم دائماً 'LIKE' مع علامات النسب المئوية '%' (مثال: `WHERE name LIKE '%عبد%الرحمن%عز%'` أو `WHERE name LIKE '%ياسر%مكي%'`) لتفادي أي مسافات زائدة في قاعدة البيانات أو أخطاء إملائية بسيطة من المستخدم مثل (عبد الحمن بدلاً من عبد الرحمن).
        
        السؤال: \"$userQuestion\"\n
        المطلوب: اكتب كود SQL (SELECT) فقط يجيب على السؤال بدقة عالية. لا تكتب أي شرح أو كود خارج SQL block.
        ملاحظة: تاريخ اليوم هو ".Carbon::now()->toDateString()." . استخدمه إذا لزم الأمر.";

        try {
            \Log::info('SQL Prompt:', ['prompt' => $sqlPrompt]);
            if ($provider === 'gemini') {
                $response = Http::timeout(120)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$geminiApiKey}", [
                    'contents' => [
                        ['parts' => [['text' => $sqlPrompt]]],
                    ],
                ]);
                $sql = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';
            } elseif ($provider === 'openrouter') {
                $response = Http::timeout(120)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $openrouterApiKey,
                        'HTTP-Referer'  => config('app.url'),
                    ])
                    ->post('https://openrouter.ai/api/v1/chat/completions', [
                        'model'    => 'openai/gpt-4o-mini',
                        'messages' => [
                            ['role' => 'user', 'content' => $sqlPrompt],
                        ],
                    ]);
                $sql = $response->json()['choices'][0]['message']['content'] ?? '';
            } else {
                $response = Http::timeout(120)->post("http://localhost:11434/api/chat", [
                    'model' => $ollamaModel,
                    'messages' => [
                        ['role' => 'system', 'content' => "أنت خبير استخراج بيانات بصيغة JSON فقط."],
                        ['role' => 'user', 'content' => $sqlPrompt]
                    ],
                    'stream' => false
                ]);
                $sqlData = $response->json();
                $sql = $sqlData['message']['content'] ?? '';
            }

            $sql = trim(str_replace(['```sql', '```'], '', $sql));
            $sql = trim($sql, " \n\r\t\v\0;"); // تنظيف إضافي للفواصل والسطور الجديدة
            \Log::info('Generated SQL:', ['sql' => $sql]);
            
            if (empty($sql)) {
                return "عذراً، لم أتمكن من استخراج استعلام صحيح.";
            }

            if (stripos($sql, 'SELECT') !== 0) {
                return "عذراً، هذا الاستعلام غير مسموح به أمنياً. (يسمح فقط بـ SELECT).";
            }

            $results = \Illuminate\Support\Facades\DB::select($sql);
            \Log::info('SQL Results:', ['results' => $results]);

            if (empty($results)) {
                return "نتيجة الاستعلام: لا توجد سجلات مطابقة في قاعدة البيانات.";
            }

            // تحويل النتائج إلى نص مقروء بدلاً من JSON خام
            $formattedRows = [];
            foreach ($results as $row) {
                $rowArray = (array) $row;
                $parts = [];
                foreach ($rowArray as $key => $value) {
                    $parts[] = "$key: $value";
                }
                $formattedRows[] = implode(' | ', $parts);
            }
            $totalCount = count($formattedRows);
            return "نتائج الاستعلام ($totalCount سجل):\n" . implode("\n", $formattedRows);

        } catch (\Exception $e) {
            \Log::error('SQL Execution Error:', ['error' => $e->getMessage()]);
            return "حدث خطأ أثناء البحث في البيانات: " . $e->getMessage();
        }
    }

    private function processMessageText($message)
    {
        $settings = $this->loadSettings();
        $provider           = $settings['provider'];
        $geminiApiKey       = $settings['gemini_api_key'];
        $openrouterApiKey   = $settings['openrouter_api_key'] ?? '';
        $ollamaModel        = $settings['ollama_model'] ?: 'qwen2.5-coder:7b';

        if ($provider === 'gemini' && empty($geminiApiKey)) {
            return [
                'error' => 'الرجاء إدخال مفتاح API الخاص بجوجل من صفحة الإعدادات أولاً.',
                'status_code' => 400
            ];
        }

        if ($provider === 'openrouter' && empty($openrouterApiKey)) {
            return [
                'error' => 'الرجاء إدخال مفتاح OpenRouter API من صفحة الإعدادات أولاً.',
                'status_code' => 400
            ];
        }

        try {
            // الخطوة 1: استخراج اسم الموظف والشهر والنية باستخدام الذكاء الاصطناعي
            $extractionPrompt = "أنت محلل نصوص خبير. استخرج البيانات التالية بصيغة JSON فقط.
            أمثلة:
            1. 'ساعات عمل ياسر مكي في 1 ابريل' -> {\"employee_name\":\"ياسر مكي\",\"day\":1,\"month\":4,\"year\":2026,\"intent\":[\"work_hours\"]}
            2. 'مرتب احمد رضا شهر 5' -> {\"employee_name\":\"احمد رضا\",\"day\":null,\"month\":5,\"year\":2026,\"intent\":[\"net_salary\"]}
            3. 'كم عدد الأقسام' -> {\"employee_name\":null,\"day\":null,\"month\":null,\"year\":null,\"intent\":[\"general_query\"]}
            
            مهم جداً: استخرج اسم الموظف من النص كما هو بالضبط بدون أي تعديل.
            الصيغة المطلوبة:
            {
                \"employee_name\": \"الاسم\",
                \"day\": رقم اليوم أو null،
                \"month\": رقم الشهر أو null،
                \"year\": رقم السنة أو null،
                \"intent\": [\"salary\", \"net_salary\", \"absence\", \"shift\", \"work_hours\", \"lateness\", \"general_query\", \"salf\", \"disemp\", \"please\", \"plzot\", \"vacation\"]
            }
            النص الحالي: \"$message\"";

            if ($provider === 'gemini') {
                $extractResponse = Http::timeout(120)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$geminiApiKey}", [
                    'contents' => [
                        ['parts' => [['text' => $extractionPrompt]]],
                    ],
                ]);

                if (! $extractResponse->successful()) {
                    throw new \Exception('فشل الاتصال بخدمة الذكاء الاصطناعي. السبب: '.$extractResponse->body());
                }

                $extractData = $extractResponse->json();
                $rawJson = $extractData['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            } elseif ($provider === 'openrouter') {
                $extractResponse = Http::timeout(120)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $openrouterApiKey,
                        'HTTP-Referer'  => config('app.url'),
                    ])
                    ->post('https://openrouter.ai/api/v1/chat/completions', [
                        'model'    => 'openai/gpt-4o-mini',
                        'messages' => [
                            ['role' => 'system', 'content' => 'أنت خبير استخراج بيانات بصيغة JSON فقط.'],
                            ['role' => 'user',   'content' => $extractionPrompt],
                        ],
                    ]);

                if (! $extractResponse->successful()) {
                    throw new \Exception('فشل الاتصال بـ OpenRouter. السبب: '.$extractResponse->body());
                }

                $extractData = $extractResponse->json();
                $rawJson = $extractData['choices'][0]['message']['content'] ?? '{}';
            } else {
                $extractResponse = Http::timeout(120)->post("http://localhost:11434/api/chat", [
                    'model' => $ollamaModel,
                    'messages' => [
                        ['role' => 'system', 'content' => "أنت خبير استخراج بيانات بصيغة JSON فقط."],
                        ['role' => 'user', 'content' => $extractionPrompt]
                    ],
                    'stream' => false,
                ]);

                if (! $extractResponse->successful()) {
                    throw new \Exception('فشل الاتصال بخدمة Ollama المحلية.');
                }

                $extractData = $extractResponse->json();
                $rawJson = $extractData['message']['content'] ?? '{}';
            }

            // تنظيف النص
            $rawJson = str_replace(['```json', '```'], '', $rawJson);
            $parsed = json_decode(trim($rawJson), true);
            \Log::info('AI Parsed:', $parsed ?? []);

            $dbInfo = '';

            $hasStructuredIntent = false;
            if (isset($parsed['intent']) && is_array($parsed['intent'])) {
                foreach (['salary', 'net_salary', 'absence', 'shift', 'work_hours', 'lateness'] as $intent) {
                    if (in_array($intent, $parsed['intent'])) {
                        $hasStructuredIntent = true;
                        break;
                    }
                }
            }

            if (in_array('general_query', $parsed['intent'] ?? []) || empty($parsed['employee_name']) || !$hasStructuredIntent) {
                $dbInfo = $this->generateAndExecuteSQL($message, $provider, $geminiApiKey, $ollamaModel, $openrouterApiKey);
            } else {
                // الخطوة 2: البحث في قاعدة البيانات إذا كان هناك اسم موظف
                if (! empty($parsed['employee_name'])) {
                    $empName = trim($parsed['employee_name']);
                    
                    // تنظيف الاسم من حروف الجر الزائدة (مثل: لياسر -> ياسر)
                    $empName = preg_replace('/^(لـ|الـ|ل)/u', '', $empName);
                    $empName = trim($empName);

                    // البحث الذكي
                    $employee = employees::with('shift')->where('name', 'LIKE', "%{$empName}%")->first();
                    
                    // محاولة أخرى إذا فشل البحث بالاسم الكامل (البحث بأول كلمة)
                    if (!$employee) {
                        $firstName = explode(' ', $empName)[0];
                        $employee = employees::with('shift')->where('name', 'LIKE', "%{$firstName}%")->first();
                    }

                    if ($employee) {
                        $targetDay = $parsed['day'] ?? null;
                        $targetMonth = $parsed['month'] ?? Carbon::now()->month;
                        $targetYear = $parsed['year'] ?? Carbon::now()->year;
                        
                        $dateDesc = ($targetDay ? "يوم $targetDay " : "") . ($targetMonth ? "شهر $targetMonth " : "") . ($targetYear ? "سنة $targetYear" : "");
                        $dbInfo .= "تم العثور على الموظف: {$employee->name}. التاريخ المستهدف: $dateDesc.\n";

                        if (in_array('shift', $parsed['intent'] ?? [])) {
                            $dbInfo .= 'الوردية الحالية هي: '.($employee->shift->name ?? 'غير مححدد').".\n";
                        }

                        // تجهيز الاستعلام الأساسي لجدول counts
                        $countsQuery = count::where('employees_id', $employee->id);
                        if ($targetDay) $countsQuery->whereDay('date', $targetDay);
                        if ($targetMonth) $countsQuery->whereMonth('date', $targetMonth);
                        if ($targetYear) $countsQuery->whereYear('date', $targetYear);

                        if (in_array('absence', $parsed['intent'] ?? [])) {
                            $absentCount = (clone $countsQuery)->where('absent', 1)->count();
                            $dbInfo .= "عدد أيام الغياب في $dateDesc هو: $absentCount يوم.\n";
                        }

                        if (in_array('work_hours', $parsed['intent'] ?? [])) {
                            $totalSeconds = (clone $countsQuery)->sum('dayh');
                            $h = floor($totalSeconds / 3600);
                            $m = floor(($totalSeconds / 60) % 60);
                            $dbInfo .= "إجمالي ساعات العمل في $dateDesc هو: $h ساعة و $m دقيقة.\n";
                        }

                        if (in_array('lateness', $parsed['intent'] ?? [])) {
                            $lateSummary = (clone $countsQuery)->selectRaw('SUM(dis) as total_late_in, SUM(disout) as total_late_out, SUM(TIME_TO_SEC(early)) as total_late_seconds')->first();
                            $totalLateMoney = ($lateSummary->total_late_in ?? 0) + ($lateSummary->total_late_out ?? 0);
                            $totalLateSeconds = $lateSummary->total_late_seconds ?? 0;
                            
                            $h = floor($totalLateSeconds / 3600);
                            $m = floor(($totalLateSeconds / 60) % 60);

                            $dbInfo .= "إجمالي مبالغ التأخير (دخول + خروج) في $dateDesc هو: $totalLateMoney جنيه.\n";
                            $dbInfo .= "إجمالي وقت التأخير (ساعات التأخير) في $dateDesc هو: $h ساعة و $m دقيقة.\n";
                        }

                        \Log::info('Final DB Info:', ['info' => $dbInfo]);

                        if (in_array('salary', $parsed['intent'] ?? []) || in_array('net_salary', $parsed['intent'] ?? [])) {
                            $kabd = (float) $employee->kabd;
                            $dbInfo .= "الراتب الأساسي المسجل هو: {$kabd}.\n";

                            if (in_array('net_salary', $parsed['intent'] ?? [])) {
                                // جلب المجاميع من جدول counts
                                $countsSummary = count::where('employees_id', $employee->id)
                                    ->whereMonth('date', $targetMonth)
                                    ->whereYear('date', $targetYear)
                                    ->selectRaw('SUM(dis) as total_dis, SUM(disout) as total_disout, SUM(count_ot) as total_ot, SUM(friday) as total_friday, SUM(eid) as total_eid')
                                    ->first();

                                $totalDis = (float) ($countsSummary->total_dis ?? 0);
                                $totalDisout = (float) ($countsSummary->total_disout ?? 0);
                                $totalOt = (float) ($countsSummary->total_ot ?? 0);

                                $holidayDays = (int) ($countsSummary->total_friday ?? 0) + (int) ($countsSummary->total_eid ?? 0);
                                $holidayBonus = round($holidayDays * ($kabd / 30), 2);

                                // حساب الخصومات
                                $absentDays = count::where('employees_id', $employee->id)
                                    ->whereMonth('date', $targetMonth)
                                    ->whereYear('date', $targetYear)
                                    ->where('absent', 1)
                                    ->count();

                                $absenceDeduction = round(($kabd / 30) * $absentDays, 2);

                                $disempTotal = \App\Models\disemp::where('employees_id', $employee->id)
                                    ->whereMonth('date', $targetMonth)
                                    ->whereYear('date', $targetYear)
                                    ->sum('dis');

                                $salfTotal = \App\Models\salf::where('employees_id', $employee->id)
                                    ->whereMonth('date', $targetMonth)
                                    ->whereYear('date', $targetYear)
                                    ->sum('salf');

                                // قروض مستحقة في هذا الشهر
                                $targetDateStart = Carbon::create($targetYear, $targetMonth, 1)->toDateString();
                                $targetDateEnd = Carbon::create($targetYear, $targetMonth, 31)->toDateString();
                                $loansTotal = \App\Models\loan::where('employees_id', $employee->id)
                                    ->where('date_start', '<=', $targetDateEnd)
                                    ->where('date_end', '>=', $targetDateStart)
                                    ->sum('mony_now');

                                // الإضافات الجديدة بناءً على طلب المستخدم
                                $transportDeduction = \Illuminate\Support\Facades\DB::table('dis_transports')
                                    ->where('employees_id', $employee->id)
                                    ->whereMonth('created_at', $targetMonth)
                                    ->whereYear('created_at', $targetYear)
                                    ->sum('mony');

                                $taminTotal = \Illuminate\Support\Facades\DB::table('taminats')
                                    ->where('employees_id', $employee->id)
                                    ->whereMonth('created_at', $targetMonth)
                                    ->whereYear('created_at', $targetYear)
                                    ->sum('mony');

                                $purchasesTotal = \Illuminate\Support\Facades\DB::table('purchases')
                                    ->where('employees_id', $employee->id)
                                    ->whereMonth('date', $targetMonth)
                                    ->whereYear('date', $targetYear)
                                    ->sum('mony');

                                $netSalary = $kabd + $holidayBonus + $totalOt - $absenceDeduction - $disempTotal - $salfTotal - $totalDis - $totalDisout - $loansTotal - $transportDeduction - $taminTotal - $purchasesTotal;

                                $dbInfo .= "تفاصيل الراتب والخصومات في شهر $targetMonth:\n";
                                $dbInfo .= "- الراتب الأساسي: $kabd\n";
                                if ($holidayBonus > 0) $dbInfo .= "+ حضور إجازات رسمية: $holidayBonus\n";
                                if ($totalOt > 0) $dbInfo .= "+ أجر إضافي (أوفر تايم): $totalOt\n";
                                
                                $dbInfo .= "الخصومات التفصيلية:\n";
                                $dbInfo .= "- خصم أيام الغياب ($absentDays أيام): $absenceDeduction\n";
                                if ($totalDis > 0) $dbInfo .= "- خصم تأخير دخول: $totalDis\n";
                                if ($totalDisout > 0) $dbInfo .= "- خصم خروج مبكر: $totalDisout\n";
                                if ($disempTotal > 0) $dbInfo .= "- خصم خارج البصمة (disemp): $disempTotal\n";
                                if ($salfTotal > 0) $dbInfo .= "- سلف (salves): $salfTotal\n";
                                if ($loansTotal > 0) $dbInfo .= "- أقساط قروض: $loansTotal\n";
                                if ($transportDeduction > 0) $dbInfo .= "- خصم مواصلات: $transportDeduction\n";
                                if ($taminTotal > 0) $dbInfo .= "- تأمينات: $taminTotal\n";
                                if ($purchasesTotal > 0) $dbInfo .= "- مشتريات: $purchasesTotal\n";

                                $dbInfo .= "إجمالي الخصومات لهذا الشهر: " . ($absenceDeduction + $totalDis + $totalDisout + $disempTotal + $salfTotal + $loansTotal + $transportDeduction + $taminTotal + $purchasesTotal) . "\n";
                                $dbInfo .= "صافي الراتب النهائي: $netSalary.\n";
                            }
                        }
                    } else {
                        // إذا لم نجد الموظف بالاسم، نجرب البحث العام كبديل
                        $dbInfo = $this->generateAndExecuteSQL($message, $provider, $geminiApiKey, $ollamaModel, $openrouterApiKey);
                    }
                }
            }

            // الخطوة 3: إرسال المعلومات للذكاء الاصطناعي لصياغة رد طبيعي
            $finalPrompt = "أنت مساعد HR ذكي. أجب على سؤال المستخدم باستخدام المعلومات التالية فقط.
            
            المعلومات المتاحة:
            $dbInfo
            
            سؤال المستخدم: \"$message\"
            
            تعليمات:
            - صغ الإجابة بأسلوب مهذب وواضح.
            - إذا كانت المعلومات المتاحة تخبرك بعدم وجود بيانات، أخبر المستخدم بذلك.
            - لا تخترع معلومات من عندك.";

            if ($provider === 'gemini') {
                $finalResponse = Http::timeout(120)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$geminiApiKey}", [
                    'contents' => [
                        ['parts' => [['text' => $finalPrompt]]],
                    ],
                ]);

                if (! $finalResponse->successful()) {
                    throw new \Exception('فشل الاتصال بخدمة الذكاء الاصطناعي (الصياغة). السبب: '.$finalResponse->body());
                }

                $finalData = $finalResponse->json();
                $answer = $finalData['candidates'][0]['content']['parts'][0]['text'] ?? 'عذراً، لم أتمكن من صياغة الإجابة.';
            } elseif ($provider === 'openrouter') {
                $finalResponse = Http::timeout(120)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $openrouterApiKey,
                        'HTTP-Referer'  => config('app.url'),
                    ])
                    ->post('https://openrouter.ai/api/v1/chat/completions', [
                        'model'    => 'openai/gpt-4o-mini',
                        'messages' => [
                            ['role' => 'system', 'content' => 'أنت مساعد HR ذكي. أجب على سؤال المستخدم بناءً على البيانات المقدمة فقط.'],
                            ['role' => 'user',   'content' => "البيانات المتاحة:\n$dbInfo\n\nالسؤال: $message"],
                        ],
                    ]);

                if (! $finalResponse->successful()) {
                    throw new \Exception('فشل الاتصال بـ OpenRouter (الصياغة). السبب: '.$finalResponse->body());
                }

                $finalData = $finalResponse->json();
                $answer = $finalData['choices'][0]['message']['content'] ?? 'عذراً، لم أتمكن من صياغة الإجابة.';
            } else {
                $finalResponse = Http::timeout(120)->post("http://localhost:11434/api/chat", [
                    'model' => $ollamaModel,
                    'messages' => [
                        ['role' => 'system', 'content' => "أنت مساعد HR ذكي لشركة Pixel. مهمتك هي الإجابة على سؤال المستخدم بناءً على البيانات المقدمة فقط. لا تعتذر ولا تدعي عدم المعرفة إذا كانت البيانات موجودة."],
                        ['role' => 'user', 'content' => "البيانات المتاحة:\n$dbInfo\n\nالسؤال: $message"]
                    ],
                    'stream' => false
                ]);

                if (! $finalResponse->successful()) {
                    throw new \Exception('فشل الاتصال بخدمة Ollama المحلية لصياغة الإجابة.');
                }

                $finalData = $finalResponse->json();
                $answer = $finalData['message']['content'] ?? 'عذراً، لم أتمكن من صياغة الإجابة.';
            }

            return [
                'response' => $answer
            ];

        } catch (\Exception $e) {
            Log::error('Chat AI Error: '.$e->getMessage());

            return [
                'error' => 'حدث خطأ في النظام. '.$e->getMessage(),
                'status_code' => 500
            ];
        }
    }

    public function sendMessage(Request $request)
    {
        $message = $request->input('message');
        $result = $this->processMessageText($message);

        if (isset($result['error'])) {
            return response()->json([
                'success' => false,
                'error'   => $result['error'],
            ], $result['status_code'] ?? 400);
        }

        return response()->json([
            'success' => true,
            'response' => $result['response'],
        ]);
    }

    public function handleTelegramWebhook(Request $request)
    {
        $update = $request->all();
        
        Log::info('Telegram Webhook Received:', $update);

        if (!isset($update['message']['text'])) {
            return response()->json(['status' => 'ignored']);
        }

        $chatId = $update['message']['chat']['id'];
        $text = $update['message']['text'];

        $result = $this->processMessageText($text);
        
        $reply = isset($result['response']) ? $result['response'] : ($result['error'] ?? 'عذراً، حدث خطأ ما.');

        $settings = $this->loadSettings();
        $token = $settings['telegram_bot_token'] ?? '';

        if (!empty($token)) {
            try {
                Http::timeout(10)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $reply
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send Telegram message: ' . $e->getMessage());
            }
        } else {
            Log::error('Telegram Bot Token is not configured.');
        }

        return response()->json(['status' => 'success']);
    }
}
