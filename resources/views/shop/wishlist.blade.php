@extends('layouts.app')

@section('title', 'Wishlist — VEYRON')

@push('styles')
<style>
table.wishlist-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin: 30px 0;
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.07);
}
table.wishlist-table th, table.wishlist-table td {
    padding: 18px 14px;
    text-align: left;
    font-size: 1rem;
}
table.wishlist-table th {
    background: #f7f7f7;
    font-weight: 700;
    color: #222;
    border-bottom: 1px solid #eee;
}
table.wishlist-table tbody tr {
    transition: background 0.2s;
}
table.wishlist-table tbody tr:hover {
    background: #f8f8f8;
}
table.wishlist-table td img {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}
.wishlist-remove-btn {
    background: none;
    border: none;
    color: #dc2626;
    font-size: 1.5rem;
    cursor: pointer;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}
.wishlist-remove-btn:hover {
    background: #f5f5f5;
}
.empty-wishlist { text-align: center; padding: 80px; }
.empty-wishlist a { color: #222; text-decoration: underline; }
</style>
@endpush

@section('content')
<div class="container">
    <h1 style="margin-bottom: 10px;"></h1>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:12px; border-radius:8px; margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(count($wishlistItems) > 0)
        <table class="wishlist-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Size</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                @foreach($wishlistItems as $item)
                <tr>
                    <td>
                        <a href="{{ route('products.show', $item->product->id) }}" style="display:inline-block;">
                            <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}">
                        </a>
                    </td>
                    <td>{{ $item->product->name }}</td>
                    <td>₹{{ number_format($item->product->price, 2) }}</td>
                    <td>{{ ucwords(str_replace('-', ' ', $item->product->category)) }}</td>
                    <td>{{ $item->size ?? '-' }}</td>
                    <td>
                        <form action="{{ route('wishlist.remove') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                            <button type="submit" class="wishlist-remove-btn" title="Remove from wishlist">&times;</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-wishlist">
            <h2>Your wishlist is empty</h2>
            <p style="color:#888; margin:20px 0;">Save items you love for later.</p>
            <a href="{{ route('products.index') }}">Continue Shopping</a>
        </div>
    @endif
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</div>
@endsection
