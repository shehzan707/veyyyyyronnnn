@extends('layouts.app')

@section('title', 'My Account — VEYRON')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<style>
    /* Reset conflicting base styles */
    #accountPage { width: 100% !important; }
    #accountPage .container { width: 100% !important; max-width: 1200px !important; margin: 0 auto !important; padding: 0 20px !important; }
    #accountPage * { box-sizing: border-box; }
    
    #accountPage .account-layout { display: grid !important; grid-template-columns: 280px 1fr !important; gap: 30px !important; padding: 30px 0 !important; }
    #accountPage .account-sidebar { background: #fff !important; border-radius: 12px !important; padding: 20px !important; box-shadow: 0 2px 10px rgba(0,0,0,0.08) !important; height: fit-content !important; }
    #accountPage .account-content { background: #fff !important; border-radius: 12px !important; padding: 30px !important; box-shadow: 0 2px 10px rgba(0,0,0,0.08) !important; }

    #accountPage .user-profile { text-align: center !important; padding-bottom: 20px !important; border-bottom: 1px solid #eee !important; margin-bottom: 20px !important; position: relative !important; }
    #accountPage .user-avatar { width: 100px !important; height: 100px !important; border-radius: 50% !important; background: #222 !important; color: #fff !important; display: flex !important; align-items: center !important; justify-content: center !important; font-size: 2.5rem !important; margin: 0 auto 15px !important; overflow: hidden !important; object-fit: cover !important; position: relative !important; }
    #accountPage .user-avatar img { width: 100% !important; height: 100% !important; object-fit: cover !important; }
    #accountPage .edit-profile-btn { top: -6px !important; position: absolute !important; bottom: 0 !important; right: 97% !important; transform: translateX(50%) !important; background: #222 !important; color: #fff !important; border: none !important; border-radius: 50% !important; width: 32px !important; height: 32px !important; cursor: pointer !important; display: flex !important; align-items: center !important; justify-content: center !important; font-size: 0.9rem !important; transition: 0.3s !important; }
    #accountPage .edit-profile-btn:hover { background: #444 !important; }
    #accountPage .user-name { font-weight: 700 !important; font-size: 1.2rem !important; }
    #accountPage .user-email { color: #888 !important; font-size: 0.9rem !important; }
    
    #accountPage .nav-tabs { display: flex !important; gap: 8px !important; margin-bottom: 20px !important; flex-wrap: wrap !important; }
    #accountPage .nav-tabs button { padding: 12px 16px !important; border: none !important; border-bottom: 2px solid transparent !important; background: none !important; cursor: pointer !important; font-weight: 500 !important; color: #888 !important; transition: 0.3s !important; }
    #accountPage .nav-tabs button.active { color: #222 !important; border-bottom-color: #222 !important; }
    #accountPage .nav-tabs button:hover { color: #222 !important; }
    
    #accountPage .tab-content { display: none !important; }
    #accountPage .tab-content.active { display: block !important; }
    
    #accountPage .form-section { margin-bottom: 30px !important; }
    #accountPage .form-section h3 { margin: 0 0 20px !important; font-size: 1.1rem !important; }
    
    #accountPage .form-row { display: grid !important; grid-template-columns: 1fr 1fr !important; gap: 16px !important; margin-bottom: 16px !important; }
    #accountPage .form-row.full { grid-template-columns: 1fr !important; }
    
    #accountPage .form-group { display: grid !important; gap: 8px !important; }
    #accountPage .form-group label { font-weight: 600 !important; font-size: 0.9rem !important; color: #222 !important; }
    #accountPage .form-group input, #accountPage .form-group select, #accountPage .form-group textarea { padding: 10px 12px !important; border: 1px solid #ddd !important; border-radius: 8px !important; font-family: inherit !important; width: 100% !important; }
    #accountPage .form-group input:focus, #accountPage .form-group select:focus, #accountPage .form-group textarea:focus { outline: none !important; border-color: #222 !important; box-shadow: 0 0 0 3px rgba(34,34,34,0.1) !important; }
    
    #accountPage .btn-submit { padding: 12px 24px !important; background: #222 !important; color: #fff !important; border: none !important; border-radius: 8px !important; cursor: pointer !important; font-weight: 600 !important; transition: 0.3s !important; }
    #accountPage .btn-submit:hover { background: #444 !important; }
    
    #accountPage .btn-secondary { padding: 8px 16px !important; background: #f0f0f0 !important; color: #222 !important; border: none !important; border-radius: 6px !important; cursor: pointer !important; transition: 0.3s !important; }
    #accountPage .btn-secondary:hover { background: #e0e0e0 !important; }
    
    #accountPage .btn-danger { padding: 8px 16px !important; background: #fee2e2 !important; color: #991b1b !important; border: none !important; border-radius: 6px !important; cursor: pointer !important; transition: 0.3s !important; }
    #accountPage .btn-danger:hover { background: #fecaca !important; }
    
    #accountPage .address-card { border: 1px solid #ddd !important; border-radius: 8px !important; padding: 16px !important; margin-bottom: 12px !important; }
    #accountPage .address-card.default { border: 2px solid #222 !important; background: #f9f9f9 !important; }
    #accountPage .address-header { display: flex !important; justify-content: space-between !important; align-items: center !important; margin-bottom: 12px !important; }
    #accountPage .address-badge { background: #222 !important; color: #fff !important; padding: 4px 10px !important; border-radius: 4px !important; font-size: 0.8rem !important; }
    #accountPage .address-content { color: #666 !important; font-size: 0.9rem !important; line-height: 1.6 !important; }
    #accountPage .address-actions { display: flex !important; gap: 8px !important; margin-top: 12px !important; }
    
    #accountPage .orders-list { }
    #accountPage .order-card { border: 1px solid #eee !important; border-radius: 12px !important; padding: 20px !important; margin-bottom: 15px !important; }
    #accountPage .order-header { display: flex !important; justify-content: space-between !important; margin-bottom: 15px !important; }
    #accountPage .order-id { font-weight: 700 !important; }
    #accountPage .order-status { padding: 5px 12px !important; border-radius: 20px !important; font-size: 0.85rem !important; }
    #accountPage .status-pending { background: #fef3c7 !important; color: #92400e !important; }
    #accountPage .status-processing { background: #dbeafe !important; color: #1e40af !important; }
    #accountPage .status-shipped { background: #e0e7ff !important; color: #3730a3 !important; }
    #accountPage .status-delivered { background: #d1fae5 !important; color: #065f46 !important; }
    #accountPage .status-cancelled { background: #fee2e2 !important; color: #991b1b !important; }
    
    #accountPage .alert { padding: 12px 16px !important; border-radius: 8px !important; margin-bottom: 20px !important; }
    #accountPage .alert-success { background: #d1fae5 !important; color: #065f46 !important; }
    #accountPage .alert-error { background: #fee2e2 !important; color: #991b1b !important; }
    
    #accountPage .crop-modal { display: none !important; position: fixed !important; top: 0 !important; left: 0 !important; width: 100% !important; height: 100% !important; background: rgba(0,0,0,0.7) !important; z-index: 1000 !important; align-items: center !important; justify-content: center !important; }
    #accountPage .crop-modal.active { display: flex !important; }
    #accountPage .crop-container { background: #fff !important; border-radius: 12px !important; padding: 20px !important; max-width: 600px !important; width: 90% !important; }
    #accountPage .crop-container h3 { margin: 0 0 16px !important; }
    #accountPage .crop-image { max-width: 100% !important; max-height: 400px !important; }
    #accountPage .crop-controls { display: flex !important; gap: 8px !important; margin-top: 16px !important; justify-content: flex-end !important; }
    #accountPage .crop-controls button { padding: 8px 16px !important; border: none !important; border-radius: 6px !important; cursor: pointer !important; font-weight: 600 !important; transition: 0.3s !important; }
    #accountPage .crop-controls .crop-save { background: #222 !important; color: #fff !important; }
    #accountPage .crop-controls .crop-save:hover { background: #444 !important; }
    #accountPage .crop-controls .crop-cancel { background: #f0f0f0 !important; color: #222 !important; }
    #accountPage .crop-controls .crop-cancel:hover { background: #e0e0e0 !important; }
    #accountPage .profile-toast { position: fixed !important; top: 24px !important; right: 24px !important; padding: 12px 16px !important; border-radius: 10px !important; background: #065f46 !important; color: #fff !important; font-size: 0.95rem !important; font-weight: 600 !important; box-shadow: 0 12px 30px rgba(0,0,0,0.18) !important; opacity: 0 !important; transform: translateY(-8px) !important; pointer-events: none !important; transition: opacity 0.2s ease, transform 0.2s ease !important; z-index: 1200 !important; }
    #accountPage .profile-toast.visible { opacity: 1 !important; transform: translateY(0) !important; }
    #accountPage .profile-toast.error { background: #991b1b !important; }
    
    @media (max-width: 768px) {
        #accountPage .account-layout { grid-template-columns: 1fr !important; }
        #accountPage .form-row { grid-template-columns: 1fr !important; }
        #accountPage .profile-toast { left: 20px !important; right: 20px !important; top: 16px !important; }
    }
</style>
@endpush

@section('content')
<div id="accountPage">
<div class="profile-toast" id="profileToast" role="status" aria-live="polite"></div>
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
                <button type="button" class="edit-profile-btn" onclick="document.getElementById('profileInput').click()" title="Change Profile Picture">
                    <span class="material-icons" style="font-size: 18px;">camera_alt</span>
                </button>
                <input type="file" id="profileInput" accept="image/*" style="display: none;">
                <div class="user-name" id="profileName">{{ $user->first_name }} {{ $user->last_name }}</div>
                <div class="user-email" id="profileEmail">{{ $user->email }}</div>
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

            <div id="addresses" class="tab-content">
                <div class="form-section">
                    <h3>Your Addresses</h3>
                    @if($addresses->count() > 0)
                        @foreach($addresses as $address)
                            <div class="address-card" {{ $address->is_default ? 'style="border: 2px solid #222; background: #f9f9f9;"' : '' }}>
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

            <div id="orders" class="tab-content">
                <h3 style="margin-bottom: 20px;">My Orders</h3>
                @if($orders->count() > 0)
                    <div class="orders-list">
                        @foreach($orders as $order)
                            @php
                                $activeItemsTotal = 0;
                                $orderSubtotal = 0;
                                foreach($order->items as $item) {
                                    $itemTotal = $item->price * $item->quantity;
                                    $orderSubtotal += $itemTotal;
                                    if (($item->item_status ?? 'Placed') !== 'Cancelled') {
                                        $activeItemsTotal += $itemTotal;
                                    }
                                }
                                $platformFee = $order->platform_fee ?? 27;
                                $shippingCost = $order->shipping_cost ?? ($orderSubtotal > 999 ? 0 : 50);
                                $actualTotal = $activeItemsTotal + $platformFee + $shippingCost;
                            @endphp
                            <div class="order-card">
                                <div class="order-header">
                                    <span class="order-id">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                                    <span class="order-status status-{{ $order->order_status }}">{{ ucfirst($order->order_status) }}</span>
                                </div>
                                <div style="display:flex; justify-content:space-between; color:#666; margin-bottom: 12px;">
                                    <span>{{ $order->created_at->format('d M Y, h:i A') }}</span>
                                    <span style="font-weight:700; color:#222;">₹{{ number_format($actualTotal, 2) }}</span>
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

<div class="crop-modal" id="cropModal">
    <div class="crop-container">
        <h3>Adjust Your Profile Picture</h3>
        <p style="color: #666; font-size: 0.9rem; margin-bottom: 16px;">Drag to move, scroll to zoom.</p>
        <img id="cropImage" class="crop-image" src="" alt="Crop">
        <div class="crop-controls">
            <button type="button" class="crop-cancel" onclick="closeCropModal()">Cancel</button>
            <button type="button" class="crop-save" onclick="saveCropImage()">Use Picture</button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
let cropper = null;
let profileToastTimer = null;

function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById(tabName).classList.add('active');
    event.target.classList.add('active');
}

function showProfileToast(message, type = 'success') {
    const toast = document.getElementById('profileToast');
    if (!toast) {
        return;
    }

    toast.textContent = message;
    toast.classList.toggle('error', type === 'error');
    toast.classList.add('visible');

    if (profileToastTimer) {
        clearTimeout(profileToastTimer);
    }

    profileToastTimer = setTimeout(() => {
        toast.classList.remove('visible');
    }, 2000);
}

function updateProfilePreview(imageUrl) {
    if (!imageUrl) {
        return;
    }

    const profilePreview = document.getElementById('profilePreview');
    const profileImage = document.createElement('img');
    profileImage.src = imageUrl;
    profileImage.alt = 'Profile';
    profilePreview.innerHTML = '';
    profilePreview.appendChild(profileImage);
}

function updateProfileSummary(user) {
    if (!user) {
        return;
    }

    const profileName = document.getElementById('profileName');
    const profileEmail = document.getElementById('profileEmail');

    if (profileName) {
        profileName.textContent = [user.first_name, user.last_name].filter(Boolean).join(' ');
    }

    if (profileEmail && user.email) {
        profileEmail.textContent = user.email;
    }
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
            if (!blob) {
                showProfileToast('Unable to process the selected image.', 'error');
                closeCropModal();
                return;
            }

            const profileForm = document.getElementById('profileForm');
            const formData = new FormData(profileForm);
            formData.set('profile_picture', blob, 'profile.png');

            fetch('{{ route("account.updateProfile") }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            }).then(async response => {
                const contentType = response.headers.get('content-type') || '';
                const data = contentType.includes('application/json')
                    ? await response.json()
                    : null;

                if (!response.ok) {
                    const validationMessage = data && data.messages
                        ? Object.values(data.messages).flat().join(' ')
                        : null;

                    throw new Error(validationMessage || data?.error || 'Upload failed.');
                }

                if (!data || !data.success) {
                    throw new Error('Unexpected response from the server.');
                }

                return data;
            }).then(data => {
                updateProfilePreview(data.profile_picture_url);
                updateProfileSummary(data.user);
                showProfileToast(data.message || 'Profile picture updated successfully!');
                closeCropModal();
            }).catch(error => {
                showProfileToast(error.message || 'Unable to update profile picture.', 'error');
                closeCropModal();
            });
        });
    }
}

function editAddressForm(id) {
    alert('Edit functionality - delete and add a new address');
}
</script>
</div><!-- Close #accountPage -->
@endsection
