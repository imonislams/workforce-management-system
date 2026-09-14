@extends('layouts.app')

@section('header', 'My Attendance')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Today's Check In / Check Out</h2>
            <p class="text-sm text-slate-500">Record your daily work attendance.</p>
        </div>
        <div class="flex items-center space-x-3">
            @if(!$attendance)
                <form action="{{ route('employee.check-in') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl shadow">Check In Now</button>
                </form>
            @elseif($attendance && !$attendance->check_out)
                <form action="{{ route('employee.check-out') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-2.5 bg-rose-600 hover:bg-rose-500 text-white font-semibold text-sm rounded-xl shadow">Check Out Now</button>
                </form>
            @else
                <span class="px-4 py-2 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-xl border border-emerald-200">Attendance Logged for Today</span>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-900">Recent Attendance History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-xs tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Check In</th>
                        <th class="px-6 py-4">Check Out</th>
                        <th class="px-6 py-4">Working Hours</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentAttendance as $rec)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 font-mono font-bold text-slate-900">{{ $rec->date ? $rec->date->format('Y-m-d') : '' }}</td>
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
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">No attendance records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $recentAttendance->links() }}
        </div>
    </div>
</div>
@endsection
