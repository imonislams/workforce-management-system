<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Workforce Management System')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full text-slate-800" x-data="{ sidebarOpen: false }">
    <div class="min-h-full flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 text-slate-300 flex-shrink-0 flex flex-col justify-between hidden lg:flex border-r border-slate-800">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-indigo-600/30">
                        W
                    </div>
                    <div>
                        <h1 class="text-base font-bold text-white leading-tight">Workforce WMS</h1>
                        <p class="text-xs text-slate-400">Enterprise System</p>
                    </div>
                </div>

                <nav class="space-y-1">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white transition' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.employees.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('admin.employees.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white transition' }}">
                            Employees
                        </a>
                        <a href="{{ route('admin.departments.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('admin.departments.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white transition' }}">
                            Departments
                        </a>
                        <a href="{{ route('admin.designations.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('admin.designations.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white transition' }}">
                            Designations
                        </a>
                        <a href="{{ route('admin.work-schedules.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('admin.work-schedules.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white transition' }}">
                            Work Schedules
                        </a>
                        <a href="{{ route('admin.attendance.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('admin.attendance.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white transition' }}">
                            Attendance
                        </a>
                        <a href="{{ route('admin.leaves.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('admin.leaves.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white transition' }}">
                            Leave Applications
                        </a>
                        <a href="{{ route('admin.leave-types.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('admin.leave-types.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white transition' }}">
                            Leave Types
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white transition' }}">
                            Users
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white transition' }}">
                            Reports
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white transition' }}">
                            Settings
                        </a>
                    @else
                        <a href="{{ route('employee.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('employee.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white transition' }}">
                            My Dashboard
                        </a>
                        <a href="{{ route('employee.attendance.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('employee.attendance.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white transition' }}">
                            My Attendance
                        </a>
                        <a href="{{ route('employee.leaves.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('employee.leaves.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white transition' }}">
                            My Leaves
                        </a>
                    @endif
                </nav>
            </div>

            <div class="p-6 border-t border-slate-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left text-sm text-rose-400 hover:text-rose-300 font-medium transition flex items-center justify-between">
                        <span>Sign Out</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Header Topbar -->
            <header class="bg-white border-b border-slate-200/80 py-4 px-6 sm:px-8 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h1 class="text-xl font-bold text-slate-900">@yield('header', 'Dashboard')</h1>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-slate-900 text-white font-bold flex items-center justify-center text-sm shadow">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6 sm:p-8 flex-1 max-w-7xl w-full mx-auto">
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center justify-between">
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium flex items-center justify-between">
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
