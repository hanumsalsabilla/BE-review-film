<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function store (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'age' => 'required|integer',
            'biodata' => 'required|string',
            'address' => 'required|string'
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $currentUser = auth()->user();

        $profileData = Profile::updateOrCreate(
            ['user_id' => $currentUser->id],
            [
                'age' => $request['age'],
                'biodata' => $request['biodata'],
                'address' => $request['address'],
                'user_id' => $currentUser->id
            ]
            );
       
        return response()->json([
            "message" => "Tambah/update profil user",
            "data" => $profileData
        ], 201);
    }

    public function index()
    {
        $currentUser = auth()->user();
        $profile = Profile::where('user_id', $currentUser->id)->first();
        
        return response()->json([
            "message" => "Tampil data profil",
            "user" => $currentUser,
            "profile" => $profile
        ], 200);
    }
    
}
