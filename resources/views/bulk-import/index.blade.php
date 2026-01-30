<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Import System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        h1 { text-align: center; margin-bottom: 30px; color: #333; }
        .card { background: white; padding: 25px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card h2 { color: #2c3e50; margin-bottom: 10px; font-size: 1.3rem; }
        .card p { color: #7f8c8d; margin-bottom: 15px; line-height: 1.6; }
        .form-group { margin-bottom: 15px; }
        .form-group input[type="file"] { width: 100%; padding: 10px; border: 2px dashed #ddd; border-radius: 5px; cursor: pointer; }
        .btn { background: #3498db; color: white; padding: 12px 30px; border: none; border-radius: 5px; cursor: pointer; font-size: 1rem; font-weight: 500; }
        .btn:hover { background: #2980b9; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .code-box { background: #f8f9fa; padding: 15px; border-radius: 5px; font-family: 'Courier New', monospace; font-size: 13px; overflow-x: auto; margin: 10px 0; }
        .emoji { font-size: 1.5rem; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📦 Bulk Import System</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Product CSV Import -->
        <div class="card">
            <h2><span class="emoji">📊</span> Import Products from CSV</h2>
            <p>Upload a CSV file to import multiple products. Images should be in <code>public/uploads/bulk/</code> folder.</p>
            
            <div class="code-box">
                <strong>CSV Format:</strong><br>
                name,description,price,category,sizes,stock,image_filename<br>
                "T-Shirt","Cotton tshirt",499,Men,"S,M,L,XL",50,tshirt.jpg
            </div>

            <form method="POST" action="{{ route('bulk.import.products') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input type="file" name="csv_file" accept=".csv" required>
                </div>
                <button type="submit" class="btn">Import Products</button>
            </form>
        </div>

        <!-- Category CSV Import -->
        <div class="card">
            <h2><span class="emoji">🏷️</span> Import Categories from CSV</h2>
            <p>Upload a CSV file to import multiple categories at once.</p>
            
            <div class="code-box">
                <strong>CSV Format:</strong><br>
                name,description,is_active<br>
                "Summer Wear","Summer collection",1<br>
                "Winter Wear","Winter collection",1
            </div>

            <form method="POST" action="{{ route('bulk.import.categories') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input type="file" name="csv_file" accept=".csv" required>
                </div>
                <button type="submit" class="btn">Import Categories</button>
            </form>
        </div>

        <!-- ZIP Image Upload -->
        <div class="card">
            <h2><span class="emoji">🖼️</span> Upload Images (ZIP)</h2>
            <p>Upload a ZIP file containing all your product images. They will be extracted to <code>public/uploads/products/</code></p>

            <form method="POST" action="{{ route('bulk.upload.images') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input type="file" name="zip_file" accept=".zip" required>
                </div>
                <button type="submit" class="btn">Upload ZIP</button>
            </form>
        </div>

        <!-- Instructions -->
        <div class="card">
            <h2><span class="emoji">📖</span> How to Use</h2>
            <ol style="line-height: 2; padding-left: 20px;">
                <li>Place your product images in: <code>public/uploads/bulk/</code></li>
                <li>Create CSV file with your data (see format above)</li>
                <li>Upload CSV file using form above</li>
                <li>System automatically creates folders and categories</li>
                <li>Done! ✅</li>
            </ol>
        </div>
    </div>
</body>
</html>
