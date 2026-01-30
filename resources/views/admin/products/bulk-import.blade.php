@extends('layouts.admin')

@section('title', 'Bulk Import Products — Admin')

@push('styles')
<style>
    .bulk-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .bulk-header {
        margin-bottom: 30px;
    }

    .bulk-header h1 {
        color: #fff;
        font-size: 2rem;
        margin-bottom: 5px;
    }

    .bulk-header p {
        color: #cbd5e1;
        font-size: 1rem;
    }

    .bulk-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .bulk-card {
        background: rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 25px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        transition: all 0.3s ease;
    }

    .bulk-card:hover {
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(99, 102, 241, 0.5);
        transform: translateY(-5px);
    }

    .bulk-card h2 {
        color: #e2e8f0;
        font-size: 1.3rem;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .bulk-card p {
        color: #cbd5e1;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 15px;
    }

    .code-box {
        background: rgba(0, 0, 0, 0.3);
        padding: 12px;
        border-radius: 6px;
        font-family: 'Courier New', monospace;
        font-size: 0.85rem;
        color: #86efac;
        margin-bottom: 15px;
        max-height: 150px;
        overflow-y: auto;
        border: 1px solid rgba(134, 239, 172, 0.2);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #e2e8f0;
        font-size: 0.9rem;
    }

    .form-group input[type="file"] {
        width: 100%;
        padding: 12px;
        background: rgba(255, 255, 255, 0.08);
        border: 2px dashed rgba(99, 102, 241, 0.5);
        border-radius: 8px;
        color: #e2e8f0;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .form-group input[type="file"]:hover {
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(99, 102, 241, 0.8);
    }

    .btn-submit {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: #fff;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(99, 102, 241, 0.3);
    }

    .alert {
        padding: 15px 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        border: 1px solid;
    }

    .alert-success {
        background: rgba(52, 211, 153, 0.1);
        border-color: rgba(52, 211, 153, 0.5);
        color: #86efac;
    }

    .alert-error {
        background: rgba(239, 68, 68, 0.1);
        border-color: rgba(239, 68, 68, 0.5);
        color: #fca5a5;
    }

    .instructions-card {
        background: rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 25px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        margin-top: 30px;
    }

    .instructions-card h3 {
        color: #e2e8f0;
        margin-bottom: 15px;
        font-size: 1.2rem;
    }

    .instructions-card ol {
        color: #cbd5e1;
        padding-left: 20px;
        line-height: 2;
    }

    .instructions-card li {
        margin-bottom: 8px;
    }

    .back-btn {
        display: inline-block;
        margin-bottom: 20px;
        padding: 10px 20px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 6px;
        color: #cbd5e1;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(99, 102, 241, 0.5);
        color: #e2e8f0;
    }
</style>
@endpush

@section('content')
<div class="bulk-container">
    <a href="{{ route('admin.products.index') }}" class="back-btn">← Back to Products</a>

    <div class="bulk-header">
        <h1>📦 Bulk Import Products</h1>
        <p>Quickly import multiple products, categories, and images in bulk</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error)
                <p>❌ {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="bulk-grid">
        <!-- Product CSV Import -->
        <div class="bulk-card">
            <h2>📊 Import Products from CSV</h2>
            <p>Upload a CSV file to import multiple products. Images should be in <code>public/uploads/bulk/</code> folder.</p>
            
            <div class="code-box">
                <strong>CSV Format:</strong><br>
                name,description,price,category,sizes,stock,image_filename<br><br>
                "T-Shirt","Cotton tshirt",499,Men,"S,M,L,XL",50,tshirt.jpg
            </div>

            <form method="POST" action="{{ route('admin.bulk.import.products') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Select CSV File</label>
                    <input type="file" name="csv_file" accept=".csv,.txt" required>
                </div>
                <button type="submit" class="btn-submit">Import Products</button>
            </form>
        </div>

        <!-- Category CSV Import -->
        <div class="bulk-card">
            <h2>🏷️ Import Categories from CSV</h2>
            <p>Upload a CSV file to import multiple categories at once. Great for setting up your catalog quickly.</p>
            
            <div class="code-box">
                <strong>CSV Format:</strong><br>
                name,description,is_active<br><br>
                "Summer Wear","Summer",1<br>
                "Winter Wear","Winter",1
            </div>

            <form method="POST" action="{{ route('admin.bulk.import.categories') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Select CSV File</label>
                    <input type="file" name="csv_file" accept=".csv,.txt" required>
                </div>
                <button type="submit" class="btn-submit">Import Categories</button>
            </form>
        </div>

        <!-- ZIP Image Upload -->
        <div class="bulk-card">
            <h2>🖼️ Upload Images (ZIP)</h2>
            <p>Upload a ZIP file containing all your product images. They will be extracted to <code>public/uploads/products/</code></p>

            <form method="POST" action="{{ route('admin.bulk.upload.images') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Select ZIP File</label>
                    <input type="file" name="zip_file" accept=".zip" required>
                </div>
                <button type="submit" class="btn-submit">Upload ZIP</button>
            </form>
        </div>
    </div>

    <!-- Instructions -->
    <div class="instructions-card">
        <h3>📖 How to Use</h3>
        <ol>
            <li>Place your product images in: <code style="color: #6366f1;">public/uploads/bulk/</code></li>
            <li>Create CSV files with your data (see format above)</li>
            <li>Upload CSV files using the forms above</li>
            <li>System automatically creates folders and categories</li>
            <li>View imported products in your Products section</li>
        </ol>
    </div>
</div>
@endsection
