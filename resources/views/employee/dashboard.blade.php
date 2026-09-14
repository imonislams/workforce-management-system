<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - Workforce WMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-100 text-slate-800">
    <div class="min-h-full flex">
        <div class="flex-1 p-6">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl p-8 border border-slate-200/80 shadow-sm space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">Hello, {{ $employee ? $employee->first_name : auth()->user()->name }}!</h2>
                        <p class="text-sm text-slate-500">Employee Code: <span class="font-mono font-bold text-indigo-600">{{ $employee ? $employee->employee_code : 'N/A' }}</span></p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-rose-600 hover:underline font-medium">Sign Out</button>
                    </form>
                </div>

                @if (session('success'))
                    <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-700 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="bg-indigo-50 border border-indigo-200 p-6 rounded-xl flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-indigo-900">Today's Attendance</h3>
                        <p class="text-xs text-indigo-700 mt-1">Status: {{ $todayAttendance ? ucfirst($todayAttendance->status) : 'Not Checked In' }}</p>
                    </div>
                    <div class="space-x-2 flex items-center">
                        @if(!$todayAttendance)
                            <form action="{{ route('employee.check-in') }}" method="POST">
                                @csrf
                                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-xl shadow hover:bg-indigo-500 transition">Check In</button>
                            </form>
                        @elseif($todayAttendance && !$todayAttendance->check_out)
                            <form action="{{ route('employee.check-out') }}" method="POST">
                                @csrf
                                <button type="submit" class="px-5 py-2.5 bg-rose-600 text-white font-medium rounded-xl shadow hover:bg-rose-500 transition">Check Out</button>
                            </form>
                        @else
                            <span class="px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl">Completed Today</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
