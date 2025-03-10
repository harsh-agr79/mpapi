<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ForgotPassword;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller {
    public function register( Request $request ) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => ['required', 'string', 'min:8', 'regex:/[A-Za-z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'],
            'phone_no' => 'required|digits:10|unique:customers,phone_no',
        ]);        

        if ( $validator->fails() ) {
            return response()->json( $validator->errors(), 422 );
        }

        $customer = Customer::create( [
            'name' => $request->name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'password' => Hash::make( $request->password ),
        ] );

        // Send verification email
        event( new Registered( $customer ) );

        return response()->json( [
            'message' => 'Registration successful, please verify your email.'
        ], 201 );
    }

    public function resendVerificationEmail( Request $request ) {
        if ( $request->user()->hasVerifiedEmail() ) {
            return response()->json( [ 'message' => 'Email already verified.' ], 200 );
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json( [ 'message' => 'Verification link sent.' ], 200 );
    }

    public function verifyEmail( Request $request, $id, $hash ) {
        $customer = Customer::findOrFail( $id );

        if ( ! hash_equals( ( string ) $hash, sha1( $customer->getEmailForVerification() ) ) ) {
            return redirect("https://mp-front.vercel.app/sign-in?emailverified=Invalid_verification_link");
        }

        if ( $customer->hasVerifiedEmail() ) {
            return redirect("https://mp-front.vercel.app/sign-in?emailverified=Email_already_verfied");
        }

        $customer->markEmailAsVerified();

        return redirect("https://mp-front.vercel.app/sign-in?emailverified=Email_Verified");
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // Apply rate limiting for login attempts
        if (RateLimiter::tooManyAttempts('login:'.$request->ip(), 5)) {
            return response()->json(['error' => 'Too many login attempts. Please try again later.'], 429);
        }
    
        $credentials = $request->only('email', 'password');
    
        if (Auth::guard('customer')->attempt($credentials)) {
            $user = Auth::guard('customer')->user();
    
            // Check if email is verified
            if (!$user->hasVerifiedEmail()) {
                return response()->json(['error' => 'Email not verified.'], 403);
            }
    
            // Generate token
            $token = $user->createToken('auth_token')->plainTextToken;
    
            // Clear failed attempts on successful login
            RateLimiter::clear('login:'.$request->ip());
    
            return response()->json(['token' => $token, 'user' => $user], 200)->cookie('auth_token', $token, 60 * 24, '/', null, true, true);
        }
    
        // Increment failed attempts if login fails
        RateLimiter::hit('login:'.$request->ip());
    
        return response()->json(['error' => 'Unauthorized login'], 401);
    }
    
    

    public function sendResetLinkEmail( Request $request ) {
        // Validate email
        $validator = Validator::make( $request->all(), [
            'email' => 'required|email|exists:customers,email',
        ] );

        if (RateLimiter::tooManyAttempts('password-reset:'.$request->ip(), 5)) {
            return response()->json(['error' => 'Too many password reset attempts. Please try again later.'], 429);
        }        

        if ( $validator->fails() ) {
            return back()->withErrors( $validator )->withInput();
        }

        // Find the user by email
        $user = Customer::where( 'email', $request->email )->first();

        // Create a random token for resetting password
        $token = Str::random( 60 );

        // Save token in password_resets table
        \DB::table( 'customers' )->where( 'email', $request->email )->update(
            [
                'email_enc' => Hash::make( $user->email ),
                'token_fp' => Hash::make( $token ),
                'fp_at' => now()
            ]
        );

        // Send reset password email
        Mail::to( $user->email )->send( new ForgotPassword( $user, Crypt::encryptString( $token ), Crypt::encryptString( $user->email ) ) );

        return response()->json( 'Email Has Been Sent', 200 );
    }

    public function rp_validateCreds(Request $request) {
        $email = Crypt::decryptString($request->email);
        $token = Crypt::decryptString($request->token);
    
        $user = DB::table('customers')->where('email', $email)->first();
    
        if ($user) {
            // Check if the token has expired (example: 60 minutes validity)
            $tokenExpiryTime = now()->subMinutes(60);
            if ($user->fp_at < $tokenExpiryTime) {
                return response()->json('Token has expired', 400);
            }
    
            // Check if the token and email match
            if (Hash::check($token, $user->token_fp) && Hash::check($email, $user->email_enc)) {
                return response()->json($request->email, 200);
            } else {
                return response()->json('Invalid credentials', 400);
            }
        }
        return response()->json('Invalid credentials', 400);
    }
    

    public function set_newpass(Request $request) {
        $email = Crypt::decryptString($request->token);
        $password = $request->password;
    
        $user = DB::table('customers')->where('email', $email)->first();
        if (Hash::check($email, $user->email_enc)) {
            DB::table('customers')->where('email', $email)->update([
                'password' => Hash::make($request->password),
                'email_enc'=> Str::random(60),
                'token_fp'=> Hash::make(Str::random(60)),
                'fp_at'=> NULL,
            ]);
    
            // Revoke all tokens after password reset
            DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->delete();
    
            return response()->json('Password Changed', 200);
        } else {
            return response()->json('Error', 406);
        }
    }    
}