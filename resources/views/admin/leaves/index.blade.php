@extends('layouts.app')

@section('header', 'Leave Applications')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Leave Applications</h2>
            <p class="text-sm text-slate-500">Review and approve employee leave requests.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-xs tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Employee</th>
                        <th class="px-6 py-4">Leave Type</th>
                        <th class="px-6 py-4">Dates</th>
                        <th class="px-6 py-4">Days</th>
                        <th class="px-6 py-4">Reason</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($leaves as $leave)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $leave->employee->full_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $leave->leaveType->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-mono text-xs">{{ $leave->start_date->format('Y-m-d') }} to {{ $leave->end_date->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ $leave->total_days }}</td>
                            <td class="px-6 py-4 text-xs text-slate-500 max-w-xs truncate">{{ $leave->reason }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider {{ $leave->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($leave->status === 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700') }}">
                                    {{ $leave->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                @if($leave->status === 'pending')
                                    <form action="{{ route('admin.leaves.approve', $leave) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs text-emerald-600 hover:underline font-bold">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.leaves.reject', $leave) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs text-rose-600 hover:underline font-bold">Reject</button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400">Processed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400">No leave applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $leaves->links() }}
        </div>
    </div>
</div>
@endsection
