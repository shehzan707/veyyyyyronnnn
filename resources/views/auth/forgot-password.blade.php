<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Veyron</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px;
        }

        .logo {
            height: 60px;
            margin-bottom: 2rem;
            border-radius: 4px;
        }

        .container {
            background: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            animation: fadeIn 0.3s ease;
        }

        .header {
            font-size: 28px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 8px;
            color: #1a1a1a;
            letter-spacing: -0.5px;
        }

        .subheader {
            font-size: 14px;
            text-align: center;
            margin-bottom: 30px;
            color: #666;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #1a1a1a;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d0d0d0;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #2c2f4a;
            box-shadow: 0 0 0 3px rgba(44, 47, 74, 0.05);
        }

        .btn-primary {
            width: 100%;
            padding: 11px 16px;
            background: #2c2f4a;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 8px;
        }

        .btn-primary:hover:not(:disabled) {
            background: #1a1d2e;
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .error-message {
            background: #fee;
            color: #c00;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: none;
            font-size: 13px;
            border-left: 3px solid #c00;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #2c2f4a;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .back-link a:hover {
            color: #1a1d2e;
        }

        .loading {
            display: none;
            text-align: center;
            margin-top: 12px;
        }

        .spinner {
            border: 2px solid #f0f0f0;
            border-top: 2px solid #2c2f4a;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            animation: spin 0.8s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .help-text {
            font-size: 12px;
            color: #999;
            margin-top: 6px;
        }
    </style>
</head>
<body>
    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo" onerror="this.style.display='none'">

    <div class="container">
        <h1 class="header">Forgot Password?</h1>
        <p class="subheader">Enter your email or phone number to recover your account</p>

        <div class="error-message" id="errorMessage"></div>

        <form id="forgotForm" onsubmit="handleSubmit(event)">
            @csrf
            <div class="form-group">
                <label for="identifier">Email or Phone Number</label>
                <input
                    type="text"
                    id="identifier"
                    name="identifier"
                    placeholder="name@example.com or 9876543210"
                    required
                    autofocus
                >
            </div>

            <button type="submit" class="btn-primary" id="submitBtn">Continue</button>

            <div class="loading" id="loading">
                <div class="spinner"></div>
            </div>
        </form>

        <div class="back-link">
            <a href="{{ route('login') }}">Back to Login</a>
        </div>
    </div>

    <script>
        const form = document.getElementById('forgotForm');
        const submitBtn = document.getElementById('submitBtn');
        const loading = document.getElementById('loading');
        const errorMessage = document.getElementById('errorMessage');

        async function handleSubmit(event) {
            event.preventDefault();

            const identifier = document.getElementById('identifier').value.trim();

            if (!identifier) {
                showError('Please enter your email or phone number');
                return;
            }

            submitBtn.disabled = true;
            loading.style.display = 'block';
            errorMessage.style.display = 'none';

            try {
                const response = await fetch('{{ route("password.check-user") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ identifier })
                });

                const data = await response.json();

                if (!response.ok) {
                    showError(data.message || 'Account not found');
                    return;
                }

                window.location.href = `/auth/reset-method/${data.user_id}`;

            } catch (error) {
                console.error('Error:', error);
                showError('An error occurred. Please try again.');
            } finally {
                submitBtn.disabled = false;
                loading.style.display = 'none';
            }
        }

        function showError(message) {
            errorMessage.textContent = message;
            errorMessage.style.display = 'block';
        }
    </script>
</body>
</html>
