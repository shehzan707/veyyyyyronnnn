<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enter Verification Code - Veyron</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
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
            font-size: 18px;
            text-align: center;
            letter-spacing: 6px;
            font-weight: bold;
            font-family: 'Courier New', monospace;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #2c2f4a;
            box-shadow: 0 0 0 3px rgba(44, 47, 74, 0.05);
        }

        .help-text {
            font-size: 12px;
            color: #999;
            margin-top: 6px;
            text-align: center;
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

        .countdown {
            font-size: 13px;
            color: #666;
            margin-top: 8px;
            text-align: center;
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

        .resend-section {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .resend-text {
            font-size: 13px;
            color: #666;
            margin-bottom: 12px;
        }

        .btn-resend {
            background: #fff;
            color: #2c2f4a;
            border: 1px solid #2c2f4a;
            padding: 9px 16px;
            cursor: pointer;
            border-radius: 6px;
            font-weight: 500;
            font-size: 13px;
            transition: all 0.2s;
        }

        .btn-resend:hover:not(:disabled) {
            background: #2c2f4a;
            color: #fff;
        }

        .btn-resend:disabled {
            opacity: 0.5;
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
    <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" class="logo" onerror="this.style.display='none'">

    <div class="container">
        <h1 class="header">Verify Your Code</h1>
        <p class="subheader">Enter the 4-digit verification code</p>

        <div class="error-message" id="errorMessage"></div>
        <div class="success-message" id="successMessage"></div>

        <form id="otpForm" onsubmit="handleSubmit(event)">
            <?php echo csrf_field(); ?>
            <input type="hidden" id="userId" value="<?php echo e($user->id); ?>">

            <div class="form-group">
                <label for="otpCode">Verification Code</label>
                <input
                    type="text"
                    id="otpCode"
                    name="otp_code"
                    placeholder="0000"
                    maxlength="4"
                    inputmode="numeric"
                    pattern="[0-9]{4}"
                    required
                    autofocus
                    oninput="validateInput(this)"
                >
                <div class="help-text">Enter the 4-digit code sent to your email</div>
            </div>

            <div id="expiryCountdown" class="countdown"></div>

            <button type="submit" class="btn-primary" id="submitBtn">Verify Code</button>

            <div class="loading" id="loading">
                <div class="spinner"></div>
            </div>
        </form>

        <div class="resend-section">
            <div class="resend-text">Didn't receive a code?</div>
            <button type="button" class="btn-resend" id="resendBtn" onclick="resendOtp()">Resend Code</button>
        </div>

        <div class="back-link">
            <a href="<?php echo e(route('login')); ?>">Back to Login</a>
        </div>
    </div>

    <script>
        let expiryTime = null;
        let resendTimeout = null;

        // Initialize
        window.addEventListener('DOMContentLoaded', () => {
            loadOtpCode();
            startExpiryCountdown();
        });

        function validateInput(input) {
            // Only allow digits
            input.value = input.value.replace(/[^0-9]/g, '');

            // Limit to 4 characters
            if (input.value.length > 4) {
                input.value = input.value.slice(0, 4);
            }
        }

        function loadOtpCode() {
            // Any 4-digit code with even numbers (0,2,4,6,8) will work
        }

        function startExpiryCountdown() {
            // The OTP expires in 5 minutes
            const now = new Date();
            expiryTime = new Date(now.getTime() + 5 * 60000);

            updateCountdown();
            setInterval(updateCountdown, 1000);
        }

        function updateCountdown() {
            const now = new Date();
            const diff = expiryTime - now;

            if (diff <= 0) {
                document.getElementById('expiryCountdown').textContent = 'Code has expired. Please request a new one.';
                document.getElementById('submitBtn').disabled = true;
                return;
            }

            const minutes = Math.floor(diff / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);
            document.getElementById('expiryCountdown').textContent = `Code expires in ${minutes}:${seconds.toString().padStart(2, '0')}`;
        }

        async function handleSubmit(event) {
            event.preventDefault();

            const userId = document.getElementById('userId').value;
            const otpCode = document.getElementById('otpCode').value.trim();
            const errorMessage = document.getElementById('errorMessage');
            const successMessage = document.getElementById('successMessage');
            const submitBtn = document.getElementById('submitBtn');
            const loading = document.getElementById('loading');

            // Validation
            if (otpCode.length !== 4) {
                showError('Please enter a valid 4-digit code');
                return;
            }

            submitBtn.disabled = true;
            loading.style.display = 'block';
            errorMessage.style.display = 'none';

            try {
                const response = await fetch('<?php echo e(route("password.verify-otp-post")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        otp_code: otpCode
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    showError(data.message || 'Invalid verification code');
                    return;
                }

                // Success - redirect to password reset page
                showSuccess('Code verified! Redirecting to set new password...');
                setTimeout(() => {
                    window.location.href = `/auth/reset-otp-password/${userId}`;
                }, 1500);

            } catch (error) {
                console.error('Error:', error);
                showError('An error occurred. Please try again.');
            } finally {
                submitBtn.disabled = false;
                loading.style.display = 'none';
            }
        }

        async function resendOtp() {
            const userId = document.getElementById('userId').value;
            const resendBtn = document.getElementById('resendBtn');
            const errorMessage = document.getElementById('errorMessage');

            resendBtn.disabled = true;
            resendBtn.textContent = 'Sending...';

            try {
                const response = await fetch('<?php echo e(route("password.resend-otp")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({
                        user_id: userId
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    // Code resent - clear input and reset timer
                    document.getElementById('otpCode').value = '';
                    startExpiryCountdown();

                    // Reset resend button after 30 seconds
                    setTimeout(() => {
                        resendBtn.disabled = false;
                        resendBtn.textContent = 'Resend Code';
                    }, 30000);
                } else {
                    showError(data.message || 'Failed to resend code');
                    resendBtn.disabled = false;
                    resendBtn.textContent = 'Resend Code';
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Failed to resend code');
                resendBtn.disabled = false;
                resendBtn.textContent = 'Resend Code';
            }
        }

        function showError(message) {
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.textContent = message;
            errorMessage.style.display = 'block';
        }

        function showSuccess(message) {
            const successMessage = document.getElementById('successMessage');
            successMessage.textContent = message;
            successMessage.style.display = 'block';
        }
    </script>
</body>
</html>
<?php /**PATH C:\Veyronnnnnnnnnn\resources\views/auth/verify-otp.blade.php ENDPATH**/ ?>