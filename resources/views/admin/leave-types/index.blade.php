@extends('layouts.app')

@section('header', 'Leave Types')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Leave Types Configuration</h2>
            <p class="text-sm text-slate-500">Manage available leave categories and day allowances.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm h-fit">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Add Leave Type</h3>
            <form action="{{ route('admin.leave-types.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Type Name *</label>
                    <input type="text" name="name" required placeholder="Sick Leave" class="w-full px-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Code *</label>
                    <input type="text" name="code" required placeholder="SL" class="w-full px-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-sm uppercase font-mono focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Max Days / Year *</label>
                    <input type="number" name="max_days" value="14" required class="w-full px-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Status *</label>
                    <select name="status" class="w-full px-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <button type="submit" class="w-full py-2.5 bg-indigo-600 text-white font-semibold text-sm rounded-xl hover:bg-indigo-500 shadow">Save Leave Type</button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-xs tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Code</th>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Max Days</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($leaveTypes as $lt)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 font-mono font-bold text-indigo-600">{{ $lt->code }}</td>
                                <td class="px-6 py-4 font-bold text-slate-900">{{ $lt->name }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ $lt->max_days }} days</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider {{ $lt->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $lt->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.leave-types.destroy', $lt) }}" method="POST" class="inline" onsubmit="return confirm('Delete leave type?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-rose-600 hover:underline font-bold">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400">No leave types defined.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $leaveTypes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
