<?php

namespace App\Support;

use App\Models\Coupon;

class CouponPricing
{
    private const SPECIAL_COUPONS = [
        'VEYRON10' => [
            'type' => 'percent',
            'value' => 10,
            'label' => '10% off',
        ],
        'VEY70' => [
            'type' => 'percent',
            'value' => 15,
            'label' => '15% off',
        ],
    ];

    public static function normalizeCode(?string $couponCode): ?string
    {
        $normalizedCode = strtoupper(trim((string) $couponCode));

        return $normalizedCode !== '' ? $normalizedCode : null;
    }

    public static function getDiscountData(?string $couponCode, float $subtotal): array
    {
        $normalizedCode = static::normalizeCode($couponCode);

        if (!$normalizedCode) {
            return static::emptyDiscountData();
        }

        if (isset(static::SPECIAL_COUPONS[$normalizedCode])) {
            $coupon = static::SPECIAL_COUPONS[$normalizedCode];

            return [
                'code' => $normalizedCode,
                'type' => $coupon['type'],
                'value' => $coupon['value'],
                'label' => $coupon['label'],
                'amount' => round($subtotal * ($coupon['value'] / 100)),
            ];
        }

        $coupon = Coupon::query()
            ->whereRaw('UPPER(code) = ?', [$normalizedCode])
            ->first();

        if (!$coupon) {
            return [
                'code' => $normalizedCode,
                'type' => null,
                'value' => null,
                'label' => '',
                'amount' => 0,
            ];
        }

        $amount = $coupon->type === 'percent'
            ? round($subtotal * ($coupon->value / 100))
            : (float) $coupon->value;

        return [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'label' => $coupon->type === 'percent'
                ? static::formatPercentLabel($coupon->value)
                : '₹' . number_format($coupon->value, 0) . ' off',
            'amount' => $amount,
        ];
    }

    public static function calculateTotal(
        float $subtotal,
        float $shipping,
        float $platformFee,
        ?string $couponCode
    ): array {
        $discount = static::getDiscountData($couponCode, $subtotal);

        return [
            'couponCode' => $discount['code'],
            'discount' => $discount['amount'],
            'discountLabel' => $discount['label'],
            'total' => $subtotal + $shipping + $platformFee - $discount['amount'],
        ];
    }

    private static function emptyDiscountData(): array
    {
        return [
            'code' => null,
            'type' => null,
            'value' => null,
            'label' => '',
            'amount' => 0,
        ];
    }

    private static function formatPercentLabel(float $value): string
    {
        $formattedValue = rtrim(rtrim(number_format($value, 2), '0'), '.');

        return $formattedValue . '% off';
    }
}
