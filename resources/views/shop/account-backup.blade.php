@extends('layouts.app')

@section('title', 'My Account — VEYRON')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<style>
    .account-layout { display: grid; grid-template-columns: 280px 1fr; gap: 30px; padding: 30px 0; }
    .account-sidebar { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); height: fit-content; }
    .account-content { background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }

    .user-profile { text-align: center; padding-bottom: 20px; border-bottom: 1px solid #eee; margin-bottom: 20px; position: relative; }
    .user-avatar { width: 100px; height: 100px; border-radius: 50%; background: #222; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 15px; overflow: hidden; object-fit: cover; position: relative; }
    .user-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .edit-profile-btn {top: -6px; position: absolute; bottom: 0; right: 97%; transform: translateX(50%); background: #222; color: #fff; border: none; border-radius: 50%; width: 32px; height: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; transition: 0.3s; }
    .edit-profile-btn:hover { background: #444; }
    .user-name { font-weight: 700; font-size: 1.2rem; }
    .user-email { color: #888; font-size: 0.9rem; }

    .nav-tabs { display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap; }
    .nav-tabs button { padding: 12px 16px; border: none; border-bottom: 2px solid transparent; background: none; cursor: pointer; font-weight: 500; color: #888; transition: 0.3s; }
    .nav-tabs button.active { color: #222; border-bottom-color: #222; }
    .nav-tabs button:hover { color: #222; }

    .tab-content { display: none; }
    .tab-content.active { display: block; }

    .form-section { margin-bottom: 30px; }
    .form-section h3 { margin: 0 0 20px; font-size: 1.1rem; }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
    .form-row.full { grid-template-columns: 1fr; }

    .form-group { display: grid; gap: 8px; }
    .form-group label { font-weight: 600; font-size: 0.9rem; color: #222; }
    .form-group input, .form-group select, .form-group textarea { padding: 10px 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #222; box-shadow: 0 0 0 3px rgba(34,34,34,0.1); }

    .btn-submit { padding: 12px 24px; background: #222; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: 0.3s; }
    .btn-submit:hover { background: #444; }

    .btn-secondary { padding: 8px 16px; background: #f0f0f0; color: #222; border: none; border-radius: 6px; cursor: pointer; transition: 0.3s; }
    .btn-secondary:hover { background: #e0e0e0; }

    .btn-danger { padding: 8px 16px; background: #fee2e2; color: #991b1b; border: none; border-radius: 6px; cursor: pointer; transition: 0.3s; }
    .btn-danger:hover { background: #fecaca; }

    .address-card { border: 1px solid #ddd; border-radius: 8px; padding: 16px; margin-bottom: 12px; }
    .address-card.default { border: 2px solid #222; background: #f9f9f9; }
    .address-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
    .address-badge { background: #222; color: #fff; padding: 4px 10px; border-radius: 4px; font-size: 0.8rem; }
    .address-content { color: #666; font-size: 0.9rem; line-height: 1.6; }
    .address-actions { display: flex; gap: 8px; margin-top: 12px; }

    .orders-list { }
    .order-card { border: 1px solid #eee; border-radius: 12px; padding: 20px; margin-bottom: 15px; }
    .order-header { display: flex; justify-content: space-between; margin-bottom: 15px; }
    .order-id { font-weight: 700; }
    .order-status { padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-processing { background: #dbeafe; color: #1e40af; }
    .status-shipped { background: #e0e7ff; color: #3730a3; }
    .status-delivered { background: #d1fae5; color: #065f46; }
    .status-cancelled { background: #fee2e2; color: #991b1b; }

    .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; }
    .alert-success { background: #d1fae5; color: #065f46; }
    .alert-error { background: #fee2e2; color: #991b1b; }

    /* Cropper Modal */
    .crop-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center; }
    .crop-modal.active { display: flex; }
    .crop-container { background: #fff; border-radius: 12px; padding: 20px; max-width: 600px; width: 90%; }
    .crop-container h3 { margin: 0 0 16px; }
    .crop-image { max-width: 100%; max-height: 400px; }
    .crop-controls { display: flex; gap: 8px; margin-top: 16px; justify-content: flex-end; }
    .crop-controls button { padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; transition: 0.3s; }
    .crop-controls .crop-save { background: #222; color: #fff; }
    .crop-controls .crop-save:hover { background: #444; }
    .crop-controls .crop-cancel { background: #f0f0f0; color: #222; }
    .crop-controls .crop-cancel:hover { background: #e0e0e0; }

    @media (max-width: 768px) {
        .account-layout { grid-template-columns: 1fr; }
        .form-row { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="account-layout">
        <aside class="account-sidebar">
            <div class="user-profile">
                <div class="user-avatar" id="profilePreview">
                    @if($user->profile_picture)
                        <img src="{{ asset('uploads/profiles/' . $user->profile_picture) }}" alt="Profile">
                    @else
                        <span>{{ strtoupper(substr($user->first_name, 0, 1)) }}</span>
                    @endif
                </div>
                <button type="button" class="edit-profile-btn" onclick="document.getElementById('profileInput').click()" title="{{ $user->profile_picture ? 'Change' : 'Upload' }} Profile Picture">
                    <span class="material-icons" style="font-size: 18px;">camera_alt</span>
                </button>
                <input type="file" id="profileInput" accept="image/*" style="display: none;">
                <div class="user-name">{{ $user->first_name }} {{ $user->last_name }}</div>
                <div class="user-email">{{ $user->email }}</div>
            </div>
            <nav>
                <a href="{{ route('account.index') }}" style="display: block; padding: 12px 0; text-decoration: none; border-bottom: 1px solid #eee; color: #222;">My Account</a>
                <a href="{{ route('wishlist.index') }}" style="display: block; padding: 12px 0; text-decoration: none; border-bottom: 1px solid #eee; color: #666;">Wishlist</a>
                <a href="{{ route('logout') }}" style="display: block; padding: 12px 0; text-decoration: none; color: #666;">Logout</a>
            </nav>
        </aside>

        <div class="account-content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="nav-tabs">
                <button class="tab-btn active" onclick="switchTab('profile')">Profile Info</button>
                <button class="tab-btn" onclick="switchTab('addresses')">Addresses</button>
                <button class="tab-btn" onclick="switchTab('orders')">My Orders</button>
            </div>

            <!-- Profile Tab -->
            <div id="profile" class="tab-content active">
                <div class="form-section">
                    <h3>Edit Profile Information</h3>
                    <form method="POST" action="{{ route('account.updateProfile') }}" enctype="multipart/form-data" id="profileForm">
                        @csrf

                        <div class="form-row">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="form-group">
                                <label>Mobile Number</label>
                                <input type="tel" name="mobile" value="{{ old('mobile', $user->mobile) }}" maxlength="10" pattern="[0-9]{10}" required>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">Save Changes</button>
                    </form>
                </div>
            </div>

            <!-- Addresses Tab -->
            <div id="addresses" class="tab-content">
                <div class="form-section">
                    <h3>Your Addresses</h3>
                    
                    @if($addresses->count() > 0)
                        @foreach($addresses as $address)
                            <div class="address-card @if($address->is_default) default @endif">
                                <div class="address-header">
                                    <strong>{{ $address->name }}</strong>
                                    @if($address->is_default)
                                        <span class="address-badge">Default</span>
                                    @endif
                                </div>
                                <div class="address-content">
                                    <p>{{ $address->address_line_1 }}@if($address->address_line_2), {{ $address->address_line_2 }}@endif</p>
                                    <p>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                    <p>{{ $address->country }} | {{ $address->phone }}</p>
                                </div>
                                <div class="address-actions">
                                    <button class="btn-secondary" type="button" onclick="editAddressForm({{ $address->id }})">Edit</button>
                                    <form method="POST" action="{{ route('account.deleteAddress', $address->id) }}" style="display: inline;" onsubmit="return confirm('Delete this address?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p style="color: #888; text-align: center; padding: 20px;">No addresses added yet.</p>
                    @endif
                </div>

                <div class="form-section">
                    <h3>Add New Address</h3>
                    <form method="POST" action="{{ route('account.addAddress') }}">
                        @csrf
                        
                        <div class="form-row full">
                            <div class="form-group">
                                <label>Address Line 1</label>
                                <input type="text" name="address_line_1" required>
                            </div>
                        </div>

                        <div class="form-row full">
                            <div class="form-group">
                                <label>Address Line 2 (Optional)</label>
                                <input type="text" name="address_line_2">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" name="city" required>
                            </div>
                            <div class="form-group">
                                <label>State</label>
                                <input type="text" name="state" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Postal Code</label>
                                <input type="text" name="postal_code" required>
                            </div>
                            <div class="form-group">
                                <label>Country</label>
                                <input type="text" name="country" value="India" required>
                            </div>
                        </div>

                        <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" id="defaultAddress" name="is_default" value="1">
                            <label for="defaultAddress" style="margin: 0;">Set as default address</label>
                        </div>

                        <button type="submit" class="btn-submit">Add Address</button>
                    </form>
                </div>
            </div>

            <!-- Orders Tab -->
            <div id="orders" class="tab-content">
                <h3 style="margin-bottom: 20px;">My Orders</h3>

                @if($orders->count() > 0)
                    <div class="orders-list">
                        @foreach($orders as $order)
                            <div class="order-card">
                                <div class="order-header">
                                    <span class="order-id">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                                    <span class="order-status status-{{ $order->order_status }}">{{ ucfirst($order->order_status) }}</span>
                                </div>
                                <div style="display:flex; justify-content:space-between; color:#666; margin-bottom: 12px;">
                                    <span>{{ $order->created_at->format('d M Y, h:i A') }}</span>
                                    <span style="font-weight:700; color:#222;">₹{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                                <a href="{{ route('account.order.view', $order->id) }}" style="display:inline-block; color:#222; text-decoration:underline;">View Details →</a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align:center; padding:50px;">
                        <p style="color:#888;">You haven't placed any orders yet.</p>
                        <a href="{{ route('products.index') }}" style="color:#222; text-decoration:underline;">Start Shopping</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Crop Modal -->
<div class="crop-modal" id="cropModal">
    <div class="crop-container">
        <h3>Adjust Your Profile Picture</h3>
        <p style="color: #666; font-size: 0.9rem; margin-bottom: 16px;">Drag to move, scroll to zoom. Select the part you want to use for your circular profile picture.</p>
        <img id="cropImage" class="crop-image" src="" alt="Crop">
        <div class="crop-controls">
            <button type="button" class="crop-cancel" onclick="closeCropModal()">Cancel</button>
            <button type="button" class="crop-save" onclick="saveCropImage()">Use This Picture</button>
            
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
let cropper = null;

function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    
    document.getElementById(tabName).classList.add('active');
    event.target.classList.add('active');
}

document.getElementById('profileInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const modal = document.getElementById('cropModal');
            const cropImage = document.getElementById('cropImage');
            cropImage.src = event.target.result;
            modal.classList.add('active');
            
            setTimeout(() => {
                if (cropper) {
                    cropper.destroy();
                }
                cropper = new Cropper(cropImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 0.8,
                    responsive: true,
                    restore: true,
                    guides: true,
                    center: true,
                    highlight: true,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: true,
                });
            }, 100);
        };
        reader.readAsDataURL(file);
    }
});

function closeCropModal() {
    document.getElementById('cropModal').classList.remove('active');
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    document.getElementById('profileInput').value = '';
}

function saveCropImage() {
    if (cropper) {
        const canvas = cropper.getCroppedCanvas({
            maxWidth: 400,
            maxHeight: 400,
            fillColor: '#fff',
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });
        
        canvas.toBlob(function(blob) {
            const formData = new FormData();
            const user = {
                first_name: '{{ Auth::user()->first_name }}',
                last_name: '{{ Auth::user()->last_name }}',
                email: '{{ Auth::user()->email }}',
                mobile: '{{ Auth::user()->mobile }}'
            };
            
            formData.append('profile_picture', blob, 'profile.png');
            formData.append('first_name', user.first_name);
            formData.append('last_name', user.last_name);
            formData.append('email', user.email);
            formData.append('mobile', user.mobile);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            
            fetch('{{ route("account.updateProfile") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error('Server error: ' + response.status + ' - ' + text.substring(0, 100));
                    });
                }
                return response.json();
            })
            .then(data => {
                closeCropModal();
                window.location.reload();
            })
            .catch(error => {
                alert('Error: ' + error.message);
                closeCropModal();
                console.error('Upload error:', error);
            });
        });
    }
}

function editAddressForm(id) {
    alert('Edit functionality - you can delete and add a new address');
}
</script>
@endsection
