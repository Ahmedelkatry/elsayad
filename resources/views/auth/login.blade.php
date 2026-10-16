@extends('layouts_new.guest')

@section('title', 'تسجيل الدخول')

@section('content')
    <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-sky-500/10 border border-sky-500/20 mb-6 group transition-all duration-500 hover:scale-110">
            <i data-lucide="shield-check" class="w-10 h-10 text-sky-500 group-hover:rotate-12 transition-transform"></i>
        </div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight mb-1">Pixel Security</h1>
        <p class="text-sky-500 font-medium text-sm mb-4">نظام الحماية والتحكم الذكي</p>
        <p class="text-gray-500 text-xs">أدخل بياناتك للوصول إلى لوحة التحكم</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 p-4 rounded-2xl bg-green-500/10 border border-green-500/20 text-green-500 text-sm flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('status') }}
        </div>
    @endif

    <div class="glass-card rounded-[2.5rem] p-8 md:p-10 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-sky-500 to-transparent opacity-50"></div>
        
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email or Phone Number -->
            <div class="space-y-2">
                <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-[0.2em] px-1">البريد الإلكتروني أو رقم الهاتف</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-500 group-focus-within:text-sky-500 transition-colors">
                        <i data-lucide="phone" class="w-5 h-5"></i>
                    </div>
                    <input id="email" 
                           class="block w-full pr-12 pl-4 py-4 bg-white/5 border border-white/5 rounded-2xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500/50 transition-all" 
                           type="text" 
                           name="email" 
                           placeholder="أدخل البريد الإلكتروني أو رقم الهاتف"
                           :value="old('email')" 
                           required 
                           autofocus 
                           autocomplete="username" />
                </div>
                @if ($errors->has('email'))
                    <p class="text-red-500 text-xs mt-1 px-1 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3 h-3"></i>
                        {{ $errors->first('email') }}
                    </p>
                @endif
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <div class="flex items-center justify-between px-1">
                    <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-[0.2em]">كلمة المرور</label>
                    @if (Route::has('password.request'))
                        <a class="text-xs font-semibold text-sky-500/80 hover:text-sky-500 transition-colors" href="{{ route('password.request') }}">
                            نسيت كلمة المرور؟
                        </a>
                    @endif
                </div>
                <div class="relative group">
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-500 group-focus-within:text-sky-500 transition-colors">
                        <i data-lucide="lock" class="w-5 h-5"></i>
                    </div>
                    <input id="password" 
                           class="block w-full pr-12 pl-4 py-4 bg-white/5 border border-white/5 rounded-2xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500/50 transition-all"
                           type="password"
                           name="password"
                           placeholder="••••••••"
                           required 
                           autocomplete="current-password" />
                </div>
                @if ($errors->has('password'))
                    <p class="text-red-500 text-xs mt-1 px-1 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3 h-3"></i>
                        {{ $errors->first('password') }}
                    </p>
                @endif
            </div>

            <!-- Remember Me -->
            <div class="flex items-center gap-3 px-1">
                <div class="relative flex items-center">
                    <input id="remember_me" 
                           type="checkbox" 
                           class="peer h-5 w-5 cursor-pointer appearance-none rounded-lg border border-white/10 bg-white/5 transition-all checked:bg-sky-500 checked:border-sky-500 focus:outline-none" 
                           name="remember">
                    <i data-lucide="check" class="absolute left-1 top-1 h-3 w-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></i>
                </div>
                <label for="remember_me" class="text-sm text-gray-400 cursor-pointer select-none">تذكرني على هذا الجهاز</label>
            </div>

            <button type="submit" class="w-full py-4 bg-sky-500 hover:bg-sky-400 text-white font-bold rounded-2xl shadow-[0_0_20px_rgba(14,165,233,0.35)] transition-all active:scale-[0.98] flex items-center justify-center gap-2 group">
                <span>تسجيل الدخول</span>
                <i data-lucide="arrow-left" class="w-5 h-5 group-hover:-translate-x-1 transition-transform"></i>
            </button>
        </form>
    </div>

    <p class="text-center mt-8 text-gray-500 text-xs">
        &copy; {{ date('Y') }} Pixel Security System. جميع الحقوق محفوظة.
    </p>
@endsection
