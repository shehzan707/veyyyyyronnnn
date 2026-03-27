@extends('layouts.admin')

@section('title', 'Order Details — Admin')

@push('styles')
<style>
.order-card { background: #3a3a3a; border-radius: 12px; padding: 25px; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); margin-bottom: 25px; }
.order-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
.order-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
.info-group { margin-bottom: 15px; }
.info-label { color: #ffffff; font-size: 0.9rem; margin-bottom: 5px; }
.info-value { font-weight: 600; color: #ffffff; }
.items-table { width: 100%; margin-top: 20px; background: #3a3a3a; border-radius: 8px; overflow: hidden; border-collapse: collapse; }
.items-table th, .items-table td { padding: 14px 12px; text-align: left; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
.items-table th { color: #ffffff; font-weight: 600; background: #2a2a2a; }
.items-table tbody tr { color: #ffffff; background-color: #3a3a3a; transition: background-color 0.2s ease; }
.items-table tbody tr:hover { background-color: #323232; }
.items-table tbody tr.cancelled { opacity: 0.7; background-color: #3a3a3a; }
.items-table tbody tr.reason-row { background-color: #323232; color: #e0e0e0; }
.items-table td { color: #ffffff; }
.items-table tfoot tr { background: #2a2a2a; color: #ffffff; }
.items-table tfoot td { color: #ffffff; }
.status-form { display: flex; gap: 10px; align-items: center; }
.status-form select { padding: 10px; background: #424242; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 8px; color: #ffffff; }
.status-form button { background: #000000; color: #fff; padding: 10px 20px; border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 8px; cursor: pointer; transition: all 0.3s ease; font-weight: 600; }
.status-form button:hover { background: #1a1a1a; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5); }
h2 { color: #fff; }
h4 { color: #fff; }
.back-link { color: #ffffff; text-decoration: none; font-weight: 600; transition: all 0.3s ease; }
.back-link:hover { color: #e0e0e0; }

.confirm-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 10000; align-items: center; justify-content: center; }
.confirm-modal.active { display: flex; }
.confirm-modal-content { background: #2a2a2a; border-radius: 12px; padding: 30px; max-width: 450px; width: 90%; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8); border: 1px solid rgba(255, 255, 255, 0.1); animation: slideUp 0.3s ease-out; }
.confirm-modal-header { font-size: 18px; font-weight: 700; color: #ffffff; margin-bottom: 16px; display: flex; align-items: center; gap: 12px; }
.confirm-modal-header::before { content: '⚠️'; font-size: 24px; }
.confirm-modal-body { font-size: 15px; color: #e0e0e0; margin-bottom: 24px; line-height: 1.6; }
.confirm-modal-footer { display: flex; gap: 12px; justify-content: flex-end; }
.confirm-modal-footer button { padding: 10px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px; transition: all 0.3s ease; }
.confirm-modal-cancel { background: #424242; color: #ffffff; }
.confirm-modal-cancel:hover { background: #525252; }
.confirm-modal-confirm { background: #dc3545; color: #ffffff; }
.confirm-modal-confirm:hover { background: #c82333; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4); }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
@endpush

@section('content')
<h2 style="margin-bottom: 25px;">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h2>

<div class="order-card">
    <div class="order-header">
        <div>
            <span style="color:#ffffff;">Order Date:</span>
            <strong style="color:#ffffff;">{{ $order->created_at->format('d M Y, h:i A') }}</strong>
        </div>
        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="status-form">
            @csrf
            <select name="order_status">
                <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="out_for_delivery" {{ $order->order_status == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit">Update Status</button>
        </form>
    </div>

    <div class="order-grid">
        <div>
            <h4 style="margin-bottom: 15px;">Customer Information</h4>
            <div class="info-group">
                <div class="info-label">Name</div>
                <div class="info-value">{{ $order->name }}</div>
            </div>
            <div class="info-group">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $order->email }}</div>
            </div>
            <div class="info-group">
                <div class="info-label">Mobile</div>
                <div class="info-value">{{ $order->mobile }}</div>
            </div>
        </div>
        <div>
            <h4 style="margin-bottom: 15px;">Shipping Address</h4>
            <div class="info-group">
                <div class="info-value">{{ $order->address }}</div>
                <div class="info-value">{{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</div>
            </div>
            <div class="info-group">
                <div class="info-label">Payment Method</div>
                <div class="info-value">{{ $order->payment_method == 'cod' ? 'Cash on Delivery' : 'Online Payment' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="order-card">
    <h4>Order Items</h4>
    <table class="items-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Size</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $activeTotal = 0;
                $cancelledTotal = 0;
            @endphp
            @foreach($order->items as $item)
                @php
                    $isCancelled = ($item->item_status ?? 'Placed') === 'Cancelled';
                    $itemTotal = $item->price * $item->quantity;
                    if ($isCancelled) {
                        $cancelledTotal += $itemTotal;
                    } else {
                        $activeTotal += $itemTotal;
                    }
                @endphp
                <tr class="{{ $isCancelled ? 'cancelled' : '' }}">
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->size ?? '-' }}</td>
                    <td>₹{{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($itemTotal, 2) }}</td>
                    <td>
                        @if($isCancelled)
                            <span style="color: #dc3545; font-weight: 600;">🚫 Cancelled</span>
                        @else
                            <span style="color: #28a745; font-weight: 600;">✓ Active</span>
                        @endif
                    </td>
                    <td>
                        @if(!$isCancelled)
                            <button type="button" onclick="adminCancelItem({{ $item->id }})" style="background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='#c82333'" onmouseout="this.style.backgroundColor='#dc3545'">Cancel</button>
                        @else
                            <span style="font-size: 12px; color: #888;">N/A</span>
                        @endif
                    </td>
                </tr>
                @if($isCancelled && $item->cancel_reason)
                    <tr class="reason-row">
                        <td colspan="7" style="padding: 8px 12px; font-size: 13px;">
                            <strong>Cancellation Reason:</strong> {{ $item->cancel_reason }}
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
        <tfoot>
            @if($cancelledTotal > 0)
            <tr>
                <td colspan="5" style="text-align:right; font-weight:700; color: #dc3545;">Cancelled Total:</td>
                <td style="font-weight:700; font-size:1.1rem; color: #dc3545;">₹{{ number_format($cancelledTotal, 2) }}</td>
                <td></td>
            </tr>
            @endif
            <tr>
                <td colspan="5" style="text-align:right; font-weight:700;">Grand Total:</td>
                <td style="font-weight:700; font-size:1.2rem;">₹{{ number_format($activeTotal, 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

<a href="{{ route('admin.orders.index') }}" class="back-link">← Back to Orders</a>

<!-- Confirmation Modal -->
<div id="confirmModal" class="confirm-modal">
    <div class="confirm-modal-content">
        <div class="confirm-modal-header">Cancel Item</div>
        <div class="confirm-modal-body">
            Are you sure you want to cancel this item? The customer will be notified and refund will be initiated if applicable.
        </div>
        <div class="confirm-modal-footer">
            <button type="button" class="confirm-modal-cancel" onclick="closeConfirmModal()">Cancel</button>
            <button type="button" class="confirm-modal-confirm" onclick="confirmAdminCancel()">Yes, Cancel Item</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentCancelItemId = null;

function adminCancelItem(itemId) {
    currentCancelItemId = itemId;
    document.getElementById('confirmModal').classList.add('active');
}

function closeConfirmModal() {
    currentCancelItemId = null;
    document.getElementById('confirmModal').classList.remove('active');
}

function confirmAdminCancel() {
    if (!currentCancelItemId) return;
    
    const itemId = currentCancelItemId;
    closeConfirmModal();
    
    const btn = event.target;
    btn.disabled = true;
    btn.textContent = 'Cancelling...';
    
    fetch(`/admin/orders/item/${itemId}/cancel`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Show success and reload
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to cancel item'));
            btn.disabled = false;
            btn.textContent = 'Yes, Cancel Item';
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('An error occurred while cancelling the item.');
        btn.disabled = false;
        btn.textContent = 'Yes, Cancel Item';
    });
}

// Close modal when clicking outside
document.addEventListener('click', (e) => {
    const modal = document.getElementById('confirmModal');
    if (e.target === modal) {
        closeConfirmModal();
    }
});
</script>
@endpush
