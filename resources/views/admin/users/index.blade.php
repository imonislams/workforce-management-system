@extends('layouts.app')

@section('header', 'System Users')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">User Management</h2>
            <p class="text-sm text-slate-500">Manage administrator and employee user credentials.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-md transition inline-flex items-center justify-center">
            + Add User
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-xs tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">User Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $u->name }}</td>
                            <td class="px-6 py-4 text-slate-600 font-mono text-xs">{{ $u->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider {{ $u->role === 'admin' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $u->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider {{ $u->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700' }}">
                                    {{ $u->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <form action="{{ route('admin.users.toggle-status', $u) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs text-amber-600 hover:underline font-medium">Toggle</button>
                                </form>
                                <a href="{{ route('admin.users.edit', $u) }}" class="text-xs text-indigo-600 hover:underline font-medium">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
