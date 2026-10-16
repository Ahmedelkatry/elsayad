@extends('layouts_new.app')
@section('title')
    تعديل مستخدم | نظام إدارة الموظفين
@endsection

@section('css')
    <link href="{{URL::asset('assets/plugins/jquery-nice-select/css/nice-select.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <a href="{{ route('users.index') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">المستخدمين</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">تعديل مستخدم</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">تعديل بيانات المستخدم</h1>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('users.index') }}" class="flex items-center gap-2 px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold hover:bg-gray-300 dark:hover:bg-white/10 transition-all active:scale-95">
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                رجوع للقائمة
            </a>
        </div>
    </div>

    @if (count($errors) > 0)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">خطأ!</strong>
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection

@section('content')
    <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-8 shadow-sm">
        {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- User Basic Info -->
                <div class="space-y-6">
                    <h4 class="text-sm font-bold text-sky-600 dark:text-sky-500 uppercase tracking-wider border-b border-gray-100 dark:border-white/5 pb-3 flex items-center gap-2">
                        <i data-lucide="info" class="w-4 h-4"></i> البيانات الأساسية
                    </h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">اسم المستخدم <span class="text-red-500">*</span></label>
                            {!! Form::text('name', null, array('class' => 'w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors','required')) !!}
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">البريد الإلكتروني <span class="text-red-500">*</span></label>
                            {!! Form::text('email', null, array('class' => 'w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors font-mono','required')) !!}
                        </div>
                    </div>
                </div>

                <!-- Security Info -->
                <div class="space-y-6">
                    <h4 class="text-sm font-bold text-sky-600 dark:text-sky-500 uppercase tracking-wider border-b border-gray-100 dark:border-white/5 pb-3 flex items-center gap-2">
                        <i data-lucide="shield" class="w-4 h-4"></i> الأمان والصلاحيات
                    </h4>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">كلمة المرور (اتركها فارغة إذا لم تكن تريد تغييرها)</label>
                                {!! Form::password('password', array('class' => 'w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors font-mono')) !!}
                            </div>
                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">تأكيد كلمة المرور</label>
                                {!! Form::password('confirm-password', array('class' => 'w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors font-mono')) !!}
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">حالة المستخدم</label>
                                <select name="Status" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors appearance-none">
                                    <option value="{{ $user->Status }}">{{ $user->Status }}</option>
                                    <option value="مفعل">مفعل</option>
                                    <option value="غير مفعل">غير مفعل</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">نوع المستخدم (الصلاحيات)</label>
                                {!! Form::select('roles[]', $roles, $userRole, ['class' => 'w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors', 'multiple']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-6 border-t border-gray-100 dark:border-white/5">
                <button type="submit" class="px-10 py-3 bg-green-600 text-white hover:bg-green-500 rounded-xl text-sm font-bold shadow-lg shadow-green-600/10 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    تحديث البيانات
                </button>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@section('js')
    <script src="{{URL::asset('assets/plugins/jquery-nice-select/js/jquery.nice-select.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/jquery-nice-select/js/nice-select.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/parsleyjs/parsley.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/form-validation.js')}}"></script>
@endsection