@extends('layouts.app')

@section('header', 'Workforce Reports')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Reports & Analytics</h2>
            <p class="text-sm text-slate-500">Filter, analyze, and export workforce attendance data.</p>
        </div>
        <a href="{{ route('admin.reports.export', request()->query()) }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl shadow-md transition inline-flex items-center justify-center">
            Export CSV
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap gap-3 items-center">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
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
                <button type="submit" class="px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded-xl">Generate Report</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-xs tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Employee</th>
                        <th class="px-6 py-4">Department</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Check In</th>
                        <th class="px-6 py-4">Check Out</th>
                        <th class="px-6 py-4">Working Hours</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($records as $rec)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $rec->employee->full_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $rec->employee->department->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-mono text-xs">{{ $rec->date ? $rec->date->format('Y-m-d') : '' }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-indigo-600">{{ $rec->check_in ? $rec->check_in->format('H:i:s') : '--' }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-700">{{ $rec->check_out ? $rec->check_out->format('H:i:s') : '--' }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ $rec->working_hours }} hrs</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider {{ $rec->status === 'present' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ $rec->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400">No report records matching criteria.</td>
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
