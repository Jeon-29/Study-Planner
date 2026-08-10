@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="max-w-md mx-auto pt-6 pb-32 px-4 space-y-5">

        <!-- Display Success or Error Messages -->
        @if(session('success'))
            <div class="p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold text-center shadow-2xs">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-3 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold shadow-2xs">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- MAIN PROFILE EDIT FORM (Handles Info, Track, Year Level, and Avatar Upload) -->
        <!-- ========================================================================= -->
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PATCH')

            <!-- Identity & Academic Header -->
            <div class="bg-white/70 backdrop-blur-md p-6 rounded-[24px] border border-stone-200/60 shadow-2xs flex flex-col items-center text-center relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-28 h-28 bg-[#DB2777]/10 rounded-full blur-2xl"></div>

                <!-- Avatar Upload Wrapper -->
                <label for="avatar-input" class="cursor-pointer group relative mb-3">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-pink-200 to-indigo-200 flex items-center justify-center overflow-hidden shadow-2xs border-2 border-white/80 group-hover:opacity-90 transition">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <span class="text-2xl font-black text-indigo-950">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div class="absolute inset-0 rounded-full bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-white">
                        <span class="material-icons-round text-base">camera_alt</span>
                    </div>
                    <input type="file" name="avatar" id="avatar-input" class="hidden" accept="image/*" onchange="this.form.submit()">
                </label>

                <h1 class="text-base font-black text-[#1C1917] tracking-tight">{{ auth()->user()->name }}</h1>
                <p class="text-[11px] font-medium text-stone-500 mt-0.5">{{ auth()->user()->email }}</p>

                <!-- Editable Track / Year Badge Fields -->
                <div class="mt-3 flex items-center justify-center gap-2 w-full">
                    <input type="text" name="track" value="{{ old('track', auth()->user()->track ?? 'BSIT Student') }}" placeholder="Track/Course"
                        class="w-1/2 px-2 py-1 text-center rounded-lg bg-stone-100/80 border border-stone-200/60 text-stone-700 text-[10px] font-extrabold uppercase tracking-wider focus:outline-none focus:border-[#DB2777]">

                    <input type="text" name="year_level" value="{{ old('year_level', auth()->user()->year_level ?? '2nd Year') }}" placeholder="Year Level"
                        class="w-1/3 px-2 py-1 text-center rounded-lg bg-stone-100/80 border border-stone-200/60 text-stone-700 text-[10px] font-extrabold uppercase tracking-wider focus:outline-none focus:border-[#DB2777]">
                </div>
            </div>

            <!-- Stats Quick Overview -->
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white/70 backdrop-blur-md border border-stone-200/60 p-4 rounded-[22px] shadow-2xs">
                    <p class="text-[10px] font-bold text-stone-400 uppercase tracking-wider mb-1">Subjects</p>
                    <div class="text-2xl font-black text-[#1C1917]">{{ $subjectsCount ?? 0 }}</div>
                    <p class="text-[10px] text-stone-500 font-medium mt-0.5">Active clusters</p>
                </div>

                <div class="bg-white/70 backdrop-blur-md border border-stone-200/60 p-4 rounded-[22px] shadow-2xs">
                    <p class="text-[10px] font-bold text-stone-400 uppercase tracking-wider mb-1">Tasks</p>
                    <div class="text-2xl font-black text-[#1C1917]">{{ $totalTasks ?? 0 }}</div>
                    <p class="text-[10px] text-stone-500 font-medium mt-0.5">Total assignments</p>
                </div>
            </div>

            <!-- Edit Personal Information Fields -->
            <div class="bg-white/70 backdrop-blur-md border border-stone-200/60 rounded-[24px] p-5 shadow-2xs space-y-4">
                <h2 class="text-[11px] font-bold text-stone-800 uppercase tracking-wider px-1">Personal Details</h2>

                <div class="space-y-3">
                    <div>
                        <label class="block text-[10px] font-bold text-stone-500 uppercase tracking-wider mb-1 px-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                            class="w-full px-3 py-2.5 rounded-xl bg-stone-50/60 border border-stone-200/60 text-xs font-semibold text-[#1C1917] focus:outline-none focus:border-[#DB2777] transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-stone-500 uppercase tracking-wider mb-1 px-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                            class="w-full px-3 py-2.5 rounded-xl bg-stone-50/60 border border-stone-200/60 text-xs font-semibold text-[#1C1917] focus:outline-none focus:border-[#DB2777] transition-all">
                    </div>

                    <button type="submit"
                        class="w-full h-11 mt-1 rounded-xl bg-[#1C1917] text-white text-xs font-bold hover:bg-stone-800 transition-all duration-200 active:scale-98 shadow-2xs flex items-center justify-center gap-2">
                        <span class="material-icons-round text-sm">save</span>
                        Save Profile Changes
                    </button>
                </div>
            </div>
        </form>

        <!-- ========================================================================= -->
        <!-- SECURITY SECTION (Button to Open Modal)                                   -->
        <!-- ========================================================================= -->
        <div class="bg-white/70 backdrop-blur-md border border-stone-200/60 rounded-[24px] p-5 shadow-2xs space-y-3">
            <h2 class="text-[11px] font-bold text-stone-800 uppercase tracking-wider px-1">Security & Password</h2>

            <button type="button" onclick="openPasswordModal()"
                class="w-full h-11 rounded-xl bg-stone-100 hover:bg-stone-200/80 border border-stone-200/80 text-stone-800 text-xs font-bold transition-all duration-200 active:scale-98 shadow-2xs flex items-center justify-center gap-2">
                <span class="material-icons-round text-sm text-stone-500">lock_reset</span>
                Update Password
            </button>
        </div>

        <!-- ========================================================================= -->
        <!-- SIGN OUT SECTION                                                         -->
        <!-- ========================================================================= -->
        <div class="bg-white/70 backdrop-blur-md border border-stone-200/60 rounded-[24px] p-5 shadow-2xs">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full h-11 rounded-xl bg-rose-50/80 border border-rose-200/60 text-rose-600 text-xs font-bold hover:bg-rose-100/80 transition-all duration-200 active:scale-98 shadow-2xs flex items-center justify-center gap-2">
                    <span class="material-icons-round text-sm">logout</span>
                    Sign Out
                </button>
            </form>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- PASSWORD UPDATE POPUP MODAL                                               -->
    <!-- ========================================================================= -->
    <div id="passwordModal" class="fixed inset-0 bg-black/40 backdrop-blur-xs z-50 flex items-center justify-center px-4 hidden">
        <div class="bg-white rounded-[24px] border border-stone-200 p-6 w-full max-w-sm shadow-xl space-y-4 relative animate-in fade-in zoom-in duration-200">
            <div class="flex items-center justify-between border-b border-stone-100 pb-3">
                <h3 class="text-xs font-bold text-stone-900 uppercase tracking-wider">Change Password</h3>
                <button type="button" onclick="closePasswordModal()" class="text-stone-400 hover:text-stone-700">
                    <span class="material-icons-round text-base">close</span>
                </button>
            </div>

            <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-3">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-[10px] font-bold text-stone-500 uppercase tracking-wider mb-1">Current Password</label>
                    <input type="password" name="current_password" required placeholder="••••••••"
                        class="w-full px-3 py-2 rounded-xl bg-stone-50 border border-stone-200 text-xs font-semibold text-[#1C1917] focus:outline-none focus:border-[#DB2777]">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-stone-500 uppercase tracking-wider mb-1">New Password</label>
                    <input type="password" name="password" required placeholder="••••••••"
                        class="w-full px-3 py-2 rounded-xl bg-stone-50 border border-stone-200 text-xs font-semibold text-[#1C1917] focus:outline-none focus:border-[#DB2777]">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-stone-500 uppercase tracking-wider mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required placeholder="••••••••"
                        class="w-full px-3 py-2 rounded-xl bg-stone-50 border border-stone-200 text-xs font-semibold text-[#1C1917] focus:outline-none focus:border-[#DB2777]">
                </div>

                <div class="pt-2 flex gap-2">
                    <button type="button" onclick="closePasswordModal()"
                        class="flex-1 h-10 rounded-xl bg-stone-100 text-stone-600 text-xs font-bold hover:bg-stone-200 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 h-10 rounded-xl bg-[#1C1917] text-white text-xs font-bold hover:bg-stone-800 transition shadow-2xs">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Toggle Script -->
    <script>
        function openPasswordModal() {
            document.getElementById('passwordModal').classList.remove('hidden');
        }
        function closePasswordModal() {
            document.getElementById('passwordModal').classList.add('hidden');
        }
    </script>
@endsection
