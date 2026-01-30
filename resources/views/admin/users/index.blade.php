@extends('layouts.admin')

@section('title', 'Users — Admin')

@push('styles')
<style>
.users-table { width: 100%; background: rgba(255, 255, 255, 0.08); border-radius: 12px; overflow: hidden; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.15); }
.users-table th, .users-table td { padding: 15px; text-align: left; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
.users-table th { background: rgba(52, 211, 153, 0.1); font-weight: 600; color: #cbd5e1; }
.users-table td { color: #e2e8f0; }
.btn-delete { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #fff; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-weight: 600; }
.btn-delete:hover { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }
h2 { color: #fff; }
</style>
@endpush

@section('content')
<h2 style="margin-bottom: 25px;">Users</h2>

<table class="users-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Registered</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->mobile ?? '-' }}</td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:30px; color:#cbd5e1;">No users found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
