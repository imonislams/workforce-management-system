@extends('layouts.app')

@section('header', 'My Leaves')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm h-fit">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Apply for Leave</h3>
            <form action="{{ route('employee.leaves.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Leave Type *</label>
                    <select name="leave_type_id" required class="w-full px-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                        <option value="">Select Type</option>
                        @foreach($leaveTypes as $lt)
                            <option value="{{ $lt->id }}">{{ $lt->name }} ({{ $lt->max_days }} days max)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Start Date *</label>
                    <input type="date" name="start_date" required class="w-full px-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">End Date *</label>
                    <input type="date" name="end_date" required class="w-full px-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Reason *</label>
                    <textarea name="reason" rows="3" required placeholder="Reason for leave..." class="w-full px-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500"></textarea>
                </div>
                <button type="submit" class="w-full py-2.5 bg-indigo-600 text-white font-semibold text-sm rounded-xl hover:bg-indigo-500 shadow">Submit Application</button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">My Leave Applications History</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-xs tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4">Dates</th>
                            <th class="px-6 py-4">Days</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($leaves as $leave)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 font-bold text-slate-900">{{ $leave->leaveType->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 font-mono text-xs">{{ $leave->start_date->format('Y-m-d') }} to {{ $leave->end_date->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ $leave->total_days }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider {{ $leave->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($leave->status === 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700') }}">
                                        {{ $leave->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($leave->status === 'pending')
                                        <form action="{{ route('employee.leaves.cancel', $leave) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs text-rose-600 hover:underline font-bold">Cancel</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400">--</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400">No leave applications found.</td>
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
</div>
@endsection
