@extends('layouts.app')

@section('header', 'Designations')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Designations Management</h2>
            <p class="text-sm text-slate-500">Manage job titles and designations.</p>
        </div>
        <a href="{{ route('admin.designations.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-md transition inline-flex items-center justify-center">
            + Add Designation
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-wrap gap-4 items-center justify-between">
            <form action="{{ route('admin.designations.index') }}" method="GET" class="flex items-center space-x-2 w-full sm:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search designations..." class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                <button type="submit" class="px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded-xl">Search</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-xs tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Designation Name</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4">Employees</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($designations as $desig)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $desig->name }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $desig->description ?: 'N/A' }}</td>
                            <td class="px-6 py-4"><span class="px-3 py-1 bg-slate-100 rounded-full font-semibold text-slate-700 text-xs">{{ $desig->employees_count }} employees</span></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider {{ $desig->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $desig->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <form action="{{ route('admin.designations.toggle-status', $desig) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs text-amber-600 hover:underline font-medium">Toggle</button>
                                </form>
                                <a href="{{ route('admin.designations.edit', $desig) }}" class="text-xs text-indigo-600 hover:underline font-medium">Edit</a>
                                <form action="{{ route('admin.designations.destroy', $desig) }}" method="POST" class="inline" onsubmit="return confirm('Delete designation?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-rose-600 hover:underline font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">No designations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $designations->links() }}
        </div>
    </div>
</div>
@endsection
