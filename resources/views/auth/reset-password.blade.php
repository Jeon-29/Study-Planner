<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FDFBF7] min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white/80 backdrop-blur-2xl p-8 rounded-3xl shadow-2xl border border-white">
        <h2 class="text-2xl font-bold text-[#1C1917] mb-2">Reset Password 🔑</h2>
        <p class="text-xs text-[#78716C] mb-6">Enter your email and new password below.</p>

        @if ($errors->any())
            <div class="p-3 mb-4 text-xs font-semibold text-red-600 bg-red-50 border border-red-100 rounded-xl">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="block text-[10px] font-bold uppercase mb-1.5">Email Address</label>
                <input type="email" name="email" value="{{ $email ?? old('email') }}" required
                    class="w-full px-4 py-3 rounded-xl bg-white border border-pink-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#F472B6]">
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase mb-1.5">New Password</label>
                <input type="password" name="password" required placeholder="Min. 8 characters"
                    class="w-full px-4 py-3 rounded-xl bg-white border border-pink-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#F472B6]">
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase mb-1.5">Confirm Password</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••"
                    class="w-full px-4 py-3 rounded-xl bg-white border border-pink-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#F472B6]">
            </div>

            <button type="submit" class="w-full py-3.5 mt-2 rounded-xl bg-[#1C1917] text-white font-semibold text-sm shadow-lg hover:bg-[#37312D] transition">
                Update Password
            </button>
        </form>
    </div>
</body>
</html>