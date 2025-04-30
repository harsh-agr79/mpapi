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
use App\Models\Coupon;
use App\Mail\OrderStatusUpdated;
use App\Mail\NewOrderNotification;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class OrderController extends Controller {

    public function applyCoupon( Request $request ) {
        $user = $request->user();
        $code = $request->input( 'coupon_code' );

        $cartItems = Cart::where( 'customer_id', $user->id )->get();

        if ( $cartItems->isEmpty() ) {
            return response()->json( [
                'message' => 'Your cart is empty.'
            ], 400 );
        }

        $coupon = Coupon::where( 'code', $code )
        ->where( 'is_active', true )
        ->first();

        if ( !$coupon ) {
            return response()->json( [
                'message' => 'Invalid or inactive coupon.'
            ], 404 );
        }

        $now = Carbon::now();

        if ( $coupon->start_date && $coupon->start_date->gt( $now ) ) {
            return response()->json( [
                'message' => 'Coupon is not yet valid.'
            ], 400 );
        }

        if ( $coupon->end_date && $coupon->end_date->lt( $now ) ) {
            return response()->json( [
                'message' => 'Coupon has expired.'
            ], 400 );
        }

        $appliesToProducts = $coupon->applies_to_products ?? [];
        $appliesToCategories = $coupon->applies_to_categories ?? [];

        $cartTotal = 0;
        $eligibleTotal = 0;

        foreach ( $cartItems as $item ) {
            $product = Product::find( $item->product_id );
            if ( !$product ) continue;

            $price = $product->discounted_price ?? $product->price;
            $lineTotal = $price * $item->quantity;

            $cartTotal += $lineTotal;

            $isProductMatch = empty( $appliesToProducts ) || in_array( $product->id, $appliesToProducts );
            $isCategoryMatch = empty( $appliesToCategories ) || in_array( $product->category_id, $appliesToCategories );

            if ( $isProductMatch && $isCategoryMatch ) {
                $eligibleTotal += $lineTotal;
            }
        }

        if ( $eligibleTotal < $coupon->minimum_order_amount ) {
            return response()->json( [
                'message' => 'Coupon is Not Applicable for your Cart'
            ], 400 );
        }

        if ( $coupon->type === 'free_shipping' ) {
            return response()->json( [
                'free_shipping' => true,
                'cart_total' =>( int ) round( $cartTotal ),
                'message' => 'Free shipping applied.'
            ], 200 );
        }

        $discount = 0;
        if ( $coupon->type === 'fixed' ) {
            $discount = $coupon->discount_amount;
        } elseif ( $coupon->type === 'percentage' ) {
            $discount = ( $eligibleTotal * $coupon->discount_amount ) / 100;
        }

        $discount = ( int ) round( $discount );
        $cartTotal = ( int ) round( $cartTotal );
        $totalAfterDiscount = $cartTotal - $discount;

        return response()->json( [
            'discount' => $discount,
            'cart_total' => $cartTotal,
            'total_after_discount' => $totalAfterDiscount,
            'message' => 'Coupon applied successfully.'
        ], 200 );
    }

    // public function checkout( Request $request ) {
    //     // Get the authenticated customer
    //     $customer = $request->user();

    //     // Retrieve cart items for the customer
    //     $cartItems = Cart::where( 'customer_id', $customer->id )->get();
    //     if ( $cartItems->isEmpty() ) {
    //         return response()->json( [ 'message' => 'Your cart is empty.' ], 400 );
    //     }

    //     // Fetch customer's default addresses if billing/shipping are not in the request
    //     $billingAddress = [
    //         'billing_full_name'     => $request->input('billing_full_name', $customer->billing_full_name),
    //         'billing_phone_number'  => $request->input('billing_phone_number', $customer->billing_phone_number),
    //         'billing_country_region'=> $request->input('billing_country_region', $customer->billing_country_region),
    //         'billing_city'          => $request->input('billing_city', $customer->billing_city),
    //         'billing_state'         => $request->input('billing_state', $customer->billing_state),
    //         'billing_email'         => $request->input('billing_email', $customer->billing_email),
    //         'billing_postal_code'   => $request->input('billing_postal_code', $customer->billing_postal_code),
    //         'billing_street_address'=> $request->input('billing_street_address', $customer->billing_street_address),
    //         'billing_municipality'  => $request->input('billing_municipality', $customer->billing_municipality),
    //         'billing_ordernote'     => $request->input('billing_ordernote', null),
    //     ];
    
    //     $shippingAddress = [
    //         'shipping_full_name'     => $request->input('shipping_full_name', $customer->shipping_full_name),
    //         'shipping_phone_number'  => $request->input('shipping_phone_number', $customer->shipping_phone_number),
    //         'shipping_country_region'=> $request->input('shipping_country_region', $customer->shipping_country_region),
    //         'shipping_city'          => $request->input('shipping_city', $customer->shipping_city),
    //         'shipping_state'         => $request->input('shipping_state', $customer->shipping_state),
    //         'shipping_email'         => $request->input('shipping_email', $customer->shipping_email),
    //         'shipping_postal_code'   => $request->input('shipping_postal_code', $customer->shipping_postal_code),
    //         'shipping_street_address'=> $request->input('shipping_street_address', $customer->shipping_street_address),
    //         'shipping_municipality'  => $request->input('shipping_municipality', $customer->shipping_municipality),
    //         'shipping_ordernote'     => $request->input('shipping_ordernote', null),
    //     ];
    
    //     // Determine delivery charge based on shipping city
   
    // }

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
        $deliveryCharge = in_array(strtolower($shippingCity), ['kathmandu', 'lalitpur', 'bhaktapur']) ? 100 : 200;
    

        if($request->has('coupon_code') && !is_null($request->input('coupon_code'))){
            $couponCode = $request->input('coupon_code');
            $coupon = null;
            $couponDiscount = 0;
            $freeShipping = false;
            $couponEligibleAmount = 0;
        
            if ($couponCode) {
                // Check if the coupon is valid
                $coupon = Coupon::where('code', $couponCode)
                                ->where('is_active', true)
                                ->where('start_date', '<=', now())
                                ->where('end_date', '>=', now())
                                ->first();
        
                // If the coupon is invalid, return an error response
                if (!$coupon) {
                    return response()->json(['message' => 'Invalid or expired coupon code.'], 400);
                }
            }
        
            // Calculate total amount, discount, and coupon discount
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
        
                // Check if the product is eligible for the coupon
                if ($coupon) {
                    $isProductMatch = is_array($coupon->applies_to_products) && in_array($product->id, $coupon->applies_to_products);
                    $isCategoryMatch = is_array($coupon->applies_to_categories) && in_array($product->category_id, $coupon->applies_to_categories);
                    $isGlobal = empty($coupon->applies_to_products) && empty($coupon->applies_to_categories);
        
                    if ($isProductMatch || $isCategoryMatch || $isGlobal) {
                        $couponEligibleAmount += $subtotal;
                    }
                }
            }
        
            // Apply coupon if eligible
            if ($coupon && $couponEligibleAmount >= $coupon->minimum_order_amount) {
                if ($coupon->type === 'free_shipping') {
                    $freeShipping = true;
                    $deliveryCharge = 0;
                } elseif ($coupon->type === 'fixed') {
                    $couponDiscount = (int) $coupon->discount_amount;
                } elseif ($coupon->type === 'percentage') {
                    $couponDiscount = (int) round($couponEligibleAmount * ($coupon->discount_amount / 100));
                }
            } else {
                // If the coupon does not meet minimum order requirements, fail the checkout
                return response()->json(['message' => 'Coupon does not meet the minimum order amount requirement.'], 400);
            }
        
            // Recalculate net total after coupon discount
            $netTotal = $totalAmount - $totalDiscount - $couponDiscount + $deliveryCharge;
        
            // Determine payment status
            if ($request->post('payment_method') == "cod") {
                $pstat = "cod";
            } else {
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
                'coupon_discount'      => $couponDiscount,
                'free_shipping'        => $freeShipping,
                'coupon_code'          => $couponCode,
                'discounted_total'     => $totalAmount - $totalDiscount,
                'net_total'            => $netTotal,
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
            if ($request->post('payment_method') != 'khalti') {
                Cart::where('customer_id', $customer->id)->delete();
            }
        
            // Send confirmation email
            Mail::to($customer->email)->send(new OrderStatusUpdated($order));
            Mail::to($customer->email)->send(new OrderStatusUpdated($order));
        
            return response()->json([
                'message' => 'Order placed successfully.',
                'order'   => $order->load('OrderItem', 'statusHistory'),
            ], 201);
        }
        else{
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
        if ( $request->post( 'payment_method' ) != 'khalti' ) {
            Cart::where( 'customer_id', $customer->id )->delete();
        }
        Mail::to( $customer->email )->queue( new OrderStatusUpdated( $order ) );
        Mail::to([
            'sales.mypowernepal@gmail.com',
            'raahulpoudel2015@gmail.com',
            'manu2721@gmail.com',
            'agrharsh7932@gmail.com'
        ])->queue(new NewOrderNotification($order));

        return response()->json( [
            'message' => 'Order placed successfully.',
            'order'   => $order->load( 'OrderItem', 'statusHistory' ),
        ], 201 );
        }
    }
    
    

    public function deletePendingOrderOnFailure( Request $request ) {
        $orderId = $request->post( 'order_id' );

        $customer = $request->user();

        $order = Order::where( 'id', $orderId )->where( 'customer_id', $customer->id )->where( 'payment_status', 'pending' )->first();

        if ( !$order ) {
            return response()->json( [ 'message' => 'Order not found or not pending.' ], 404 );
        }

        // Delete associated order items first
        $order->OrderItem()->delete();

        // Delete the order itself
        $order->delete();

        return response()->json( [ 'message' => 'Pending order deleted due to payment failure.' ], 200 );
    }

    public function handlePaymentSuccess( Request $request ) {
        $customer = $request->user();
        // ✅ Get the authenticated user

        $validated = $request->validate( [
            'order_id' => 'required|exists:orders,id',
            'payment_reference' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ] );

        // ✅ Create payment entry
        Payment::create( [
            'customer_id'=>$customer->id,
            'order_id' => $validated[ 'order_id' ],
            'payment_reference' => $validated[ 'payment_reference' ],
            'amount' => $validated[ 'amount' ],
            'payment_method' => $validated[ 'payment_method' ],
        ] );

        // ✅ Clear the user's cart
        Cart::where('customer_id', $customer->id)->delete();

        // ✅ Update order's payment_status
        Order::where( 'id', $validated[ 'order_id' ] )->update( [
            'payment_status' => 'paid',
        ] );

        return response()->json( [ 'message' => 'Payment successful, cart cleared, and order updated.' ], 200 );
    }

    public function getOrders( Request $request ) {
        $customer = $request->user();

        $orders = Order::where( 'customer_id', $customer->id )
        ->whereIn( 'payment_status', [ 'paid', 'cod' ] )
        ->orderBy( 'created_at', 'desc' )
        ->get();

        return response()->json( [ 'orders' => $orders ], 200 );
    }

    public function getOrderDetails( Request $request, $orderId ) {
        $customer = $request->user();

        $order = Order::where( 'customer_id', $customer->id )
        ->where( 'id', $orderId )
        ->whereIn( 'payment_status', [ 'paid', 'cod' ] )
        ->with( 'OrderItem.product', 'statusHistory', 'payments' )
        ->first();

        if ( !$order ) {
            return response()->json( [ 'message' => 'Order not found.' ], 404 );
        }

        return response()->json( [ 'order' => $order ], 200 );
    }
}
