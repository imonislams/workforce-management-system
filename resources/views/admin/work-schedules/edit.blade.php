@extends('layouts.app')

@section('header', 'Edit Work Schedule')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl border border-slate-200/80 p-8 shadow-sm">
    <h2 class="text-xl font-bold text-slate-900 mb-6">Edit Work Schedule</h2>

    <form action="{{ route('admin.work-schedules.update', $workSchedule) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Schedule Name *</label>
            <input type="text" name="name" value="{{ old('name', $workSchedule->name) }}" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
            @error('name')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Start Time *</label>
                <input type="text" name="start_time" value="{{ old('start_time', $workSchedule->start_time) }}" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">End Time *</label>
                <input type="text" name="end_time" value="{{ old('end_time', $workSchedule->end_time) }}" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Break Start</label>
                <input type="text" name="break_start" value="{{ old('break_start', $workSchedule->break_start) }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Break End</label>
                <input type="text" name="break_end" value="{{ old('break_end', $workSchedule->break_end) }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Grace Period (Minutes) *</label>
            <input type="number" name="grace_period" value="{{ old('grace_period', $workSchedule->grace_period) }}" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Status *</label>
            <select name="status" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                <option value="active" {{ old('status', $workSchedule->status) === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $workSchedule->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4">
            <a href="{{ route('admin.work-schedules.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-200">Cancel</a>
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-500 shadow">Update Schedule</button>
        </div>
    </form>
</div>
@endsection
