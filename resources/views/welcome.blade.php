<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Planner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Subtle modern floating movement animation */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) scale(1);
            }

            50% {
                transform: translateY(-15px) scale(1.05);
            }
        }

        @keyframes pulseGlow {

            0%,
            100% {
                opacity: 0.5;
                transform: scale(1);
            }

            50% {
                opacity: 0.8;
                transform: scale(1.1);
            }
        }

        .animate-float-slow {
            animation: float 8s ease-in-out infinite;
        }

        .animate-float-delayed {
            animation: float 10s ease-in-out infinite 2s;
        }

        .animate-glow {
            animation: pulseGlow 12s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-[#FDFBF7] min-h-screen flex items-center justify-center p-4 overflow-hidden relative">

    <!-- ========================================================================= -->
    <!-- PREMIUM HIGH-STANDARD BACKGROUND LAYER ARCHITECTURE                         -->
    <!-- ========================================================================= -->

    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <!-- 1. Top Right Large Organic Fluid Blob (High Definition Pop) -->
        <div
            class="absolute -top-[10%] -right-[15%] w-[125%] sm:w-[450px] h-[360px] rounded-b-[11rem] rounded-l-[13rem] bg-[#FBCFE8] opacity-95 transform rotate-12 transition-all drop-shadow-[0_10px_30px_rgba(219,39,119,0.15)] border-b border-l border-white/40">
        </div>

        <!-- 2. Bottom Left Large Organic Fluid Blob (High Definition Pop) -->
        <div
            class="absolute -bottom-[12%] -left-[18%] w-[135%] sm:w-[480px] h-[400px] rounded-t-[13rem] rounded-r-[13rem] bg-[#FFE4E6] opacity-95 transform -rotate-6 transition-all drop-shadow-[-10px_-10px_30px_rgba(244,114,182,0.12)] border-t border-r border-white/50">
        </div>

        <!-- 3. Elegant Floating Glassmorphic Ambient Bubble (Top Left Accent) -->
        <div
            class="absolute top-[25%] left-[5%] w-48 h-48 rounded-full bg-white/40 backdrop-blur-sm border border-white/50 shadow-lg animate-float-slow hidden sm:block">
        </div>

        <!-- 4. Elegant Floating Glassmorphic Ambient Bubble (Middle Right Accent) -->
        <div
            class="absolute bottom-[35%] right-[5%] w-56 h-56 rounded-full bg-gradient-to-br from-white/30 to-transparent blur-sm border border-white/30 shadow-md animate-float-delayed hidden sm:block">
        </div>

        <!-- 5. Sophisticated Minimal Grid Pattern Overlay for High Texture -->
        <div
            class="absolute inset-0 bg-[linear-gradient(to_right,#80808008_1px,transparent_1px),linear-gradient(to_bottom,#80808008_1px,transparent_1px)] bg-[size:24px_24px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)]">
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- SCREEN 1: SPLASH VIEW                                                     -->
    <!-- ========================================================================= -->
    <div id="splash-screen"
        class="w-full max-w-sm flex flex-col items-center justify-between min-h-[82vh] transition-all duration-700 ease-out transform scale-100 opacity-100 z-10">

        <div class="w-full h-8"></div>

        <!-- Center Fluid Graphics Wrapper -->
        <div class="w-full flex justify-center my-6 relative">
            <div
                class="absolute inset-0 max-w-xs mx-auto bg-gradient-to-r from-pink-200/30 to-amber-200/20 rounded-full blur-2xl opacity-60">
            </div>

            <!-- Added transform utilities for a smoother exit animation -->
            <div id="center-graphic"
                class="relative w-52 h-52 flex items-center justify-center bg-white/50 backdrop-blur-md rounded-full border border-white/70 shadow-xl transition-all duration-500 ease-out">
                <span class="text-8xl select-none filter drop-shadow-md">🌸</span>
                <div class="absolute -right-3 top-10 text-3xl animate-bounce" style="animation-duration: 3s;">🩺</div>
                <div class="absolute -left-3 bottom-10 text-3xl animate-float-slow">🦷</div>
            </div>
        </div>

        <!-- Typography Branding Block -->
        <div class="text-center px-4">
            <h1 class="text-4xl font-extrabold text-[#1C1917] tracking-tight">
                Study<span class="text-[#DB2777] font-semibold">Planner</span>
            </h1>
            <p class="text-sm font-medium text-[#78716C] mt-2.5 tracking-widest uppercase">Plan. Focus. Achieve.</p>
        </div>

        <!-- Bottom Action Menu Elements -->
        <div class="w-full px-4 mt-12 space-y-5">
            <button onclick="openForm('login-form')"
                class="w-full py-4 rounded-2xl bg-[#1C1917] hover:bg-[#37312D] text-[#FFF5F5] font-semibold text-sm shadow-xl active:scale-[0.98] transition-all duration-200 border border-stone-800">
                Log In
            </button>

            <div class="text-center">
                <p class="text-xs text-[#78716C] tracking-wide">
                    New to the platform?
                    <button onclick="openForm('register-form')"
                        class="text-[#DB2777] font-bold hover:underline ml-1">Create an account</button>
                </p>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- SCREEN 2: LOG IN SLIDE-UP FORM CONTAINER                                  -->
    <!-- ========================================================================= -->
    <div id="login-form"
        class="absolute bottom-0 left-0 right-0 max-w-md mx-auto w-full bg-white/75 backdrop-blur-2xl p-8 rounded-t-[2.5rem] shadow-2xl border-t border-white/80 transition-all duration-500 ease-out transform translate-y-full opacity-0 z-20">
        <div class="w-12 h-1.5 bg-[#E7E5E4] rounded-full mx-auto mb-6 cursor-pointer hover:bg-stone-300 transition"
            onclick="closeForms()"></div>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-[#1C1917]">Welcome Back 🌸</h2>
            <p class="text-xs text-[#78716C] mt-1">Please enter your credentials to access your planner.</p>
        </div>

        @if ($errors->has('email') && !old('name'))
            <div
                class="p-3 mb-4 text-xs font-semibold text-red-600 bg-red-50/80 border border-red-100 rounded-xl backdrop-blur-sm">
                {{ $errors->first('email') }}
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-[#1C1917] uppercase tracking-wider mb-1.5">Email
                    Address</label>
                <input type="email" name="email"
                    class="w-full px-4 py-3 rounded-xl bg-white/90 border border-pink-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#F472B6]/40 text-[#1C1917] shadow-sm transition"
                    placeholder="example@email.com" required>
            </div>
            <div>
                <label
                    class="block text-[10px] font-bold text-[#1C1917] uppercase tracking-wider mb-1.5">Password</label>
                <input type="password" name="password"
                    class="w-full px-4 py-3 rounded-xl bg-white/90 border border-pink-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#F472B6]/40 text-[#1C1917] shadow-sm transition"
                    placeholder="••••••••" required>
            </div>
            <button type="submit"
                class="w-full py-3.5 mt-4 rounded-xl bg-[#1C1917] text-[#FFF5F5] font-semibold text-sm shadow-lg hover:bg-[#37312D] transition-all duration-200">Sign
                In</button>
        </form>

        <div class="text-center mt-6">
            <button onclick="switchForm('register-form')" class="text-xs text-[#78716C] tracking-wide">Don't have an
                account? <span class="text-[#DB2777] font-bold hover:underline">Sign up</span></button>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- SCREEN 3: REGISTER SLIDE-UP FORM CONTAINER                                -->
    <!-- ========================================================================= -->
    <div id="register-form"
        class="absolute bottom-0 left-0 right-0 max-w-md mx-auto w-full bg-white/75 backdrop-blur-2xl p-8 rounded-t-[2.5rem] shadow-2xl border-t border-white/80 transition-all duration-500 ease-out transform translate-y-full opacity-0 z-20">
        <div class="w-12 h-1.5 bg-[#E7E5E4] rounded-full mx-auto mb-6 cursor-pointer hover:bg-stone-300 transition"
            onclick="closeForms()"></div>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-[#1C1917]">Create Account ✨</h2>
            <p class="text-xs text-[#78716C] mt-1">Join the community and organize your requirements.</p>
        </div>

        @if ($errors->any() && old('name'))
            <div
                class="p-3 mb-4 text-xs font-semibold text-red-600 bg-red-50/80 border border-red-100 rounded-xl backdrop-blur-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-[#1C1917] uppercase tracking-wider mb-1.5">Full
                    Name</label>
                <input type="text" name="name"
                    class="w-full px-4 py-3 rounded-xl bg-white/90 border border-pink-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#F472B6]/40 text-[#1C1917] shadow-sm transition"
                    placeholder="Future Dentist" required>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-[#1C1917] uppercase tracking-wider mb-1.5">Email
                    Address</label>
                <input type="email" name="email"
                    class="w-full px-4 py-3 rounded-xl bg-white/90 border border-pink-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#F472B6]/40 text-[#1C1917] shadow-sm transition"
                    placeholder="example@email.com" required>
            </div>
            <div>
                <label
                    class="block text-[10px] font-bold text-[#1C1917] uppercase tracking-wider mb-1.5">Password</label>
                <input type="password" name="password"
                    class="w-full px-4 py-3 rounded-xl bg-white/90 border border-pink-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#F472B6]/40 text-[#1C1917] shadow-sm transition"
                    placeholder="Min. 8 characters" required>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-[#1C1917] uppercase tracking-wider mb-1.5">Confirm
                    Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full px-4 py-3 rounded-xl bg-white/90 border border-pink-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#F472B6]/40 text-[#1C1917] shadow-sm transition"
                    placeholder="••••••••" required>
            </div>
            <button type="submit"
                class="w-full py-3.5 mt-4 rounded-xl bg-[#1C1917] text-[#FFF5F5] font-semibold text-sm shadow-lg hover:bg-[#37312D] transition-all duration-200">Create
                Account</button>
        </form>

        <div class="text-center mt-6">
            <button onclick="switchForm('login-form')" class="text-xs text-[#78716C] tracking-wide">Already have an
                account? <span class="text-[#DB2777] font-bold hover:underline">Log in</span></button>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- INTERACTION & AUTO-ERROR CONTROL LOGIC                                    -->
    <!-- ========================================================================= -->
    <script>
        const splash = document.getElementById('splash-screen');

        function openForm(formId) {
            // 1. Fade out and scale down the splash layout text/buttons
            splash.classList.remove('scale-100', 'opacity-100');
            splash.classList.add('scale-95', 'opacity-0', 'pointer-events-none');

            // 2. Smoothly lift, shrink, and fade out the center graphic ring
            const graphic = document.getElementById('center-graphic');
            if (graphic) {
                graphic.classList.add('-translate-y-8', 'opacity-0', 'scale-90');
            }

            // 3. Bring up the requested form container
            const targetForm = document.getElementById(formId);
            targetForm.classList.remove('translate-y-full', 'opacity-0');
            targetForm.classList.add('translate-y-0', 'opacity-100');
        }

        function closeForms() {
            // 1. Reset forms back down under the screen viewport
            ['login-form', 'register-form'].forEach(id => {
                const form = document.getElementById(id);
                form.classList.remove('translate-y-0', 'opacity-100');
                form.classList.add('translate-y-full', 'opacity-0');
            });

            // 2. Bring back the main splash menu configurations
            splash.classList.remove('scale-95', 'opacity-0', 'pointer-events-none');
            splash.classList.add('scale-100', 'opacity-100');

            // 3. Return the center graphic back to its gorgeous resting state
            const graphic = document.getElementById('center-graphic');
            if (graphic) {
                graphic.classList.remove('-translate-y-8', 'opacity-0', 'scale-90');
            }
        }

        function switchForm(targetFormId) {
            const currentFormId = targetFormId === 'login-form' ? 'register-form' : 'login-form';

            document.getElementById(currentFormId).classList.add('translate-y-full', 'opacity-0');
            document.getElementById(currentFormId).classList.remove('translate-y-0', 'opacity-100');

            setTimeout(() => {
                document.getElementById(targetFormId).classList.remove('translate-y-full', 'opacity-0');
                document.getElementById(targetFormId).classList.add('translate-y-0', 'opacity-100');
            }, 200);
        }

        // Keep forms locked open if returned with database rules error alerts
        window.addEventListener('DOMContentLoaded', () => {
            @if ($errors->has('email') && !old('name'))
                openForm('login-form');
            @endif

            @if ($errors->any() && old('name'))
                openForm('register-form');
            @endif
        });
    </script>
</body>

</html>
