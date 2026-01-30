@extends('layouts.admin')

@section('title', 'Orders — Admin')

@push('styles')
<style>
.orders-table { width: 100%; background: rgba(255, 255, 255, 0.08); border-radius: 12px; overflow: hidden; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.15); }
.orders-table th, .orders-table td { padding: 15px; text-align: left; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
.orders-table th { background: rgba(52, 211, 153, 0.1); font-weight: 600; color: #cbd5e1; }
.orders-table td { color: #e2e8f0; }
.status-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
.status-pending { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }
.status-processing { background: rgba(59, 130, 246, 0.2); color: #93c5fd; }
.status-shipped { background: rgba(99, 102, 241, 0.2); color: #c4b5fd; }
.status-delivered { background: rgba(52, 211, 153, 0.2); color: #86efac; }
.status-cancelled { background: rgba(239, 68, 68, 0.2); color: #fca5a5; }
.view-btn { color: #34d399; text-decoration: none; font-weight: 600; transition: all 0.3s ease; }
.view-btn:hover { color: #86efac; text-decoration: underline; }
h2 { color: #fff; }
</style>
@endpush

@section('content')
<h2 style="margin-bottom: 25px;">Orders</h2>

<table class="orders-table">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Total</th>
            <th>Payment</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
            <tr>
                <td>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $order->name }}</td>
                <td>{{ $order->email }}</td>
                <td>₹{{ number_format($order->total_amount, 2) }}</td>
                <td>{{ strtoupper($order->payment_method) }}</td>
                <td><span class="status-badge status-{{ $order->order_status }}">{{ ucfirst($order->order_status) }}</span></td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td><a href="{{ route('admin.orders.show', $order->id) }}" class="view-btn">View</a></td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="text-align:center; padding:30px; color:#cbd5e1;">No orders yet.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
