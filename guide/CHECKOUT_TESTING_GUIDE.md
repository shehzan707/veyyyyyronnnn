# Checkout System - Quick Testing Guide

## Test Scenario 1: Complete Checkout Flow (Logged-In User)

### Prerequisites
1. User logged in
2. Products added to cart with quantities and sizes selected
3. User has saved addresses (or will create new one)

### Steps to Test

#### Step 1: Navigate to Checkout
```
1. Click "Proceed to Checkout" button from cart
2. URL should change to /checkout
3. Three-step progress indicator should display: BAG → SHIPPING → PAYMENT
4. BAG step should be active (highlighted in black)
```

#### Step 2: Review Order (BAG Step)
```
1. Verify cart items display:
   - Product image
   - Product name
   - Quantity
   - Size
   - Item total (price × quantity)

2. Verify billing summary:
   - Subtotal = sum of all item totals
   - Platform Fee (usually ₹0)
   - Shipping:
     - FREE if subtotal > ₹999
     - ₹50 if subtotal ≤ ₹999
   - Coupon discount (if any applied)
   - Total = subtotal + shipping - discount

3. Click "Next: Shipping Info" button
   - Should validate (always passes for BAG)
   - Should move to SHIPPING step
```

#### Step 3: Enter Shipping Information (SHIPPING Step)
```
Contact Information:
1. Verify fields are pre-filled:
   - First Name: from Auth::user()->first_name
   - Last Name: from Auth::user()->last_name
   - Email: from Auth::user()->email
   - Mobile: from Auth::user()->phone

2. You can edit any field

Saved Addresses:
3. If user has saved addresses:
   - Click radio button to select address
   - All other address fields auto-fill
   - New address form hides

4. If no saved addresses or want new:
   - Enter address details manually:
     * Address: Street address
     * City: City name
     * State: State name
     * Pincode: 6-digit postal code

Address Type:
5. Select address type:
   - Click "Home" (default) - OFFICE SECTION HIDDEN
   - Click "Office" - OFFICE SECTION APPEARS with:
     * Delivery Time dropdown (must select one)
     * Saturday delivery checkbox
     * Sunday delivery checkbox

Office Delivery Example:
- Select "Office"
- Choose delivery time: "9:00 AM - 5:00 PM"
- Check "Open on Saturday" if applicable
- Check "Open on Sunday" if applicable

6. Click "Next: Payment Method" button
   - Validates: All contact fields filled, address selected/filled, if office then time selected
   - Moves to PAYMENT step
```

#### Step 4: Select Payment Method (PAYMENT Step)
```
Payment Method Selection:

Test COD (Cash on Delivery):
1. Select "Cash on Delivery" card
   - Card should highlight with border
   - NO payment detail fields should appear

Test UPI:
1. Select "UPI" card
2. UPI detail section should appear with:
   - UPI ID input field
3. Enter valid UPI ID: yourname@bankname
   Example: john.doe@okhdfcbank
4. Field has pattern validation

Test Debit Card / Credit Card:
1. Select "Debit Card" or "Credit Card"
2. Card detail section should appear with:
   - Card Number (text input)
   - Card Expiry (MM/YY format)
   - Card CVV (3-4 digits)
   - Cardholder Name (text input)
3. Test card number formatting:
   - Type: 1234567890123456
   - Display: 1234 5678 9012 3456 (auto-formatted with spaces)
4. Test expiry formatting:
   - Type: 202412
   - Display: 20/24 (auto-formatted)
5. Enter test data:
   - Card Number: 4532123456789010 (test card)
   - Expiry: 12/25
   - CVV: 123
   - Cardholder: John Doe

Test Net Banking:
1. Select "Net Banking" card
2. Bank dropdown should appear with options:
   - HDFC Bank
   - ICICI Bank
   - Axis Bank
   - SBI
   - Kotak Mahindra
3. Select any bank from dropdown

Test Digital Wallet:
1. Select "Digital Wallet" card
2. Wallet section should appear with:
   - Wallet Name dropdown (PayPal, Google Pay, Apple Pay, etc.)
   - Wallet ID input field
3. Select wallet: "Google Pay"
4. Enter wallet ID: phone number (e.g., 9876543210)

7. Verify total amount displayed:
   - Should match order summary in sidebar
   - Matches BAG step total

8. Click "Place Order" button
   - Validates all payment fields for selected method
   - If valid: submits form and creates order
   - Redirects to /order-success/{orderId}
```

#### Step 5: Order Success Page
```
1. URL should be /order-success/[number]
2. Page should display:
   - Order confirmation message
   - Order ID
   - Order summary
   - Shipping details
   - Payment method used
   - Total amount
3. Verify database:
   - New Order record created with all details
   - OrderItem records created for each cart item
   - Stock reduced for each product/size
   - payment_details JSON stored correctly
   - address_type and delivery_time stored (if office)
   - open_saturday, open_sunday stored (if checked)
```

---

## Test Scenario 2: Guest Checkout (No Login)

### Steps
```
1. Don't login, add products to cart
2. Click "Proceed to Checkout"
3. Same flow as Scenario 1
4. Create new address (no saved addresses available)
5. Enter all required information manually
6. Complete checkout
7. Verify in database:
   - Order.user_id = NULL (guest order)
   - Address not saved (not a logged-in user)
```

---

## Test Scenario 3: Edge Cases

### Invalid Address Type for Office
```
1. Select "Office" as address type
2. Don't select delivery time
3. Try to proceed to Payment step
4. Should see alert: "Please select a preferred delivery time for office"
5. Cannot proceed
```

### Missing Contact Information
```
1. Clear first_name field
2. Try to proceed to Payment step
3. Should see alert: "Please fill in all contact information fields"
4. Cannot proceed
```

### Invalid UPI ID
```
1. Select UPI payment method
2. Enter invalid UPI: "john" (missing @)
3. Try to place order
4. Should see validation error
5. Form not submitted
```

### Invalid Card Number
```
1. Select Debit Card
2. Enter card number: "1234" (less than 16 digits)
3. Try to place order
4. Should see validation error: "Card number must be 16 digits"
5. Form not submitted
```

### Stock Validation
```
1. Add product with 5 units in stock
2. Try to add 10 units to cart
3. Stock validation should prevent adding more than 5
4. Even if somehow 10 added, checkout should fail with:
   "Sorry, [product name] has insufficient stock"
```

---

## Test Scenario 4: Database Verification

### After Order Creation, Check:

```sql
-- Verify Order record
SELECT * FROM orders WHERE id = [order_id];

-- Should contain:
- id: order ID
- user_id: user's ID (or NULL for guest)
- name: "First Last"
- email: user's email
- mobile: user's phone
- address: shipping address
- city, state, pincode: shipping location
- address_type: "home" or "office"
- delivery_time: "9-5", "10-6", "9-1", or "2-6" (if office)
- open_saturday: 0 or 1 (if office)
- open_sunday: 0 or 1 (if office)
- payment_method: "cod", "upi", "debit_card", etc.
- payment_status: "pending"
- payment_details: JSON {"upi_id": "..."} or {...}
- order_status: "pending"
- total_amount: calculated total

-- Verify OrderItem records
SELECT * FROM order_items WHERE order_id = [order_id];

-- Should contain:
- order_id
- product_id
- product_name
- size
- quantity
- price (unit price)

-- Verify Stock Reduced
SELECT stock FROM sizes WHERE product_id = [product_id] AND size = '[size]';

-- Stock should be reduced by quantity ordered
```

---

## Common Issues & Solutions

### Issue: "CSRF token mismatch"
**Solution**: Ensure form includes `@csrf` blade directive (already included)

### Issue: "Only X available" warning on cart
**Cause**: Stock validation working correctly
**Solution**: Don't try to add more than available stock

### Issue: Empty payment fields not validating
**Solution**: Only validates fields for selected payment method
- COD: No validation needed
- UPI: upi_id required
- Card: All card fields required
- Bank: bank_name required
- Wallet: wallet_name and wallet_id required

### Issue: Address not saving for new address
**Cause**: User is guest (not logged in)
**Solution**: This is correct behavior - only logged-in users get saved addresses

### Issue: Sidebar total doesn't match final total
**Cause**: Possible JavaScript calculation error
**Solution**: Verify in checkout-final.blade.php that subtotal/shipping calculation is correct

---

## Form Input Names Reference

Use these names when testing or inspecting form data:

```
Contact Info:
- first_name
- last_name
- email
- mobile

Address:
- address_id (if selecting saved)
- address (if new address)
- city
- state
- pincode

Settings:
- address_type (home|office)
- delivery_time (9-5|10-6|9-1|2-6)
- open_saturday (checkbox)
- open_sunday (checkbox)

Payment:
- payment_method (cod|upi|debit_card|credit_card|netbanking|wallet)

Payment Details:
- upi_id
- card_number
- card_expiry
- card_cvv
- card_name
- bank_name
- wallet_name
- wallet_id
```

---

## Browser Developer Tools Inspection

### To inspect form data:

```javascript
// In browser console:
const form = document.getElementById('checkoutForm');
const formData = new FormData(form);
for (let [key, value] of formData.entries()) {
    console.log(key + ': ' + value);
}

// Or in Network tab:
1. Submit the form
2. Right-click POST /checkout request
3. Go to "Request" tab
4. View Form Data section
```

---

## Performance Testing

### Expected Response Times
- GET /checkout: < 100ms
- POST /checkout: < 500ms (with database writes)

### Database Query Count
- GET /checkout: 2-3 queries (cart items, user addresses)
- POST /checkout: 5-10 queries (validation, order creation, items, stock update)

### Stock Update Verification
```php
// After order, stock should be reduced:
Original: 100
Ordered: 5
New Stock: 95
```

---

## Security Testing

### CSRF Protection
```
1. Remove @csrf from form
2. Try to submit
3. Should get CSRF token mismatch error ✓
```

### SQL Injection Prevention
```
1. Enter malicious input in address field: "'; DROP TABLE orders; --"
2. Should be escaped/sanitized
3. Order should save with literal string ✓
```

### Payment Data Safety
```
1. View form data in Network tab
2. Payment details visible in POST data
3. TODO in production: Encrypt sensitive data
```

---

## Acceptance Criteria Checklist

- [ ] All 3 steps navigate correctly
- [ ] No step can be skipped
- [ ] Contact information pre-fills for logged-in users
- [ ] Saved addresses display for logged-in users
- [ ] New address form works for guests and logged-in users
- [ ] Office section shows/hides based on address type
- [ ] Delivery time options display only for office
- [ ] Weekend checkboxes appear only for office
- [ ] Payment method selection shows/hides relevant fields
- [ ] Card number auto-formats with spaces
- [ ] Expiry date auto-formats to MM/YY
- [ ] Order is created with all information
- [ ] Stock is properly reduced
- [ ] Cart is cleared after order
- [ ] Order success page displays correctly
- [ ] All form validations work (client and server)
- [ ] Database records created correctly
- [ ] payment_details JSON stored correctly
- [ ] address_type stored correctly
- [ ] delivery_time stored correctly (if office)
- [ ] open_saturday/open_sunday stored correctly (if office)
- [ ] Responsive design works on mobile/tablet
- [ ] No errors in browser console
- [ ] No database errors in server logs
