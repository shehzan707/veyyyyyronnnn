@extends('layouts.admin')

@section('title', 'Users — Admin')

@push('styles')
<style>
.users-table { width: 100%; background: #3a3a3a; border-radius: 12px; overflow: hidden; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
.users-table th, .users-table td { padding: 15px; text-align: left; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
.users-table th { background: #2a2a2a; font-weight: 600; color: #ffffff; }
.users-table td { color: #ffffff; }

/* Toggle Switch Styles */
.toggle-switch { position: relative; display: inline-block; width: 50px; height: 26px; margin-right: 10px; }
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ef4444; transition: 0.3s; border-radius: 26px; }
.toggle-slider:before { position: absolute; content: ""; height: 22px; width: 22px; left: 2px; bottom: 2px; background-color: white; transition: 0.3s; border-radius: 50%; }
input:checked + .toggle-slider { background-color: #10b981; }
input:checked + .toggle-slider:before { transform: translateX(24px); }
.toggle-slider:hover { box-shadow: 0 0 8px rgba(255, 255, 255, 0.3); }

.status-text { font-size: 13px; font-weight: 600; color: #94a3b8; min-width: 70px; display: inline-block; }
input:checked ~ .status-text { color: #10b981; }
.status-text.inactive { color: #ef4444; }

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
            <th>Status</th>
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
                    <div style="display: flex; align-items: center;">
                        <label class="toggle-switch">
                            <input type="checkbox" class="status-toggle" data-user-id="{{ $user->id }}" {{ $user->is_active ? 'checked' : '' }} onchange="toggleUserStatus(event, {{ $user->id }})">
                            <span class="toggle-slider"></span>
                        </label>
                        <span class="status-text {{ !$user->is_active ? 'inactive' : '' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:30px; color:#cbd5e1;">No users found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<script>
let requestInProgress = {}; // Track ongoing requests

function toggleUserStatus(event, userId) {
    const toggle = event.target;
    
    // Prevent duplicate requests
    if (requestInProgress[userId]) {
        toggle.checked = !toggle.checked;
        return;
    }
    
    requestInProgress[userId] = true;
    const statusText = toggle.closest('div').querySelector('.status-text');
    const previousState = !toggle.checked; // The state BEFORE the toggle
    const newState = toggle.checked; // The state AFTER the toggle
    
    console.log('Toggle user', userId, 'from', previousState ? 'Active' : 'Inactive', 'to', newState ? 'Active' : 'Inactive');
    
    // Update UI immediately
    statusText.textContent = newState ? 'Active' : 'Inactive';
    statusText.classList.toggle('inactive', !newState);
    toggle.disabled = true;
    
    // Send request to server
    fetch(`/admin/users/${userId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Server confirmed the new state
            toggle.checked = data.is_active;
            statusText.textContent = data.is_active ? 'Active' : 'Inactive';
            statusText.classList.toggle('inactive', !data.is_active);
            console.log('✓ Status updated:', data.message);
        } else {
            throw new Error('Server returned success: false');
        }
    })
    .catch(error => {
        console.error('Error toggling status:', error);
        // Revert to previous state on error
        toggle.checked = previousState;
        statusText.textContent = previousState ? 'Active' : 'Inactive';
        statusText.classList.toggle('inactive', !previousState);
        alert('Failed to update user status. Please try again.');
    })
    .finally(() => {
        toggle.disabled = false;
        delete requestInProgress[userId];
    });
}
</script>
@endsection
