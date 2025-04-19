<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            'color' => 'nullable|string'
        ]);

        $customer = $request->user(); // Get authenticated customer

        $cartItem = Cart::where('customer_id', $customer->id)
                        ->where('product_id', $request->product_id)
                        ->first();

        if ($cartItem) {
            // If quantity is provided, update it; otherwise, increment by 1
            $newQuantity = $cartItem->quantity + ($request->quantity ?? 1);
            $cartItem->update(['quantity' => $newQuantity]);
            if($request->has('color')){
                $cartItem->update(['quantity' => $newQuantity, 'color'=>$request->color]);
            }
        } else {
            Cart::create([
                'customer_id' => $customer->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity ?? 1,
                'color' => $request->color ?? NULL 
            ]);
        }
        if($request->has('color')){

        }

        // Fetch updated cart with product details
        $cart = Cart::where('customer_id', $customer->id)->with('product')->get();

        $totalPrice = 0;
        $totalDiscountedPrice = 0;

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

        $netTotal = $totalDiscountedPrice;

        return response()->json([
            'message' => $cartItem ? 'Cart updated' : 'Product added to cart',
            'cart' => $cart,
            'total_price' => $totalPrice,
            'total_discounted_price' => $totalDiscountedPrice,
            'net_total' => $netTotal
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

        $totalPrice = 0;
        $totalDiscountedPrice = 0;

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

        $netTotal = $totalDiscountedPrice;

        return response()->json([
            'message' => $cartItem ? ($cartItem->quantity > 1 ? 'Cart quantity decreased' : 'Product removed from cart') : 'Item not found in cart',
            'cart' => $cart,
            'total_price' => $totalPrice,
            'total_discounted_price' => $totalDiscountedPrice,
            'net_total' => $netTotal
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

        $totalPrice = 0;
        $totalDiscountedPrice = 0;

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

        $netTotal = $totalDiscountedPrice;

        return response()->json([
            'message' => 'Product removed from cart',
            'cart' => $cart,
            'total_price' => $totalPrice,
            'total_discounted_price' => $totalDiscountedPrice,
            'net_total' => $netTotal
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
            'billing_street_address' => 'nullable|string|max:255',
            'billing_municipality' => 'nullable|string|max:255',
            'billing_ordernote' => 'nullable|string',
        ]);

        $user = $request->user(); // Get authenticated user
        $customer = Customer::find($user->id);
        $customer->update($request->only([
            'billing_full_name', 'billing_phone_number', 'billing_country_region',
            'billing_city', 'billing_state', 'billing_email', 'billing_postal_code',
            'billing_street_address',
            'billing_municipality',
            'billing_ordernote',
        ]));

        return response()->json(['message' => 'Billing address updated successfully', 'updated_address' => 
        [
            'billing_full_name' => $customer->billing_full_name,
            'billing_phone_number' => $customer->billing_phone_number,
            'billing_country_region' => $customer->billing_country_region,
            'billing_city' => $customer->billing_city,
            'billing_state' => $customer->billing_state,
            'billing_email' => $customer->billing_email,
            'billing_postal_code' => $customer->billing_postal_code,
            'billing_address' => $customer->billing_address,
            'billing_street_address' => $customer->billing_street_address,
            'billing_municipality' => $customer->billing_municipality,
            'billing_ordernote' => $customer->billing_ordernote
        ]]);
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
            'shipping_street_address' => 'nullable|string|max:255',
            'shipping_municipality' => 'nullable|string|max:255',
            'shipping_ordernote' => 'nullable|string',
        ]);

        $user = $request->user();
        $customer = Customer::find($user->id);
        $customer->update($request->only([
            'shipping_full_name', 'shipping_phone_number', 'shipping_country_region',
            'shipping_city', 'shipping_state', 'shipping_email', 'shipping_postal_code',
            'shipping_street_address',
            'shipping_municipality',
            'shipping_ordernote'
        ]));

        return response()->json(['message' => 'Shipping address updated successfully', 'updated_address' => [
            'shipping_full_name' => $customer->shipping_full_name,
            'shipping_phone_number' => $customer->shipping_phone_number,
            'shipping_country_region' => $customer->shipping_country_region,
            'shipping_city' => $customer->shipping_city,
            'shipping_state' => $customer->shipping_state,
            'shipping_email' => $customer->shipping_email,
            'shipping_postal_code' => $customer->shipping_postal_code,
            'shipping_address' => $customer->shipping_address,
            'shipping_street_address' => $customer->shipping_street_address,
            'shipping_municipality' => $customer->shipping_municipality,
            'shipping_ordernote' => $customer->shipping_ordernote
        ]]);
    }


    public function getBillingAddress(Request $request)
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
            'billing_street_address' => $customer->billing_street_address,
            'billing_municipality' => $customer->billing_municipality,
            'billing_ordernote' => $customer->billing_ordernote
        ]);
    }

    // Get Shipping Address
    public function getShippingAddress(Request $request)
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
            'shipping_street_address' => $customer->shipping_street_address,
            'shipping_municipality' => $customer->shipping_municipality,
            'shipping_ordernote' => $customer->shipping_ordernote
        ]);
    }


    public function getProvinces()
    {
        // Get distinct provinces (states) from the locations table
        $provinces = DB::table('locations')->distinct()->pluck('province');

        return response()->json($provinces);
    }

    // 2. Get districts by province name
    public function getDistrictsByProvince(Request $request)
    {
        $provinceName = $request->input('province');

        if (empty($provinceName)) {
            return response()->json(['error' => 'Province name is required'], 400);
        }

        // Get distinct districts for the given province
        $districts = DB::table('locations')->where('province', $provinceName)
                             ->distinct()
                             ->pluck('district');

        return response()->json($districts);
    }

    // 3. Get municipalities by district name
    public function getMunicipalitiesByDistrict(Request $request)
    {
        $districtName = $request->input('district');

        if (empty($districtName)) {
            return response()->json(['error' => 'District name is required'], 400);
        }

        // Get municipalities for the given district
        $municipalities = DB::table('locations')->where('district', $districtName)
                                  ->distinct()
                                  ->pluck('municipality');

        return response()->json($municipalities);
    }

    public function uploadProfilePic(Request $request)
    {
        $request->validate([
            'profile_pic' => 'required|image|mimes:jpeg,png,jpg,gif,svg,heif,heic|max:2048'
        ]);

        $cust = $request->user();
        $user = Customer::find($cust->id);

        // Delete previous profile pic if exists
        if ($user->profile_pic) {
            Storage::disk('public')->delete($user->profile_pic);
        }

        // Store the new file
        $path = $request->file('profile_pic')->store('profile_pics', 'public');

        // Update user record
        $user->profile_pic = $path;
        $user->save();

        return response()->json([
            'message' => 'Profile picture updated successfully.',
            'profile_pic_url' => asset('storage/' . $path),
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'new_password_confirmation' => 'required',
        ]);

        $cust = $request->user();
        $user = Customer::find($cust->id);

        if ($user->google_id !== null) {
            return response()->json([
                'message' => 'Password change is not allowed for Google login users.',
            ], 403);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect.',
            ], 401);
        }

        if ($request->new_password !== $request->new_password_confirmation) {
            return response()->json([
                'message' => 'New password and confirmation do not match.',
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully.',
        ]);
    }
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_no' => 'required|string|max:15', // adjust regex or rules based on format
        ]);

        $cust = $request->user();
        $user = Customer::find($cust->id);

        $user->name = $request->name;
        $user->phone_no = $request->phone_no;
        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => $user,
        ]);
    }
}
