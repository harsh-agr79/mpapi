<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Cart;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        // Get the authenticated customer
        $customer = $request->user();
    
        // Retrieve cart items for the customer
        $cartItems = Cart::where('customer_id', $customer->id)->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.'], 400);
        }
    
        // Fetch customer's default addresses if billing/shipping are not in the request
        $billingAddress = [
            'billing_full_name'     => $request->input('billing_full_name', $customer->billing_full_name),
            'billing_phone_number'  => $request->input('billing_phone_number', $customer->billing_phone_number),
            'billing_country_region'=> $request->input('billing_country_region', $customer->billing_country_region),
            'billing_city'          => $request->input('billing_city', $customer->billing_city),
            'billing_state'         => $request->input('billing_state', $customer->billing_state),
            'billing_email'         => $request->input('billing_email', $customer->billing_email),
            'billing_postal_code'   => $request->input('billing_postal_code', $customer->billing_postal_code),
            'billing_street_address'=> $request->input('billing_street_address', $customer->billing_street_address),
            'billing_municipality'  => $request->input('billing_municipality', $customer->billing_municipality),
            'billing_ordernote'     => $request->input('billing_ordernote', null),
        ];
    
        $shippingAddress = [
            'shipping_full_name'     => $request->input('shipping_full_name', $customer->shipping_full_name),
            'shipping_phone_number'  => $request->input('shipping_phone_number', $customer->shipping_phone_number),
            'shipping_country_region'=> $request->input('shipping_country_region', $customer->shipping_country_region),
            'shipping_city'          => $request->input('shipping_city', $customer->shipping_city),
            'shipping_state'         => $request->input('shipping_state', $customer->shipping_state),
            'shipping_email'         => $request->input('shipping_email', $customer->shipping_email),
            'shipping_postal_code'   => $request->input('shipping_postal_code', $customer->shipping_postal_code),
            'shipping_street_address'=> $request->input('shipping_street_address', $customer->shipping_street_address),
            'shipping_municipality'  => $request->input('shipping_municipality', $customer->shipping_municipality),
            'shipping_ordernote'     => $request->input('shipping_ordernote', null),
        ];
    
        // Determine delivery charge based on shipping city
        $shippingCity = $shippingAddress['shipping_city'];
        $deliveryCharge = in_array($shippingCity, ['kathmandu', 'lalitpur', 'bhaktapur']) ? 100 : 200;
    
        // Calculate order total, discount, and final net total
        $totalAmount = 0;
        $totalDiscount = 0;
        foreach ($cartItems as $item) {
            $product = Product::find($item->product_id);
            if (!$product) continue;
    
            $price = $product->price;
            $discountedPrice = $product->discounted_price ?? $price;
            $subtotal = $discountedPrice * $item->quantity;
            
            $totalAmount += ($price * $item->quantity);
            $totalDiscount += ($price - $discountedPrice) * $item->quantity;
        }
    
        $netTotal = $totalAmount - $totalDiscount + $deliveryCharge;

        if($request->post('payment_method') == "cod"){
            $pstat = "cod";
        }
        else{
            $pstat = "pending";
        }
        // Create a new order
        $order = Order::create(array_merge([
            'customer_id'          => $customer->id,
            'order_date'           => now(),
            'current_status'       => 'pending',
            'total_amount'         => $totalAmount,
            'delivery_charge'      => $deliveryCharge,
            'discount'             => $totalDiscount,
            'discounted_total'     => $totalAmount - $totalDiscount,
            'net_total'            => $totalAmount - $totalDiscount + $deliveryCharge,
            'payment_status'       => $pstat,
            'last_status_updated'  => now(),
        ], $billingAddress, $shippingAddress));
    
        // Add order items
        foreach ($cartItems as $cartItem) {
            $product = Product::find($cartItem->product_id);
            if (!$product) continue;
    
            OrderItem::create([
                'order_id'        => $order->id,
                'customer_id'     => $customer->id,
                'product_id'      => $cartItem->product_id,
                'quantity'        => $cartItem->quantity,
                'color'           => $cartItem->color,
                'price'           => $product->price,
                'discounted_price'=> $product->discounted_price ?? $product->price,
            ]);
        }
    
        // Record initial order status
        OrderStatusHistory::create([
            'user_id' => '1',
            'order_id'   => $order->id,
            'status'     => 'pending',
            'changed_at' => now(),
        ]);
    
        // (Optional) Clear customer's cart after checkout
        if ($request->post('payment_method') != "khalti") {
            Cart::where('customer_id', $customer->id)->delete();
        }
    
        return response()->json([
            'message' => 'Order placed successfully.',
            'order'   => $order->load('OrderItem', 'statusHistory'),
        ], 201);
    }
    

    public function deletePendingOrderOnFailure(Request $request)
    {
        $orderId = $request->post('order_id');

        $customer = $request->user();

        $order = Order::where('id', $orderId)->where('customer_id',$customer->id)->where('payment_status', 'pending')->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found or not pending.'], 404);
        }

        // Delete associated order items first
        $order->OrderItem()->delete();

        // Delete the order itself
        $order->delete();

        return response()->json(['message' => 'Pending order deleted due to payment failure.'], 200);
    }


    public function handlePaymentSuccess(Request $request)
    {
        $customer = $request->user(); // ✅ Get the authenticated user

        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_reference' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        // ✅ Create payment entry
        Payment::create([
            'customer_id'=>$customer->id,
            'order_id' => $validated['order_id'],
            'payment_reference' => $validated['payment_reference'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
        ]);

        // ✅ Clear the user's cart
        Cart::where('customer_id', $customer->id)->delete();

        // ✅ Update order's payment_status
        Order::where('id', $validated['order_id'])->update([
            'payment_status' => 'paid',
        ]);

        return response()->json(['message' => 'Payment successful, cart cleared, and order updated.'], 200);
    }

    public function getOrders(Request $request)
    {
        $customer = $request->user();

        $orders = Order::where('customer_id', $customer->id)
            ->whereIn('payment_status', ['paid', 'cod'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['orders' => $orders], 200);
    }

    public function getOrderDetails(Request $request, $orderId)
    {
        $customer = $request->user();

        $order = Order::where('customer_id', $customer->id)
            ->where('id', $orderId)
            ->whereIn('payment_status', ['paid', 'cod'])
            ->with('OrderItem.product', 'statusHistory', 'payments')
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        return response()->json(['order' => $order], 200);
    }
}
