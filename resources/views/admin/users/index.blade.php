@extends('layouts.admin')

@section('title', 'Users — Admin')

@push('styles')
<style>
.users-table { width: 100%; background: #3a3a3a; border-radius: 12px; overflow: hidden; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
.users-table th, .users-table td { padding: 15px; text-align: left; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
.users-table th { background: #2a2a2a; font-weight: 600; color: #ffffff; }
.users-table td { color: #ffffff; }
.btn-delete { background: #000000; color: #fff; border: 1px solid rgba(255, 255, 255, 0.3); padding: 8px 15px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-weight: 600; }
.btn-delete:hover { background: #1a1a1a; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5); }
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
