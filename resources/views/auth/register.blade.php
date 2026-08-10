@extends('layouts.auth')

@section('content')
    <div class="w-full max-w-md bg-white/70 backdrop-blur-md p-8 rounded-3xl shadow-xl border border-white/40">

        <div class="text-center mb-8">
            <span
                class="text-xs uppercase tracking-widest text-[#DB2777] font-semibold bg-pink-100/60 px-3 py-1 rounded-full">Join
                Us</span>
            <h1 class="text-3xl font-bold text-[#1C1917] mt-3">Create Account</h1>
            <p class="text-sm text-[#78716C] mt-1">Organize your medical journey beautifully.</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-[#1C1917] uppercase tracking-wider mb-2">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full px-4 py-3 rounded-xl bg-white/90 border border-pink-100 text-[#1C1917] focus:outline-none focus:ring-2 focus:ring-[#F472B6]/40 text-sm transition"
                    placeholder="Future Doc" required>
                @error('name')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#1C1917] uppercase tracking-wider mb-2">Email
                    Address</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-4 py-3 rounded-xl bg-white/90 border border-pink-100 text-[#1C1917] focus:outline-none focus:ring-2 focus:ring-[#F472B6]/40 text-sm transition"
                    placeholder="you@university.edu" required>
                @error('email')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#1C1917] uppercase tracking-wider mb-2">Password</label>
                <input type="password" name="password"
                    class="w-full px-4 py-3 rounded-xl bg-white/90 border border-pink-100 text-[#1C1917] focus:outline-none focus:ring-2 focus:ring-[#F472B6]/40 text-sm transition"
                    placeholder="••••••••" required>
                @error('password')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#1C1917] uppercase tracking-wider mb-2">Confirm
                    Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full px-4 py-3 rounded-xl bg-white/90 border border-pink-100 text-[#1C1917] focus:outline-none focus:ring-2 focus:ring-[#F472B6]/40 text-sm transition"
                    placeholder="••••••••" required>
            </div>

            <button type="submit"
                class="w-full py-3 mt-2 rounded-xl bg-[#1C1917] hover:bg-[#37312D] text-[#FFF5F5] font-medium text-sm shadow-lg hover:shadow-xl transition-all duration-200">
                Sign Up
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-xs text-[#78716C]">Already have an account? <a href="{{ route('login') }}"
                    class="text-[#DB2777] font-semibold hover:underline">Log in</a></p>
        </div>
    </div>
@endsection
