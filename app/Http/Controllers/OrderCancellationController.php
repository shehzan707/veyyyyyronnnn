<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderCancellationController extends Controller
{
    /**
     * Canned cancellation reasons
     */
    private static $cancellationReasons = [
        'ordered_by_mistake' => 'Ordered by mistake',
        'found_cheaper' => 'Found cheaper elsewhere',
        'delivery_time_long' => 'Delivery time is too long',
        'wrong_size' => 'Wrong size / variant selected',
        'changed_mind' => 'Changed my mind',
        'shipping_too_high' => 'Shipping charges too high',
        'payment_issue' => 'Payment issue',
        'product_unclear' => 'Product details not clear',
        'other' => 'Other',
    ];

    /**
     * Get valid cancellation reasons
     */
    public function getReasons()
    {
        return response()->json([
            'reasons' => self::$cancellationReasons,
            'success' => true
        ]);
    }

    /**
     * Cancel a specific order item
     */
    public function cancelItem(Request $request, $itemId)
    {
        // Validate request
        $validated = $request->validate([
            'cancel_reason' => 'required|string',
            'custom_reason' => 'nullable|string|max:500',
        ]);

        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        try {
            // Find the order item
            $orderItem = OrderItem::findOrFail($itemId);
            
            // Verify the item belongs to the user's order
            $order = Order::findOrFail($orderItem->order_id);
            if ($order->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            // Set default status to 'Placed' if empty (for items created before migration)
            $itemStatus = $orderItem->item_status ?: 'Placed';
            $orderItem->item_status = $itemStatus;

            // Check if item can be cancelled (only Placed or Processing status)
            if (!in_array($itemStatus, ['Placed', 'Processing'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item cannot be cancelled. Items with status "' . $itemStatus . '" cannot be cancelled.'
                ], 422);
            }

            // Check if already cancelled
            if ($itemStatus === 'Cancelled') {
                return response()->json(['success' => false, 'message' => 'Item is already cancelled'], 422);
            }

            // Build the cancellation reason text
            $reasonKey = $validated['cancel_reason'];
            $reasonText = self::$cancellationReasons[$reasonKey] ?? $reasonKey;
            
            if ($reasonKey === 'other' && !empty($validated['custom_reason'])) {
                $reasonText = 'Other: ' . $validated['custom_reason'];
            }

            // Update the order item
            $orderItem->update([
                'item_status' => 'Cancelled',
                'cancel_reason' => $reasonText,
                'cancelled_at' => now(),
            ]);

            // Restore product stock for the specific size
            try {
                if ($orderItem->product_id && $orderItem->size) {
                    $product = Product::find($orderItem->product_id);
                    if ($product) {
                        $product->restoreStock($orderItem->size, $orderItem->quantity);
                    }
                }
            } catch (\Exception $stockErr) {
                \Log::warning('Stock restoration failed: ' . $stockErr->getMessage());
                // Don't fail the entire operation if stock restoration fails
            }

            // Handle refund logic
            $refundAmount = $orderItem->price * $orderItem->quantity;
            
            try {
                if ($order->payment_method !== 'cod') {
                    $currentRefundAmount = $order->refund_amount ?? 0;
                    $order->update([
                        'refund_status' => 'Initiated',
                        'refund_amount' => $currentRefundAmount + $refundAmount,
                        'refund_initiated_at' => now(),
                    ]);
                }
            } catch (\Exception $refundErr) {
                \Log::warning('Refund update failed: ' . $refundErr->getMessage());
            }

            // Try to update order status but don't fail if it errors
            try {
                $freshOrder = Order::find($orderItem->order_id);
                $allCancelledCount = $freshOrder->items()->where('item_status', 'Cancelled')->count();
                $totalItemsCount = $freshOrder->items()->count();

                if ($allCancelledCount === $totalItemsCount) {
                    $freshOrder->update(['order_status' => 'Cancelled']);
                } elseif ($allCancelledCount > 0) {
                    $freshOrder->update(['order_status' => 'Partially Cancelled']);
                }
            } catch (\Exception $statusErr) {
                \Log::warning('Order status update failed: ' . $statusErr->getMessage());
            }

            // Return success - the core cancellation has been completed
            return response()->json([
                'success' => true,
                'message' => 'Order item cancelled successfully!',
                'refund_amount' => $refundAmount,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $ve) {
            \Log::error('Validation error in cancelItem:', $ve->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . json_encode($ve->errors())
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Exception in cancelItem: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Error cancelling item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel entire order
     */
    public function cancelOrder(Request $request, $orderId)
    {
        $validated = $request->validate([
            'cancel_reason' => 'required|string',
            'custom_reason' => 'nullable|string|max:500',
        ]);

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        try {
            $order = Order::findOrFail($orderId);
            
            // Verify ownership
            if ($order->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            // Build cancellation reason
            $reasonKey = $validated['cancel_reason'];
            $reasonText = self::$cancellationReasons[$reasonKey] ?? $reasonKey;
            if ($reasonKey === 'other' && !empty($validated['custom_reason'])) {
                $reasonText = 'Other: ' . $validated['custom_reason'];
            }

            $totalRefund = 0;

            // Cancel all items that can be cancelled
            foreach ($order->items as $item) {
                // Set default status to 'Placed' if empty
                $itemStatus = $item->item_status ?: 'Placed';
                
                if (in_array($itemStatus, ['Placed', 'Processing'])) {
                    $item->update([
                        'item_status' => 'Cancelled',
                        'cancel_reason' => $reasonText,
                        'cancelled_at' => now(),
                    ]);

                    // Restore stock for the specific size
                    if ($item->product_id && $item->size) {
                        $product = Product::find($item->product_id);
                        if ($product) {
                            $product->restoreStock($item->size, $item->quantity);
                        }
                    }

                    $totalRefund += $item->price * $item->quantity;
                }
            }

            // Update order status and refund
            if ($order->payment_method !== 'cod') {
                $order->update([
                    'order_status' => 'Cancelled',
                    'refund_status' => 'Initiated',
                    'refund_amount' => $totalRefund,
                    'refund_initiated_at' => now(),
                ]);
            } else {
                $order->update(['order_status' => 'Cancelled']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully. ' . 
                    ($order->payment_method !== 'cod' 
                        ? 'Refund of ₹' . number_format($totalRefund, 2) . ' will be processed shortly.'
                        : 'Your order has been cancelled.'),
                'refund_amount' => $totalRefund,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error cancelling order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel an order item by admin (one click, no reason needed)
     */
    public function cancelItemByAdmin($itemId)
    {
        try {
            // Find the order item
            $orderItem = OrderItem::findOrFail($itemId);
            
            // Get the order
            $order = Order::findOrFail($orderItem->order_id);

            // Set default status to 'Placed' if empty
            $itemStatus = $orderItem->item_status ?: 'Placed';
            $orderItem->item_status = $itemStatus;

            // Check if item can be cancelled
            if (!in_array($itemStatus, ['Placed', 'Processing'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item cannot be cancelled.'
                ], 422);
            }

            // Check if already cancelled
            if ($itemStatus === 'Cancelled') {
                return response()->json(['success' => false, 'message' => 'Item is already cancelled'], 422);
            }

            // Update the order item with admin cancellation
            $orderItem->update([
                'item_status' => 'Cancelled',
                'cancel_reason' => 'Cancelled by Admin',
                'cancelled_at' => now(),
                'cancelled_by_admin' => true,
            ]);

            // Restore product stock for the specific size
            try {
                if ($orderItem->product_id && $orderItem->size) {
                    $product = Product::find($orderItem->product_id);
                    if ($product) {
                        $product->restoreStock($orderItem->size, $orderItem->quantity);
                    }
                }
            } catch (\Exception $stockErr) {
                \Log::warning('Stock restoration failed: ' . $stockErr->getMessage());
            }

            // Handle refund logic
            $refundAmount = $orderItem->price * $orderItem->quantity;
            
            try {
                if ($order->payment_method !== 'cod') {
                    $currentRefundAmount = $order->refund_amount ?? 0;
                    $order->update([
                        'refund_status' => 'Initiated',
                        'refund_amount' => $currentRefundAmount + $refundAmount,
                        'refund_initiated_at' => now(),
                    ]);
                }
            } catch (\Exception $refundErr) {
                \Log::warning('Refund update failed: ' . $refundErr->getMessage());
            }

            // Update order status
            try {
                $freshOrder = Order::find($orderItem->order_id);
                $allCancelledCount = $freshOrder->items()->where('item_status', 'Cancelled')->count();
                $totalItemsCount = $freshOrder->items()->count();

                if ($allCancelledCount === $totalItemsCount) {
                    $freshOrder->update(['order_status' => 'Cancelled']);
                } elseif ($allCancelledCount > 0) {
                    $freshOrder->update(['order_status' => 'Partially Cancelled']);
                }
            } catch (\Exception $statusErr) {
                \Log::warning('Order status update failed: ' . $statusErr->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Item cancelled by admin successfully!',
                'refund_amount' => $refundAmount,
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Exception in cancelItemByAdmin: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error cancelling item: ' . $e->getMessage()
            ], 500);
        }
    }
}
