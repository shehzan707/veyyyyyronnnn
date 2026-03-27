<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Identity - Veyron</title>
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
            margin-bottom: 28px;
            color: #666;
        }

        .info-box {
            background: #f0f4f8;
            border-left: 3px solid #2c2f4a;
            padding: 12px 14px;
            border-radius: 4px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #333;
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

        .success-message {
            background: #efe;
            color: #060;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: none;
            font-size: 13px;
            border-left: 3px solid #060;
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

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #2c2f4a;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .back-link a:hover {
            text-decoration: underline;
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
    </style>
</head>
<body>
    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo" onerror="this.style.display='none'">

    <div class="container">
        <h1 class="header">Verify Your Identity</h1>
        <p class="subheader">Confirm your email and phone number</p>

        <div class="info-box">
            Enter your email address or phone number to verify your identity and continue.
        </div>

        <div class="error-message" id="errorMessage"></div>
        <div class="success-message" id="successMessage"></div>

        <form id="verifyForm" onsubmit="handleSubmit(event)">
            @csrf
            <input type="hidden" id="userId" value="{{ $user->id }}">

            <div class="form-group">
                <label for="identifier">Email or Phone Number</label>
                <input
                    type="text"
                    id="identifier"
                    name="identifier"
                    placeholder="Enter your email or phone number"
                    required
                    autofocus
                >
            </div>

            <button type="submit" class="btn-primary" id="submitBtn">Send Verification Code</button>

            <div class="loading" id="loading">
                <div class="spinner"></div>
            </div>
        </form>

        <div class="back-link">
            <a href="{{ route('password.forgot') }}">Back</a>
        </div>
    </div>

    <script>
        const form = document.getElementById('verifyForm');
        const submitBtn = document.getElementById('submitBtn');
        const loading = document.getElementById('loading');
        const errorMessage = document.getElementById('errorMessage');
        const successMessage = document.getElementById('successMessage');

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function isValidPhone(phone) {
            const phoneRegex = /^[0-9]{7,}$/; // At least 7 digits
            return phoneRegex.test(phone.replace(/\D/g, ''));
        }

        async function handleSubmit(event) {
            event.preventDefault();

            const userId = document.getElementById('userId').value;
            const identifier = document.getElementById('identifier').value.trim();

            if (!identifier) {
                showError('Please enter your email or phone number');
                return;
            }

            let isEmail = isValidEmail(identifier);
            let isPhone = isValidPhone(identifier);

            if (!isEmail && !isPhone) {
                showError('Please enter a valid email address or phone number');
                return;
            }

            submitBtn.disabled = true;
            loading.style.display = 'block';
            errorMessage.style.display = 'none';

            try {
                const payload = {
                    user_id: userId
                };

                if (isEmail) {
                    payload.email = identifier;
                    console.log('Sending email verification:', { userId, email: identifier });
                } else {
                    payload.mobile = identifier;
                    console.log('Sending mobile verification:', { userId, mobile: identifier });
                }

                const response = await fetch('{{ route("password.verify-identity-post") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();
                console.log('Server response:', data);

                if (!response.ok) {
                    showError(data.message || 'Identity verification failed');
                    return;
                }

                // Success - proceed to OTP generation
                showSuccess('Identity verified! Generating verification code...');
                setTimeout(async () => {
                    await generateOtp(userId);
                }, 1500);

            } catch (error) {
                console.error('Error:', error);
                showError('An error occurred. Please try again.');
            } finally {
                submitBtn.disabled = false;
                loading.style.display = 'none';
            }
        }

        async function generateOtp(userId) {
            try {
                const response = await fetch('{{ route("password.generate-otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_id: userId
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    // Redirect to OTP input page
                    window.location.href = `/auth/verify-otp/${userId}`;
                } else {
                    showError(data.message || 'Failed to generate verification code');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('An error occurred. Please try again.');
            }
        }

        function showError(message) {
            errorMessage.textContent = message;
            errorMessage.style.display = 'block';
        }

        function showSuccess(message) {
            successMessage.textContent = message;
            successMessage.style.display = 'block';
        }
    </script>
</body>
</html>
