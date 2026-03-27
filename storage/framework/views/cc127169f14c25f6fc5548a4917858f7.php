<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set New Password - Veyron</title>
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

        .success-badge {
            text-align: center;
            margin-bottom: 16px;
            font-size: 40px;
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

        .password-wrapper {
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 11px 36px 11px 12px;
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

        .toggle-pwd {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 16px;
            color: #999;
            user-select: none;
            background: none;
            border: none;
            padding: 0;
        }

        .toggle-pwd:hover {
            color: #666;
        }

        .strength-bar {
            height: 3px;
            background: #e0e0e0;
            border-radius: 2px;
            overflow: hidden;
            margin-top: 6px;
            margin-bottom: 6px;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: width 0.2s, background-color 0.2s;
            background-color: #ccc;
        }

        .strength-text {
            font-size: 12px;
            color: #999;
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
    <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" class="logo" onerror="this.style.display='none'">

    <div class="container">
        <div class="success-badge">✓</div>
        <h1 class="header">Set New Password</h1>
        <p class="subheader">Your identity has been verified</p>

        <div class="error-message" id="errorMessage"></div>
        <div class="success-message" id="successMessage"></div>

        <form id="passwordForm" onsubmit="handleSubmit(event)">
            <?php echo csrf_field(); ?>
            <input type="hidden" id="userId" value="<?php echo e($user->id); ?>">

            <div class="form-group">
                <label for="newPassword">New Password</label>
                <div class="password-wrapper">
                    <input
                        type="password"
                        id="newPassword"
                        name="new_password"
                        placeholder="Enter your new password"
                        required
                        autofocus
                        oninput="checkStrength()"
                    >
                    <button type="button" class="toggle-pwd" onclick="togglePassword('newPassword')">Show</button>
                </div>
                <div class="strength-bar">
                    <div class="strength-fill" id="strengthFill"></div>
                </div>
                <div class="strength-text" id="strengthText">Strength: Weak</div>
            </div>

            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <div class="password-wrapper">
                    <input
                        type="password"
                        id="confirmPassword"
                        name="new_password_confirmation"
                        placeholder="Confirm your new password"
                        required
                    >
                    <button type="button" class="toggle-pwd" onclick="togglePassword('confirmPassword')">Show</button>
                </div>
            </div>

            <button type="submit" class="btn-primary" id="submitBtn">Reset Password</button>

            <div class="loading" id="loading">
                <div class="spinner"></div>
            </div>
        </form>

        <div class="back-link">
            <a href="<?php echo e(route('login')); ?>">Back to Login</a>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const button = event.target;
            if (field.type === 'password') {
                field.type = 'text';
                button.textContent = 'Hide';
            } else {
                field.type = 'password';
                button.textContent = 'Show';
            }
        }

        function checkStrength() {
            const pwd = document.getElementById('newPassword').value;
            const bar = document.getElementById('strengthFill');
            const text = document.getElementById('strengthText');
            
            let strength = 0;
            const strengths = ['Weak', 'Fair', 'Good', 'Strong'];
            const colors = ['#ddd', '#f59e0b', '#3b82f6', '#10b981'];

            if (pwd.length >= 6) strength++;
            if (pwd.length >= 10) strength++;
            if (/[a-z]/.test(pwd) && /[A-Z]/.test(pwd)) strength++;
            if (/[0-9]/.test(pwd)) strength++;
            if (/[^a-zA-Z0-9]/.test(pwd)) strength++;

            const level = Math.min(Math.floor(strength / 1.5), 3);
            bar.style.width = ((level + 1) * 25) + '%';
            bar.style.backgroundColor = colors[level];
            text.textContent = 'Strength: ' + strengths[level];
        }

        async function handleSubmit(event) {
            event.preventDefault();

            const userId = document.getElementById('userId').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (newPassword !== confirmPassword) {
                showError('Passwords do not match');
                return;
            }

            if (newPassword.length < 6) {
                showError('Password must be at least 6 characters');
                return;
            }

            document.getElementById('submitBtn').disabled = true;
            document.getElementById('loading').style.display = 'block';

            try {
                const response = await fetch('<?php echo e(route("password.reset-otp-password-post")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        new_password: newPassword,
                        new_password_confirmation: confirmPassword
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    showError(data.message || 'Error updating password');
                    return;
                }

                showSuccess('Password updated successfully! Redirecting...');
                setTimeout(() => {
                    window.location.href = '<?php echo e(route("login")); ?>';
                }, 2000);

            } catch (error) {
                showError('An error occurred. Please try again.');
            } finally {
                document.getElementById('submitBtn').disabled = false;
                document.getElementById('loading').style.display = 'none';
            }
        }

        function showError(msg) {
            const el = document.getElementById('errorMessage');
            el.textContent = msg;
            el.style.display = 'block';
        }

        function showSuccess(msg) {
            const el = document.getElementById('successMessage');
            el.textContent = msg;
            el.style.display = 'block';
        }
    </script>
</body>
</html>
<?php /**PATH C:\Veyronnnnnnnnnn\resources\views/auth/reset-otp-password.blade.php ENDPATH**/ ?>