<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Veyron</title>
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
            max-width: 460px;
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
        }

        .user-info {
            background: #f8f9fa;
            padding: 12px 14px;
            border-radius: 6px;
            margin-bottom: 28px;
            text-align: center;
            border-left: 3px solid #2c2f4a;
        }

        .user-info p {
            margin: 0;
            color: #666;
            font-size: 13px;
        }

        .user-info strong {
            color: #1a1a1a;
            font-weight: 500;
        }

        .options {
            display: grid;
            gap: 12px;
            margin-bottom: 20px;
        }

        .option {
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            padding: 16px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .option:hover {
            border-color: #2c2f4a;
            background: #fafbfc;
        }

        .option.selected {
            border-color: #2c2f4a;
            background: #f0f1f5;
        }

        .option-title {
            font-size: 15px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .option-desc {
            font-size: 13px;
            color: #666;
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
        <h1 class="header">Reset Password</h1>
        <p class="subheader">Choose how you want to reset your password</p>

        <div class="user-info">
            <p>Account: <strong>{{ $user->email ?? $user->mobile }}</strong></p>
        </div>

        <div class="options">
            <div class="option" id="option1" onclick="selectMethod(1)">
                <div class="option-title">Using Old Password</div>
                <div class="option-desc">Enter your current password to set a new one</div>
            </div>

            <div class="option" id="option2" onclick="selectMethod(2)">
                <div class="option-title">Using Verification Code</div>
                <div class="option-desc">Verify your identity with email and phone number</div>
            </div>
        </div>

        <button type="button" class="btn-primary" id="continueBtn" disabled onclick="continueProcedure()">
            Continue
        </button>

        <div class="back-link">
            <a href="{{ route('login') }}">Back to Login</a>
        </div>
    </div>

    <script>
        let selectedMethod = null;

        function selectMethod(method) {
            selectedMethod = method;

            document.querySelectorAll('.option').forEach(el => {
                el.classList.remove('selected');
            });

            if (method === 1) {
                document.getElementById('option1').classList.add('selected');
            } else {
                document.getElementById('option2').classList.add('selected');
            }

            document.getElementById('continueBtn').disabled = false;
        }

        async function continueProcedure() {
            const userId = {{ $user->id }};

            if (selectedMethod === 1) {
                window.location.href = `/auth/reset-old-password/${userId}`;
            } else if (selectedMethod === 2) {
                // Generate OTP directly, skip verification page
                await generateOtpAndProceed(userId);
            }
        }

        async function generateOtpAndProceed(userId) {
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
                    // Go directly to OTP input page
                    window.location.href = `/auth/verify-otp/${userId}`;
                } else {
                    alert(data.message || 'Failed to generate verification code');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        }
    </script>
</body>
</html>
