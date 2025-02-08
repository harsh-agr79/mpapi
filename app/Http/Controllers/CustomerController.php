<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\Product;

class CustomerController extends Controller
{
    /**
     * Toggle Wishlist
     */
    public function toggleWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $customer = $request->user(); // Get authenticated customer

        $wishlistItem = Wishlist::where('customer_id', $customer->id)
                                ->where('product_id', $request->product_id)
                                ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
        } else {
            Wishlist::create([
                'customer_id' => $customer->id,
                'product_id' => $request->product_id,
            ]);
        }

        // Fetch updated wishlist with full product details
        $wishlist = Product::whereIn('id', Wishlist::where('customer_id', $customer->id)->pluck('product_id'))->get();

        return response()->json([
            'message' => $wishlistItem ? 'Product removed from wishlist' : 'Product added to wishlist',
            'wishlist' => $wishlist
        ]);
    }

    /**
     * Add to Cart
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $customer = $request->user(); // Get authenticated customer

        $cartItem = Cart::where('customer_id', $customer->id)
                        ->where('product_id', $request->product_id)
                        ->first();

        if ($cartItem) {
            // If quantity is provided, update it; otherwise, increment by 1
            $newQuantity = $request->quantity ?? ($cartItem->quantity + 1);
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'customer_id' => $customer->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity ?? 1,
            ]);
        }

        // Fetch updated cart with product details
        $cart = Cart::where('customer_id', $customer->id)->with('product')->get();

        return response()->json([
            'message' => $cartItem ? 'Cart updated' : 'Product added to cart',
            'cart' => $cart
        ]);
    }

    /**
     * Decrement Cart Quantity
     * If quantity drops below 1, delete the cart entry.
     */
    public function decrementCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $customer = $request->user(); // Get authenticated customer

        $cartItem = Cart::where('customer_id', $customer->id)
                        ->where('product_id', $request->product_id)
                        ->first();

        if ($cartItem) {
            if ($cartItem->quantity > 1) {
                $cartItem->decrement('quantity');
            } else {
                $cartItem->delete();
            }
        }

        // Fetch updated cart with product details
        $cart = Cart::where('customer_id', $customer->id)->with('product')->get();

        return response()->json([
            'message' => $cartItem ? ($cartItem->quantity > 1 ? 'Cart quantity decreased' : 'Product removed from cart') : 'Item not found in cart',
            'cart' => $cart
        ]);
    }

    /**
     * Remove Item from Cart Directly
     */
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $customer = $request->user(); // Get authenticated customer

        Cart::where('customer_id', $customer->id)
            ->where('product_id', $request->product_id)
            ->delete();

        // Fetch updated cart with product details
        $cart = Cart::where('customer_id', $customer->id)->with('product')->get();

        return response()->json([
            'message' => 'Product removed from cart',
            'cart' => $cart
        ]);
    }

    public function getCart(Request $request)
    {
        $customer = $request->user(); // Get authenticated customer
    
        // Fetch the cart with product details for the logged-in user
        $cart = Cart::where('customer_id', $customer->id)->with('product')->get();
    
        // Initialize totals
        $totalPrice = 0;
        $totalDiscountedPrice = 0;
    
        // Calculate totals
        foreach ($cart as $item) {
            if ($item->product) { // Ensure product exists
                $price = $item->product->price ?? 0;
                $discountedPrice = $item->product->discounted_price ?? 0;
    
                // If discounted_price is null or 0, use the original price
                $finalPrice = ($discountedPrice > 0) ? $discountedPrice : $price;
    
                $totalPrice += $price * $item->quantity;
                $totalDiscountedPrice += $finalPrice * $item->quantity;
            }
        }
    
        // Net total (total after discount)
        $netTotal = $totalDiscountedPrice;
    
        return response()->json([
            'cart' => $cart,
            'total_price' => $totalPrice,
            'total_discounted_price' => $totalDiscountedPrice,
            'net_total' => $netTotal
        ]);
    }
    
    

    public function getWishlist(Request $request)
    {
        $customer = $request->user(); // Get authenticated customer

        // Fetch the wishlist with product details for the logged-in user
        $wishlist = Product::whereIn('id', Wishlist::where('customer_id', $customer->id)->pluck('product_id'))->get();

        return response()->json([
            'wishlist' => $wishlist
        ]);
    }



    public function updateBillingAddress(Request $request)
    {
        $request->validate([
            'billing_full_name' => 'required|string|max:255',
            'billing_phone_number' => 'required|string|max:20',
            'billing_country_region' => 'required|string|max:100',
            'billing_city' => 'required|string|max:100',
            'billing_state' => 'required|string|max:100',
            'billing_email' => 'required|email|max:255',
            'billing_postal_code' => 'required|string|max:20',
        ]);

        $user = $request->user(); // Get authenticated user
        $customer = Customer::find($user->id);
        $customer->update($request->only([
            'billing_full_name', 'billing_phone_number', 'billing_country_region',
            'billing_city', 'billing_state', 'billing_email', 'billing_postal_code'
        ]));

        return response()->json(['message' => 'Billing address updated successfully', 'customer' => $customer]);
    }

    public function updateShippingAddress(Request $request)
    {
        $request->validate([
            'shipping_full_name' => 'required|string|max:255',
            'shipping_phone_number' => 'required|string|max:20',
            'shipping_country_region' => 'required|string|max:100',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'required|string|max:100',
            'shipping_email' => 'required|email|max:255',
            'shipping_postal_code' => 'required|string|max:20',
        ]);

        $user = $request->user();
        $customer = Customer::find($user->id);
        $customer->update($request->only([
            'shipping_full_name', 'shipping_phone_number', 'shipping_country_region',
            'shipping_city', 'shipping_state', 'shipping_email', 'shipping_postal_code'
        ]));

        return response()->json(['message' => 'Shipping address updated successfully', 'customer' => $customer]);
    }


    public function getBillingAddress()
    {
        $user = $request->user();
        $customer = Customer::find($user->id);

        if (!$customer) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'billing_full_name' => $customer->billing_full_name,
            'billing_phone_number' => $customer->billing_phone_number,
            'billing_country_region' => $customer->billing_country_region,
            'billing_city' => $customer->billing_city,
            'billing_state' => $customer->billing_state,
            'billing_email' => $customer->billing_email,
            'billing_postal_code' => $customer->billing_postal_code,
            'billing_address' => $customer->billing_address,
        ]);
    }

    // Get Shipping Address
    public function getShippingAddress()
    {
        $user = $request->user();
        $customer = Customer::find($user->id);

        if (!$customer) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'shipping_full_name' => $customer->shipping_full_name,
            'shipping_phone_number' => $customer->shipping_phone_number,
            'shipping_country_region' => $customer->shipping_country_region,
            'shipping_city' => $customer->shipping_city,
            'shipping_state' => $customer->shipping_state,
            'shipping_email' => $customer->shipping_email,
            'shipping_postal_code' => $customer->shipping_postal_code,
            'shipping_address' => $customer->shipping_address,
        ]);
    }
}
