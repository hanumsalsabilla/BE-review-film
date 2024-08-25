<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'critic' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'movie_id' => 'required|exists:movie,id'
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $currentUser = auth()->user();

        $review = Review::updateOrCreate(
            [
                'user_id' => $currentUser->id,
                'movie_id' => $request['movie_id']
            ],
            [
                'critic' => $request['critic'],
                'rating' => $request['rating']
            ]
        );

        return response()->json([
            "message" => "Review added/updated successfully",
            "data" => $review
        ], 201);
    }

    public function index($movieId)
    {
        $reviews = Review::where('movie_id', $movieId)->with('user')->get();
        return response()->json($reviews);
    }
}