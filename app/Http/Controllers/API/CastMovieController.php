<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CastMovie;
use App\Http\Requests\CastMovieRequest;

class CastMovieController extends Controller
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
        $castMovies = CastMovie::all();

        return response()->json([
            'message' => 'Berhasil tampil cast Movie',
            'data' => $castMovies
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CastMovieRequest $request)
    {
       // Validasi dan simpan data
       $castMovie = CastMovie::create($request->all());

       // Jika berhasil disimpan
       return response()->json([
           'message' => 'Berhasil tambah cast Movie',
       ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $castMovie = CastMovie::with(['movie', 'cast'])->find($id);

        if (!$castMovie) {
            return response()->json([
                'message' => 'Cast Movie tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => 'Berhasil tampil Cast Movie',
            'data' => $castMovie
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CastMovieRequest $request, string $id)
    {
        // Cari CastMovie berdasarkan ID
        $castMovie = CastMovie::find($id);

        if (!$castMovie) {
            return response()->json([
                'message' => 'Cast Movie tidak ditemukan'
            ], 404);
        }

        // Update data CastMovie
        $castMovie->name = $request['name'];
        $castMovie->cast_id = $request['cast_id'];
        $castMovie->movie_id = $request['movie_id'];
        $castMovie->save();

        return response()->json([
            'message' => 'Cast Movie berhasil diupdate'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Cari CastMovie berdasarkan ID
        $castMovie = CastMovie::find($id);

        if (!$castMovie) {
            return response()->json([
                'message' => 'Cast Movie tidak ditemukan'
            ], 404);
        }

        // Hapus CastMovie
        $castMovie->delete();

        return response()->json([
            'message' => 'Cast Movie berhasil dihapus'
        ], 200);
    }
}
