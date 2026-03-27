# Password Reset Routes - Fixed Reference

## Route Names (Corrected)

All routes are prefixed with `password.` since they're in a named group.

### GET Routes (Display Forms)
- `password.forgot` → `/auth/forgot-password` (no params)
- `password.check-user` → POST only
- `password.reset-method` → `/auth/reset-method/{userId}` (requires userId)
- `password.reset-old-password` → `/auth/reset-old-password/{userId}` (requires userId)
- `password.verify-identity` → `/auth/verify-identity/{userId}` (requires userId)
- `password.verify-otp` → `/auth/verify-otp/{userId}` (requires userId)
- `password.reset-otp-password` → `/auth/reset-otp-password/{userId}` (requires userId)

### POST Routes (Handle Data)
- `password.check-user` → POST `/auth/check-user` (no userId param needed)
- `password.verify-old-password` → POST `/auth/verify-old-password`
- `password.verify-identity-post` → POST `/auth/verify-identity`
- `password.generate-otp` → POST `/auth/generate-otp`
- `password.resend-otp` → POST `/auth/resend-otp`
- `password.verify-otp-post` → POST `/auth/verify-otp`
- `password.reset-otp-password-post` → POST `/auth/reset-otp-password`

## Blade View Usage

### In verify-identity.blade.php
```blade
{{ route("password.verify-identity-post") }}  <!-- For POST request -->
```

### In verify-otp.blade.php
```blade
{{ route("password.verify-otp-post") }}  <!-- For POST request -->
```

### In reset-otp-password.blade.php
```blade
{{ route("password.reset-otp-password-post") }}  <!-- For POST request -->
```

### In reset-old-password.blade.php
```blade
{{ route("password.verify-old-password") }}  <!-- For POST request -->
```

---

✅ **All route names are now consistent and working!**
