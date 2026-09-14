@extends('layouts.app')

@section('header', 'Employees Directory')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Employees Directory</h2>
            <p class="text-sm text-slate-500">Manage employee profiles, assignments, and employment status.</p>
        </div>
        <a href="{{ route('admin.employees.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-md transition inline-flex items-center justify-center">
            + Add Employee
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-wrap gap-4 items-center justify-between">
            <form action="{{ route('admin.employees.index') }}" method="GET" class="flex flex-wrap gap-2 w-full sm:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Code, name, or email..." class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                <select name="department_id" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                    <option value="">All Departments</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->id }}" {{ request('department_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded-xl">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-xs tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Employee Code</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Department & Designation</th>
                        <th class="px-6 py-4">Joining Date</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($employees as $emp)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 font-mono font-bold text-indigo-600">{{ $emp->employee_code }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $emp->full_name }}</div>
                                <div class="text-xs text-slate-400">{{ $emp->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-800 font-medium">{{ $emp->department->name ?? 'Unassigned' }}</div>
                                <div class="text-xs text-slate-400">{{ $emp->designation->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-slate-600">{{ $emp->joining_date ? $emp->joining_date->format('Y-m-d') : 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider {{ $emp->employment_status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                    {{ $emp->employment_status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.employees.show', $emp) }}" class="text-xs text-slate-600 hover:underline font-medium">View</a>
                                <a href="{{ route('admin.employees.edit', $emp) }}" class="text-xs text-indigo-600 hover:underline font-medium">Edit</a>
                                <form action="{{ route('admin.employees.toggle-status', $emp) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs text-amber-600 hover:underline font-medium">Toggle</button>
                                </form>
                                <form action="{{ route('admin.employees.destroy', $emp) }}" method="POST" class="inline" onsubmit="return confirm('Delete employee?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-rose-600 hover:underline font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">No employees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $employees->links() }}
        </div>
    </div>
</div>
@endsection
