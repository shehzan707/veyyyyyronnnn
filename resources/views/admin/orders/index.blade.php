@extends('layouts.admin')

@section('title', 'Orders — Admin')

@push('styles')
<style>
.orders-table { width: 100%; background: #3a3a3a; border-radius: 12px; overflow: hidden; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
.orders-table th, .orders-table td { padding: 15px; text-align: left; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
.orders-table th { background: #2a2a2a; font-weight: 600; color: #ffffff; }
.orders-table td { color: #ffffff; }
.status-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
.status-pending { background: #000000; color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.3); }
.status-processing { background: #000000; color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.3); }
.status-shipped { background: #000000; color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.3); }
.status-delivered { background: #000000; color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.3); }
.status-cancelled { background: #000000; color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.3); }
.view-btn { color: #ffffff; text-decoration: none; font-weight: 600; transition: all 0.3s ease; }
.view-btn:hover { color: #e0e0e0; text-decoration: underline; }
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
