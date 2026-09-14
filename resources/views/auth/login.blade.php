<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Workforce Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-900 text-slate-100">
    <div class="max-w-md w-full space-y-8 bg-slate-800 p-8 rounded-2xl shadow-2xl border border-slate-700/80" x-data="{ tab: 'admin' }">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-600/20 text-indigo-400 mb-4 border border-indigo-500/30">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-white">Workforce Management System</h2>
            <p class="mt-2 text-sm text-slate-400">Sign in to your account to continue</p>
        </div>

        @if (session('status'))
            <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <div class="flex rounded-xl bg-slate-900/60 p-1 border border-slate-700/50">
            <button type="button" @click="tab = 'admin'" :class="tab === 'admin' ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:text-white'" class="flex-1 py-2 text-sm font-medium rounded-lg transition-all">
                Administrator
            </button>
            <button type="button" @click="tab = 'employee'" :class="tab === 'employee' ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:text-white'" class="flex-1 py-2 text-sm font-medium rounded-lg transition-all">
                Employee
            </button>
        </div>

        <form action="{{ route('login') }}" method="POST" class="mt-8 space-y-5">
            @csrf
            <input type="hidden" name="login_type" :value="tab">

            <div x-show="tab === 'admin'" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                    @error('email')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div x-show="tab === 'employee'" class="space-y-4" style="display: none;">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Employee Code</label>
                    <input type="text" name="employee_code" value="{{ old('employee_code') }}" placeholder="EMP-0001" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                    @error('employee_code')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300">Password</label>
                    <a href="{{ route('password.request') }}" class="text-xs text-indigo-400 hover:text-indigo-300 transition">Forgot password?</a>
                </div>
                <input type="password" name="password" placeholder="••••••••" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                @error('password')
                    <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <input id="remember" type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-800">
                <label for="remember" class="ml-2 text-xs text-slate-300">Remember me for 30 days</label>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-lg shadow-indigo-600/30 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-800">
                Sign In
            </button>
        </form>
    </div>
</body>
</html>
