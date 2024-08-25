<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\OtpCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $roleUser = Role::where('name', 'user')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleUser->id
        ]);

        $user->generateOtpCode();

        $token = JWTAuth::fromUser($user);

        Mail::to($user->email)->queue(new RegisterMail($user));

        return response()->json([
            "message" => "Registrasi berhasil",
            "user" => $user,
            "token" => $token
        ]);
    }

    public function generateOtpCode(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);

        $UserData = User::where('email', $request->email)->first();

        $UserData->generateOtpCode();

        return response()->json([
            "message" => "Berhasil generate ulang OTP Code",
            "data" => $UserData
        ]);
    }

    public function verifikasi(Request $request){
        $request->validate([
            'otp' => 'required|integer|max:999999'
        ]);

        $otp_code = OtpCode::where('otp', $request->otp)->first();

        if(!$otp_code){
            return response()->json([
                "message" => "OTP Code tidak ditemukan"
            ], 404);
        }

        $now = Carbon::now();

        if($now > $otp_code->valid_until){
            return response()->json([
                "message" => "OTP Code sudah kadaluarsa. Silakan generate ulang."
            ], 400);
        }

        $user = User::find($otp_code->user_id);
        $user->email_verified_at = $now;

        $user->save();

        $otp_code->delete();

        return response()->json([
            "message" => "Berhasil verifikasi akun"
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'User invalid'
            ], 401);
        }

        $UserData = User::with('role')->where('email', $request->email)->first();

        $token = JWTAuth::fromUser($UserData);

        return response()->json([
            "user" => $UserData,
            "token" => $token
        ]);
    }

    public function getUser()
    {
        $user = auth()->user();

        $currentUser = User::with('Profile', 'historyReviews')->find($user->id);

        return response()->json([
            "message" => "Berhasil melakukan get user",
            "user" => $currentUser
        ]);
    }

    public function updateUser(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
        $user->name = $request->name;

        $user->save();

        return response()->json([
            'message' => 'Username berhasil diupdate',
        ]); 
    }

    public function logout()
    {
        auth()->logout();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
    
}
