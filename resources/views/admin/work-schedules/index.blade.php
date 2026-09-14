@extends('layouts.app')

@section('header', 'Work Schedules')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Work Schedules</h2>
            <p class="text-sm text-slate-500">Manage shift schedules and grace periods.</p>
        </div>
        <a href="{{ route('admin.work-schedules.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-md transition inline-flex items-center justify-center">
            + Add Schedule
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-xs tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Schedule Name</th>
                        <th class="px-6 py-4">Working Hours</th>
                        <th class="px-6 py-4">Grace Period</th>
                        <th class="px-6 py-4">Employees</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($schedules as $sched)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $sched->name }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-700">{{ $sched->start_time }} - {{ $sched->end_time }}</td>
                            <td class="px-6 py-4"><span class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-full font-semibold text-xs">{{ $sched->grace_period }} mins</span></td>
                            <td class="px-6 py-4"><span class="px-3 py-1 bg-slate-100 rounded-full font-semibold text-slate-700 text-xs">{{ $sched->employees_count }} employees</span></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider {{ $sched->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $sched->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <form action="{{ route('admin.work-schedules.toggle-status', $sched) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs text-amber-600 hover:underline font-medium">Toggle</button>
                                </form>
                                <a href="{{ route('admin.work-schedules.edit', $sched) }}" class="text-xs text-indigo-600 hover:underline font-medium">Edit</a>
                                <form action="{{ route('admin.work-schedules.destroy', $sched) }}" method="POST" class="inline" onsubmit="return confirm('Delete work schedule?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-rose-600 hover:underline font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">No work schedules found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $schedules->links() }}
        </div>
    </div>
</div>
@endsection
