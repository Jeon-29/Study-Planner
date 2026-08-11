<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Planner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-15px) scale(1.05); }
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.1); }
        }

        .animate-float-slow { animation: float 8s ease-in-out infinite; }
        .animate-float-delayed { animation: float 10s ease-in-out infinite 2s; }
        .animate-glow { animation: pulseGlow 12s ease-in-out infinite; }
    </style>
</head>

<body class="bg-[#FDFBF7] min-h-screen flex items-center justify-center p-4 overflow-hidden relative">

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-[9999] transition-all duration-500 ease-out opacity-0 translate-y-[-20px] pointer-events-none">
        <div id="toast-content" class="flex items-center gap-3 px-5 py-3.5 rounded-2xl backdrop-blur-2xl border shadow-xl text-xs font-bold">
            <span id="toast-icon" class="material-icons-round text-lg"></span>
            <span id="toast-message"></span>
        </div>
    </div>

    <!-- Background Layer Architecture -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -right-[15%] w-[125%] sm:w-[450px] h-[360px] rounded-b-[11rem] rounded-l-[13rem] bg-[#FBCFE8] opacity-95 transform rotate-12 transition-all drop-shadow-[0_10px_30px_rgba(219,39,119,0.15)] border-b border-l border-white/40"></div>
        <div class="absolute -bottom-[12%] -left-[18%] w-[135%] sm:w-[480px] h-[400px] rounded-t-[13rem] rounded-r-[13rem] bg-[#FFE4E6] opacity-95 transform -rotate-6 transition-all drop-shadow-[-10px_-10px_30px_rgba(244,114,182,0.12)] border-t border-r border-white/50"></div>
        <div class="absolute top-[25%] left-[5%] w-48 h-48 rounded-full bg-white/40 backdrop-blur-sm border border-white/50 shadow-lg animate-float-slow hidden sm:block"></div>
        <div class="absolute bottom-[35%] right-[5%] w-56 h-56 rounded-full bg-gradient-to-br from-white/30 to-transparent blur-sm border border-white/30 shadow-md animate-float-delayed hidden sm:block"></div>
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808008_1px,transparent_1px),linear-gradient(to_bottom,#80808008_1px,transparent_1px)] bg-[size:24px_24px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)]"></div>
    </div>

    <!-- SCREEN 1: SPLASH VIEW -->
    <div id="splash-screen" class="w-full max-w-sm flex flex-col items-center justify-between min-h-[82vh] transition-all duration-700 ease-out transform scale-100 opacity-100 z-10">
        <div class="w-full h-8"></div>

        <div class="w-full flex justify-center my-6 relative">
            <div class="absolute inset-0 max-w-xs mx-auto bg-gradient-to-r from-pink-200/30 to-amber-200/20 rounded-full blur-2xl opacity-60"></div>
            <!-- REVERTED: Logo reinstated with original Emojis -->
            <div id="center-graphic" class="relative w-52 h-52 flex items-center justify-center bg-white/50 backdrop-blur-md rounded-full border border-white/70 shadow-xl transition-all duration-500 ease-out">
                <span class="text-7xl select-none filter drop-shadow-md">🌸</span>
                <div class="absolute -right-3 top-10 text-2xl animate-bounce" style="animation-duration: 3s;">🩺</div>
                <div class="absolute -left-3 bottom-10 text-2xl animate-float-slow">🦷</div>
            </div>
        </div>

        <div class="text-center px-4">
            <h1 class="text-4xl font-extrabold text-[#1C1917] tracking-tight">
                Study<span class="text-[#DB2777] font-semibold">Planner</span>
            </h1>
            <p class="text-sm font-medium text-[#78716C] mt-2.5 tracking-widest uppercase">Plan. Focus. Achieve.</p>
        </div>

        <div class="w-full px-4 mt-12 space-y-5">
            <button onclick="openForm('login-form')" class="w-full py-4 rounded-2xl bg-[#1C1917] hover:bg-[#37312D] text-[#FFF5F5] font-semibold text-sm shadow-xl active:scale-[0.98] transition-all duration-200 border border-stone-800 cursor-pointer">
                Log In
            </button>
            <div class="text-center">
                <p class="text-xs text-[#78716C] tracking-wide">
                    New to the platform?
                    <button onclick="openForm('register-form')" class="text-[#DB2777] font-bold hover:underline ml-1 cursor-pointer">Create an account</button>
                </p>
            </div>
        </div>
    </div>

    <!-- SCREEN 2: LOG IN SLIDE-UP FORM CONTAINER -->
    <div id="login-form" class="absolute bottom-0 left-0 right-0 max-w-md mx-auto w-full bg-white/75 backdrop-blur-2xl p-8 rounded-t-[2.5rem] shadow-2xl border-t border-white/80 transition-all duration-500 ease-out transform translate-y-full opacity-0 z-20">
        <div class="w-12 h-1.5 bg-[#E7E5E4] rounded-full mx-auto mb-6 cursor-pointer hover:bg-stone-300 transition" onclick="closeForms()"></div>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-[#1C1917] flex items-center gap-2">
                Welcome Back 
                <span class="material-icons-round text-[#DB2777] text-2xl">waving_hand</span>
            </h2>
            <p class="text-xs text-[#78716C] mt-1">Please enter your credentials to access your planner.</p>
        </div>

        @if ($errors->has('email') && !old('name') && !old('token'))
            <div class="p-3 mb-4 text-xs font-semibold text-red-600 bg-red-50/80 border border-red-100 rounded-xl backdrop-blur-sm">
                {{ $errors->first('email') }}
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-[#1C1917] uppercase tracking-wider mb-1.5">Email Address</label>
                <input type="email" name="email" class="w-full px-4 py-3 rounded-xl bg-white/90 border border-pink-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#F472B6]/40 text-[#1C1917] shadow-sm transition" placeholder="example@email.com" required>
            </div>
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-[10px] font-bold text-[#1C1917] uppercase tracking-wider">Password</label>
                    <button type="button" onclick="switchForm('forgot-password-form')" class="text-[11px] text-[#DB2777] font-bold hover:underline cursor-pointer">Forgot password?</button>
                </div>
                <input type="password" name="password" class="w-full px-4 py-3 rounded-xl bg-white/90 border border-pink-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#F472B6]/40 text-[#1C1917] shadow-sm transition" placeholder="••••••••" required>
            </div>
            <button type="submit" class="w-full py-3.5 mt-4 rounded-xl bg-[#1C1917] text-[#FFF5F5] font-semibold text-sm shadow-lg hover:bg-[#37312D] transition-all duration-200 cursor-pointer">Sign In</button>
        </form>

        <div class="text-center mt-6">
            <button onclick="switchForm('register-form')" class="text-xs text-[#78716C] tracking-wide cursor-pointer">Don't have an account? <span class="text-[#DB2777] font-bold hover:underline">Sign up</span></button>
        </div>
    </div>

    <!-- SCREEN 3: REGISTER SLIDE-UP FORM CONTAINER -->
    <div id="register-form" class="absolute bottom-0 left-0 right-0 max-w-md mx-auto w-full bg-white/75 backdrop-blur-2xl p-8 rounded-t-[2.5rem] shadow-2xl border-t border-white/80 transition-all duration-500 ease-out transform translate-y-full opacity-0 z-20">
        <div class="w-12 h-1.5 bg-[#E7E5E4] rounded-full mx-auto mb-6 cursor-pointer hover:bg-stone-300 transition" onclick="closeForms()"></div>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-[#1C1917] flex items-center gap-2">
                Create Account 
                <span class="material-icons-round text-[#DB2777] text-2xl">auto_awesome</span>
            </h2>
            <p class="text-xs text-[#78716C] mt-1">Join the community and organize your requirements.</p>
        </div>

        @if ($errors->any() && old('name'))
            <div class="p-3 mb-4 text-xs font-semibold text-red-600 bg-red-50/80 border border-red-100 rounded-xl backdrop-blur-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-[#1C1917] uppercase tracking-wider mb-1.5">Full Name</label>
                <input type="text" name="name" class="w-full px-4 py-3 rounded-xl bg-white/90 border border-pink-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#F472B6]/40 text-[#1C1917] shadow-sm transition" placeholder="Future Dentist" required>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-[#1C1917] uppercase tracking-wider mb-1.5">Email Address</label>
                <input type="email" name="email" class="w-full px-4 py-3 rounded-xl bg-white/90 border border-pink-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#F472B6]/40 text-[#1C1917] shadow-sm transition" placeholder="example@email.com" required>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-[#1C1917] uppercase tracking-wider mb-1.5">Password</label>
                <input type="password" name="password" class="w-full px-4 py-3 rounded-xl bg-white/90 border border-pink-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#F472B6]/40 text-[#1C1917] shadow-sm transition" placeholder="Min. 8 characters" required>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-[#1C1917] uppercase tracking-wider mb-1.5">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-3 rounded-xl bg-white/90 border border-pink-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#F472B6]/40 text-[#1C1917] shadow-sm transition" placeholder="••••••••" required>
            </div>
            <button type="submit" class="w-full py-3.5 mt-4 rounded-xl bg-[#1C1917] text-[#FFF5F5] font-semibold text-sm shadow-lg hover:bg-[#37312D] transition-all duration-200 cursor-pointer">Create Account</button>
        </form>

        <div class="text-center mt-6">
            <button onclick="switchForm('login-form')" class="text-xs text-[#78716C] tracking-wide cursor-pointer">Already have an account? <span class="text-[#DB2777] font-bold hover:underline">Log in</span></button>
        </div>
    </div>

    <!-- SCREEN 4: FORGOT PASSWORD SLIDE-UP FORM CONTAINER -->
    <div id="forgot-password-form" class="absolute bottom-0 left-0 right-0 max-w-md mx-auto w-full bg-white/75 backdrop-blur-2xl p-8 rounded-t-[2.5rem] shadow-2xl border-t border-white/80 transition-all duration-500 ease-out transform translate-y-full opacity-0 z-20">
        <div class="w-12 h-1.5 bg-[#E7E5E4] rounded-full mx-auto mb-6 cursor-pointer hover:bg-stone-300 transition" onclick="closeForms()"></div>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-[#1C1917] flex items-center gap-2">
                Forgot Password? 
                <span class="material-icons-round text-[#DB2777] text-2xl">lock_reset</span>
            </h2>
            <p class="text-xs text-[#78716C] mt-1">Enter your email address and we'll send you a password reset link.</p>
        </div>

        @if (session('status'))
            <div class="p-3 mb-4 text-xs font-semibold text-emerald-600 bg-emerald-50/80 border border-emerald-100 rounded-xl backdrop-blur-sm">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->has('email') && session('status') == null && !old('name') && !old('token'))
            <div class="p-3 mb-4 text-xs font-semibold text-red-600 bg-red-50/80 border border-red-100 rounded-xl backdrop-blur-sm">
                {{ $errors->first('email') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-[#1C1917] uppercase tracking-wider mb-1.5">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 rounded-xl bg-white/90 border border-pink-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#F472B6]/40 text-[#1C1917] shadow-sm transition" placeholder="example@email.com" required>
            </div>
            <button type="submit" class="w-full py-3.5 mt-4 rounded-xl bg-[#1C1917] text-[#FFF5F5] font-semibold text-sm shadow-lg hover:bg-[#37312D] transition-all duration-200 cursor-pointer">Send Reset Link</button>
        </form>

        <div class="text-center mt-6">
            <button onclick="switchForm('login-form')" class="text-xs text-[#78716C] tracking-wide cursor-pointer">Remember your password? <span class="text-[#DB2777] font-bold hover:underline">Log in</span></button>
        </div>
    </div>

    <!-- SCREEN 5: SET NEW PASSWORD SLIDE-UP FORM CONTAINER -->
    <div id="reset-password-form" class="absolute bottom-0 left-0 right-0 max-w-md mx-auto w-full bg-white/75 backdrop-blur-2xl p-8 rounded-t-[2.5rem] shadow-2xl border-t border-white/80 transition-all duration-500 ease-out transform translate-y-full opacity-0 z-20">
        <div class="w-12 h-1.5 bg-[#E7E5E4] rounded-full mx-auto mb-6 cursor-pointer hover:bg-stone-300 transition" onclick="closeForms()"></div>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-[#1C1917] flex items-center gap-2">
                Set New Password 
                <span class="material-icons-round text-[#DB2777] text-2xl">key</span>
            </h2>
            <p class="text-xs text-[#78716C] mt-1">Please enter your email and set your new password.</p>
        </div>

        @if ($errors->any() && (old('token') || isset($token)))
            <div class="p-3 mb-4 text-xs font-semibold text-red-600 bg-red-50/80 border border-red-100 rounded-xl backdrop-blur-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token ?? old('token') }}">

            <div>
                <label class="block text-[10px] font-bold text-[#1C1917] uppercase tracking-wider mb-1.5">Email Address</label>
                <input type="email" name="email" value="{{ $email ?? old('email') }}" class="w-full px-4 py-3 rounded-xl bg-white/90 border border-pink-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#F472B6]/40 text-[#1C1917] shadow-sm transition" placeholder="example@email.com" required>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-[#1C1917] uppercase tracking-wider mb-1.5">New Password</label>
                <input type="password" name="password" class="w-full px-4 py-3 rounded-xl bg-white/90 border border-pink-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#F472B6]/40 text-[#1C1917] shadow-sm transition" placeholder="Min. 8 characters" required>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-[#1C1917] uppercase tracking-wider mb-1.5">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-3 rounded-xl bg-white/90 border border-pink-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#F472B6]/40 text-[#1C1917] shadow-sm transition" placeholder="••••••••" required>
            </div>
            <button type="submit" class="w-full py-3.5 mt-4 rounded-xl bg-[#1C1917] text-[#FFF5F5] font-semibold text-sm shadow-lg hover:bg-[#37312D] transition-all duration-200 cursor-pointer">Update Password</button>
        </form>

        <div class="text-center mt-6">
            <button onclick="switchForm('login-form')" class="text-xs text-[#78716C] tracking-wide cursor-pointer font-semibold">Back to <span class="text-[#DB2777] font-bold hover:underline">Log in</span></button>
        </div>
    </div>

    <!-- INTERACTION LOGIC -->
    <script>
        const splash = document.getElementById('splash-screen');

        function openForm(formId) {
            splash.classList.remove('scale-100', 'opacity-100');
            splash.classList.add('scale-95', 'opacity-0', 'pointer-events-none');

            const graphic = document.getElementById('center-graphic');
            if (graphic) {
                graphic.classList.add('-translate-y-8', 'opacity-0', 'scale-90');
            }

            const targetForm = document.getElementById(formId);
            if (targetForm) {
                targetForm.classList.remove('translate-y-full', 'opacity-0');
                targetForm.classList.add('translate-y-0', 'opacity-100');
            }
        }

        function closeForms() {
            ['login-form', 'register-form', 'forgot-password-form', 'reset-password-form'].forEach(id => {
                const form = document.getElementById(id);
                if (form) {
                    form.classList.remove('translate-y-0', 'opacity-100');
                    form.classList.add('translate-y-full', 'opacity-0');
                }
            });

            splash.classList.remove('scale-95', 'opacity-0', 'pointer-events-none');
            splash.classList.add('scale-100', 'opacity-100');

            const graphic = document.getElementById('center-graphic');
            if (graphic) {
                graphic.classList.remove('-translate-y-8', 'opacity-0', 'scale-90');
            }
        }

        function switchForm(targetFormId) {
            ['login-form', 'register-form', 'forgot-password-form', 'reset-password-form'].forEach(id => {
                const form = document.getElementById(id);
                if (form) {
                    form.classList.remove('translate-y-0', 'opacity-100');
                    form.classList.add('translate-y-full', 'opacity-0');
                }
            });

            setTimeout(() => {
                const targetForm = document.getElementById(targetFormId);
                if (targetForm) {
                    targetForm.classList.remove('translate-y-full', 'opacity-0');
                    targetForm.classList.add('translate-y-0', 'opacity-100');
                }
            }, 200);
        }

        window.addEventListener('DOMContentLoaded', () => { 
            @if (isset($token) || session('open_modal') === 'reset-password-form' || old('token'))
                openForm('reset-password-form');
                @if ($errors->any())
                    showToast("{{ $errors->first() }}", 'error');
                @endif
            @elseif (session('status')) 
                openForm('forgot-password-form'); 
                showToast("{{ session('status') }}", 'success'); 
            @elseif (session('open_modal') === 'forgot-password-form') 
                openForm('forgot-password-form'); 
                @if ($errors->has('email')) 
                    showToast("{{ $errors->first('email') }}", 'error'); 
                @endif
            @elseif ($errors->has('email') && !old('name')) 
                openForm('login-form'); 
            @elseif ($errors->any() && old('name')) 
                openForm('register-form'); 
            @endif
        });

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const content = document.getElementById('toast-content');
            const icon = document.getElementById('toast-icon');
            const msgEl = document.getElementById('toast-message');

            msgEl.innerText = message;

            if (type === 'success') {
                content.className = "flex items-center gap-3 px-5 py-3.5 rounded-2xl bg-white/90 backdrop-blur-2xl border border-emerald-200 shadow-[0_12px_40px_rgba(0,0,0,0.1)] text-emerald-800 text-xs font-bold";
                icon.innerText = "check_circle";
                icon.className = "material-icons-round text-lg text-emerald-600";
            } else {
                content.className = "flex items-center gap-3 px-5 py-3.5 rounded-2xl bg-white/90 backdrop-blur-2xl border border-rose-200 shadow-[0_12px_40px_rgba(0,0,0,0.1)] text-rose-800 text-xs font-bold";
                icon.innerText = "error";
                icon.className = "material-icons-round text-lg text-rose-600";
            }

            container.classList.remove('opacity-0', 'translate-y-[-20px]', 'pointer-events-none');
            container.classList.add('opacity-100', 'translate-y-0');

            setTimeout(() => {
                container.classList.remove('opacity-100', 'translate-y-0');
                container.classList.add('opacity-0', 'translate-y-[-20px]', 'pointer-events-none');
            }, 4500);
        }
    </script>
</body>

</html>