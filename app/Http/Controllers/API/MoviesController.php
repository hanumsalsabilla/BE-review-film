<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovieRequest;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class MoviesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['isPublic'])->only(['index', 'show']);
        $this->middleware(['auth:api', 'isAdmin'])->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Movie::all();

        return response()->json([
            "message" => "Tampil Movie berhasil",
            "data" => $movies
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MovieRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('poster')) {
            $posterName = time() . '.' . $request->poster->extension();
            $request->poster->storeAs('public/poster', $posterName);
            $data['poster'] = url('storage/poster/' . $posterName);
        }

        Movie::create($data);
        return response()->json([
            "message" => "Data berhasil ditambahkan"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $movieData = Movie::with(['genre', 'listCasts', 'listReviews.user'])->find($id);

        if (!$movieData) {
            return response()->json([
                "message" => "Movie ID tidak ditemukan"
            ], 404);
        }

        return response()->json([
            'message' => 'Data Detail ditampilkan',
            'data' => $movieData
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MovieRequest $request, string $id)
    {
        $data = $request->validated();
        $movie = Movie::find($id);
    
        if (!$movie) {
            return response()->json([
                "message" => "Movie ID tidak ditemukan"
            ], 404);
        }
    
        if ($request->hasFile('poster')) {
            if ($movie->poster) {
                $namePoster = basename($movie->poster);
                Storage::delete('public/poster/' . $namePoster);
            }
            $posterName = time() . '.' . $request->poster->extension();
            $request->poster->storeAs('public/poster', $posterName);
            $data['poster'] = url('storage/poster/' . $posterName);
        }
    
        $movie->update($data);
    
        return response()->json([
            "message" => "Data berhasil diupdate",
            "data" => $movie
        ], 200);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json([
                "message" => "Movie ID tidak ditemukan"
            ], 404);
        }

        if ($movie->poster) {
            $namePoster = basename($movie->poster);
            Storage::delete('public/poster/' . $namePoster);
        }
        $movie->delete();
        return response()->json([
            "message" => "Data berhasil dihapus"
        ], 200);
    }
}
