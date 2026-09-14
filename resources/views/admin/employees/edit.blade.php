@extends('layouts.app')

@section('header', 'Edit Employee Profile')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl border border-slate-200/80 p-8 shadow-sm">
    <h2 class="text-xl font-bold text-slate-900 mb-6">Edit Employee Profile</h2>

    <form action="{{ route('admin.employees.update', $employee) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">First Name *</label>
                <input type="text" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Last Name *</label>
                <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Email Address *</label>
                <input type="email" name="email" value="{{ old('email', $employee->email) }}" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Employee Code</label>
                <input type="text" disabled value="{{ $employee->employee_code }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-100 border border-slate-200 text-sm font-mono text-slate-500">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Department</label>
                <select name="department_id" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                    <option value="">Select Department</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->id }}" {{ old('department_id', $employee->department_id) == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Designation</label>
                <select name="designation_id" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                    <option value="">Select Designation</option>
                    @foreach($designations as $ds)
                        <option value="{{ $ds->id }}" {{ old('designation_id', $employee->designation_id) == $ds->id ? 'selected' : '' }}>{{ $ds->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Work Schedule</label>
                <select name="work_schedule_id" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                    <option value="">Select Shift</option>
                    @foreach($schedules as $s)
                        <option value="{{ $s->id }}" {{ old('work_schedule_id', $employee->work_schedule_id) == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Joining Date *</label>
                <input type="date" name="joining_date" value="{{ old('joining_date', $employee->joining_date ? $employee->joining_date->format('Y-m-d') : '') }}" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Employment Status *</label>
                <select name="employment_status" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                    <option value="active" {{ old('employment_status', $employee->employment_status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('employment_status', $employee->employment_status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="terminated" {{ old('employment_status', $employee->employment_status) === 'terminated' ? 'selected' : '' }}>Terminated</option>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4">
            <a href="{{ route('admin.employees.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-200">Cancel</a>
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-500 shadow">Update Profile</button>
        </div>
    </form>
</div>
@endsection
