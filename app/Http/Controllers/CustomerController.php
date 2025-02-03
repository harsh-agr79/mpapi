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

        return response()->json([
            'cart' => $cart
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
}
