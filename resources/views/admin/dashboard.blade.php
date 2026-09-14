<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Workforce Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full bg-slate-100 text-slate-800" x-data="{ sidebarOpen: false }">
    <div class="min-h-full flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 text-white flex-shrink-0 flex flex-col justify-between hidden lg:flex">
            <div class="p-6">
                <h1 class="text-xl font-bold tracking-tight text-indigo-400">Workforce WMS</h1>
                <p class="text-xs text-slate-400 mt-0.5">Management Suite</p>
                <nav class="mt-8 space-y-1">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium bg-indigo-600 text-white shadow">Dashboard</a>
                        <a href="{{ route('admin.employees.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">Employees</a>
                        <a href="{{ route('admin.departments.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">Departments</a>
                        <a href="{{ route('admin.designations.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">Designations</a>
                        <a href="{{ route('admin.work-schedules.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">Work Schedules</a>
                        <a href="{{ route('admin.attendance.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">Attendance</a>
                        <a href="{{ route('admin.leaves.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">Leaves</a>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">Users</a>
                        <a href="{{ route('admin.reports.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">Reports</a>
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">Settings</a>
                    @else
                        <a href="{{ route('employee.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium bg-indigo-600 text-white shadow">My Dashboard</a>
                        <a href="{{ route('employee.attendance.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">My Attendance</a>
                        <a href="{{ route('employee.leaves.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">My Leaves</a>
                    @endif
                </nav>
            </div>
            <div class="p-6 border-t border-slate-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left text-sm text-rose-400 hover:text-rose-300 transition font-medium">Sign Out</button>
                </form>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Topbar -->
            <header class="bg-white border-b border-slate-200 py-4 px-6 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">
                    {{ auth()->user()->isAdmin() ? 'Administrator View' : 'Employee Self-Service' }}
                </h2>
                <div class="flex items-center space-x-3">
                    <span class="text-sm font-medium text-slate-700">{{ auth()->user()->name }}</span>
                    <span class="text-xs uppercase px-2.5 py-1 rounded-full font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">{{ auth()->user()->role }}</span>
                </div>
            </header>

            <main class="p-6 flex-1">
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-700 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="bg-white rounded-2xl p-8 border border-slate-200/80 shadow-sm">
                    <h3 class="text-2xl font-bold text-slate-900">Welcome to Workforce Management System</h3>
                    <p class="text-slate-500 mt-2">Manage workforce metrics, attendance, schedules, leaves, and analytics efficiently.</p>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
