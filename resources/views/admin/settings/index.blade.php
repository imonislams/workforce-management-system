@extends('layouts.app')

@section('header', 'System Settings')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl border border-slate-200/80 p-8 shadow-sm space-y-6">
    <h2 class="text-xl font-bold text-slate-900">System & Company Settings</h2>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        <div class="border-b border-slate-100 pb-6 space-y-4">
            <h3 class="text-base font-bold text-slate-800">Company Information</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Company Name</label>
                    <input type="text" name="company_name" value="{{ $settings['company_name'] ?? 'Workforce Management System' }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Company Email</label>
                    <input type="email" name="company_email" value="{{ $settings['company_email'] ?? 'admin@example.com' }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Phone</label>
                <input type="text" name="company_phone" value="{{ $settings['company_phone'] ?? '+1 555-0199' }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
            </div>
        </div>

        <div class="border-b border-slate-100 pb-6 space-y-4">
            <h3 class="text-base font-bold text-slate-800">Attendance Defaults</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Office Start Time</label>
                    <input type="text" name="office_start_time" value="{{ $settings['office_start_time'] ?? '09:00' }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Office End Time</label>
                    <input type="text" name="office_end_time" value="{{ $settings['office_end_time'] ?? '17:00' }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Grace Period (Mins)</label>
                    <input type="number" name="grace_period" value="{{ $settings['grace_period'] ?? '15' }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-2">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-semibold text-sm rounded-xl hover:bg-indigo-500 shadow">Save Settings</button>
        </div>
    </form>
</div>
@endsection
