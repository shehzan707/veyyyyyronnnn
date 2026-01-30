@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 800px; margin: 0 auto;">
    <h2 style="margin-bottom: 24px;">Order Details</h2>
    @if(isset($order))
        <div style="border:1px solid #ddd; padding:20px; border-radius:8px; margin-bottom:24px; background:#fafafa;">
            <div style="font-size:18px; font-weight:bold; margin-bottom:8px;">Order #{{ $order->id }}</div>
            <div style="margin-bottom:4px;"><strong>Status:</strong> {{ $order->order_status ?? 'N/A' }}</div>
            <div style="margin-bottom:4px;"><strong>Placed on:</strong> {{ $order->created_at->format('d M Y, H:i') }}</div>
            <div><strong>Total:</strong> ₹{{ number_format($order->total_amount ?? ($order->items->sum(function($i){return $i->price * $i->quantity;})), 0) }}</div>
        </div>
        <h4 style="margin-bottom:16px;">Products</h4>
        <div style="overflow-x:auto; min-width: 700px;">
        <table style="min-width:700px; width:100%; border-collapse:separate; border-spacing:0; background:#fff; box-shadow:0 2px 8px #eee; border-radius:10px;">
            <thead>
                <tr style="background:linear-gradient(90deg,#f8fafc,#e2e8f0);">
                    <th style="padding:14px 10px; border-bottom:2px solid #e2e8f0; text-align:left;">Image</th>
                    <th style="padding:14px 10px; border-bottom:2px solid #e2e8f0; text-align:left;">Product</th>
                    <th style="padding:14px 10px; border-bottom:2px solid #e2e8f0; text-align:right;">Quantity</th>
                    <th style="padding:14px 10px; border-bottom:2px solid #e2e8f0; text-align:right;">Price</th>
                    <th style="padding:14px 10px; border-bottom:2px solid #e2e8f0; text-align:right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr style="border-bottom:1px solid #f1f1f1;">
                        <td style="padding:12px 10px; text-align:left;">
                            @php
                                $img = null;
                                if (isset($item->product->image) && $item->product->image) {
                                    $decoded = json_decode($item->product->image, true);
                                    if (is_array($decoded)) {
                                        $img = $decoded[0] ?? null;
                                    } else {
                                        $img = $item->product->image;
                                    }
                                }
                                // fallback placeholder if no image
                                $imgUrl = $img ? asset('uploads/' . ltrim($img, '/')) : 'https://via.placeholder.com/60x60?text=No+Image';
                            @endphp
                            <img src="{{ $imgUrl }}" alt="{{ $item->product->name ?? $item->product_name }}" style="width:60px; height:60px; object-fit:cover; border-radius:8px; box-shadow:0 1px 4px #eee; background:#f8f8f8;">
                        </td>
                        <td style="padding:12px 10px;">{{ $item->product->name ?? $item->product_name ?? 'N/A' }}</td>
                        <td style="padding:12px 10px; text-align:right;">{{ $item->quantity }}</td>
                        <td style="padding:12px 10px; text-align:right;">₹{{ number_format($item->price, 0) }}</td>
                        <td style="padding:12px 10px; text-align:right;">₹{{ number_format($item->price * $item->quantity, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @else
        <div class="alert alert-danger">Order not found.</div>
    @endif
</div>
@endsection
