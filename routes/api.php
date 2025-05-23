<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
->middleware(['signed', 'throttle:6,1'])
->name('verification.verify');

Route::group(['middleware'=>'api_key'], function () {

    Route::post('/login', [AuthController::class, 'login']); //
    Route::post('/register', [AuthController::class, 'register']); //
    Route::post('/google-login', [AuthController::class, 'googleLogin']);

    Route::post('/forgotpwd', [AuthController::class, 'sendResetLinkEmail']); //
    Route::post('/resetpwd/validatecredentials', [AuthController::class, 'rp_validateCreds']); //
    Route::post('/resetpwd/newpwd', [AuthController::class, 'set_newpass']); //

    Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail'])
    ->middleware(['auth:sanctum', 'throttle:6,1'])
    ->name('verification.resend');

    Route::get('/privacypolicy', [PolicyController::class, 'getPrivacyPolicy']);
    Route::get('/warrantypolicy', [PolicyController::class, 'getWarrantyPolicy']);
    Route::get('/terms', [PolicyController::class, 'getTerms']);
    Route::get('/shippingreturns', [PolicyController::class, 'getShippings']);
    Route::get('/signimage', [HomePageController::class, 'getSignImage']);

    Route::get('/getfaqs', [FAQController::class, 'getFaq']);

    Route::get('/getcategory', [InventoryController::class, 'getCategory']);
    Route::get('/getproductlist', [InventoryController::class, 'getProducts']);
    Route::get('/getproductlist2', [InventoryController::class, 'getProducts2']);
    Route::get('/getcolors', [InventoryController::class, 'getAvailableColors']);
    Route::get('/getproduct/{id}', [InventoryController::class, 'getProductDetail']);

    Route::get('/getaboutus', [AboutUsController::class, 'getAboutUs']);
    Route::get('/gethomepage', [HomePageController::class, 'getHomePageData']);

    Route::get('/getBlogs', [BlogController::class, 'getBlog']);
    Route::get('/getBlog/{id}', [BlogController::class, 'getBlogContent']);

    Route::post('/contact-messages', [ContactMessageController::class, 'store']);
    Route::get('/contact-us', [ContactMessageController::class, 'getContactInfo']);

    Route::get('/provinces', [CustomerController::class, 'getProvinces']); //done
    Route::post('/districts', [CustomerController::class, 'getDistrictsByProvince']); //done
    Route::post('/municipalities', [CustomerController::class, 'getMunicipalitiesByDistrict']); //done

    Route::post('/searchlog', [HomePageController::class, 'logSearch']);

   
    

    Route::middleware(['auth:sanctum', 'verified'])->group(function () {   

        Route::post('/customer/profile-pic', [CustomerController::class, 'uploadProfilePic']);
        Route::post('/customer/change-password', [CustomerController::class, 'changePassword']);
        Route::post('/customer/update-profile', [CustomerController::class, 'updateProfile']);
        
        Route::get('cart', [CustomerController::class, 'getCart']);
        Route::get('wishlist', [CustomerController::class, 'getWishlist']);
        Route::post('/wishlist/toggle', [CustomerController::class, 'toggleWishlist']);
        Route::post('/cart/add', [CustomerController::class, 'addToCart']);
        Route::post('/cart/decrement', [CustomerController::class, 'decrementCart']);
        Route::post('/cart/remove', [CustomerController::class, 'removeFromCart']);

        Route::post('/product/review', [InventoryController::class, 'addReview']);

        Route::post('/customer/update-billing-address', [CustomerController::class, 'updateBillingAddress']);
        Route::post('/customer/update-shipping-address', [CustomerController::class, 'updateShippingAddress']);
        Route::get('/customer/billing-address', [CustomerController::class, 'getBillingAddress']);
        Route::get('/customer/shipping-address', [CustomerController::class, 'getShippingAddress']);

        Route::post('/apply-coupon', [OrderController::class, 'applyCoupon']);
        Route::post('/checkout', [OrderController::class, 'checkout']);
        Route::post('/orders/payment-success', [OrderController::class, 'handlePaymentSuccess']);
        Route::post('/orders/delete-on-failure', [OrderController::class, 'deletePendingOrderOnFailure']);

        Route::get('/orders', [OrderController::class, 'getOrders']);
        Route::get('/orders/{orderId}', [OrderController::class, 'getOrderDetails']);

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/check-token', [AuthController::class, 'checkToken']);
     });
 });
