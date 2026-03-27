<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Veyron</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #f5f6fa;
        }

        .logo {
            height: 80px;
            border-radius: 2px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
            margin-bottom: 1.5rem;
        }

        #loginbox {
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
            width: 100%;
            max-width: 400px;
            animation: fadeIn 0.8s ease;
        }

        .loginheader {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .smallheader {
            font-size: 1rem;
            text-align: center;
            margin-bottom: 1rem;
            color: #555;
        }

        .loginbody input,
        .loginbody select {
            width: 100%;
            padding: 0.8rem 1rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .loginbody button.continue {
            width: 100%;
            padding: 0.8rem 1rem;
            background: #2c2f4a;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s, transform 0.3s;
        }

        .loginbody button.continue:hover {
            background:#2c2f4a;
            transform: translateY(-2px);
        }

        #loginbox p {
            margin-top: 1rem;
            text-align: center;
            color: #555;
        }

        #loginbox p span {
            color: #2c2f4a;
            cursor: pointer;
            font-weight: 500;
            text-decoration: underline;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 500px) {
            #loginbox {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <img src="<?php echo e(asset('images/veyronlogo.jpg')); ?>" alt="Veyron Logo" class="logo">

    <div id="loginbox">
        <div class="loginheader">Welcome to Veyron</div>
        <div class="smallheader">Login to your account</div>

        <?php if($errors->any()): ?>
            <div class="error-message">
                <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login')); ?>" class="loginbody">
            <?php echo csrf_field(); ?>
            <input type="text" name="identifier" placeholder="Email or Mobile Number" value="<?php echo e(old('identifier')); ?>" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="user" <?php echo e(old('role') == 'user' ? 'selected' : ''); ?>>User</option>
                <option value="admin" <?php echo e(old('role') == 'admin' ? 'selected' : ''); ?>>Admin</option>
            </select>

            <button type="submit" class="continue">Login</button>
        </form>

        <p>Don't have an account? <span onclick="window.location.href='<?php echo e(route('register')); ?>'">Register</span></p>
        <p style="margin-top: 0.5rem;"><span onclick="window.location.href='<?php echo e(route('password.forgot')); ?>'" style="cursor: pointer; color: #2c2f4a; text-decoration: underline;">Forgot Password?</span></p>
    </div>
</body>
</html>
<?php /**PATH C:\Veyronnnnnnnnnn\resources\views/auth/login.blade.php ENDPATH**/ ?>