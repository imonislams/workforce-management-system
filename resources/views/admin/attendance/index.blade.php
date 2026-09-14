@extends('layouts.app')

@section('header', 'Attendance Log')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Attendance Records</h2>
            <p class="text-sm text-slate-500">Monitor employee check-ins, check-outs, and daily metrics.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50">
            <form action="{{ route('admin.attendance.index') }}" method="GET" class="flex flex-wrap gap-3 items-center">
                <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                <select name="department_id" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                    <option value="">All Departments</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->id }}" {{ request('department_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Present</option>
                    <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Late</option>
                    <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>Absent</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded-xl">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-xs tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Employee</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Check In</th>
                        <th class="px-6 py-4">Check Out</th>
                        <th class="px-6 py-4">Working Hours</th>
                        <th class="px-6 py-4">Late Mins</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($records as $rec)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 font-bold text-slate-900">
                                {{ $rec->employee->full_name ?? 'N/A' }}
                                <div class="text-xs text-slate-400 font-normal">{{ $rec->employee->employee_code ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs">{{ $rec->date ? $rec->date->format('Y-m-d') : '' }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-indigo-600">{{ $rec->check_in ? $rec->check_in->format('H:i:s') : '--:--' }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-700">{{ $rec->check_out ? $rec->check_out->format('H:i:s') : '--:--' }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ $rec->working_hours }} hrs</td>
                            <td class="px-6 py-4 text-xs font-mono text-amber-600">{{ $rec->late_minutes }} m</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider {{ $rec->status === 'present' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($rec->status === 'late' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700') }}">
                                    {{ $rec->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400">No attendance logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $records->links() }}
        </div>
    </div>
</div>
@endsection
