@extends('layouts.app')

@section('header', 'Employee Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl border border-slate-200/80 p-8 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="flex items-center space-x-5">
            <div class="w-16 h-16 rounded-full bg-indigo-600 text-white font-bold text-2xl flex items-center justify-center shadow-lg">
                {{ strtoupper(substr($employee->first_name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-900">{{ $employee->full_name }}</h2>
                <p class="text-sm font-mono text-indigo-600 font-semibold">{{ $employee->employee_code }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $employee->department->name ?? 'No Department' }} &bull; {{ $employee->designation->name ?? 'No Designation' }}</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.employees.edit', $employee) }}" class="px-5 py-2.5 bg-indigo-600 text-white font-semibold text-sm rounded-xl shadow hover:bg-indigo-500">Edit Profile</a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-3">
            <h3 class="text-base font-bold text-slate-900">Personal Information</h3>
            <p class="text-sm"><span class="text-slate-500 font-medium">Email:</span> {{ $employee->email }}</p>
            <p class="text-sm"><span class="text-slate-500 font-medium">Phone:</span> {{ $employee->phone ?: 'N/A' }}</p>
            <p class="text-sm"><span class="text-slate-500 font-medium">Joining Date:</span> {{ $employee->joining_date ? $employee->joining_date->format('Y-m-d') : 'N/A' }}</p>
            <p class="text-sm"><span class="text-slate-500 font-medium">Status:</span> <span class="uppercase font-bold text-xs text-emerald-600">{{ $employee->employment_status }}</span></p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-3">
            <h3 class="text-base font-bold text-slate-900">Work Schedule</h3>
            <p class="text-sm"><span class="text-slate-500 font-medium">Shift Name:</span> {{ $employee->workSchedule->name ?? 'Default System Shift' }}</p>
            <p class="text-sm"><span class="text-slate-500 font-medium">Hours:</span> {{ $employee->workSchedule ? $employee->workSchedule->start_time . ' - ' . $employee->workSchedule->end_time : '09:00 - 17:00' }}</p>
        </div>
    </div>
</div>
@endsection
