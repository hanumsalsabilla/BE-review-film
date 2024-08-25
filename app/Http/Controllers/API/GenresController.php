<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenresRequest;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenresController extends Controller
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
        $genres= Genre::all();
        return response()->json([
            "message" => 'Tampil semua genre',
            "data" => $genres,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GenresRequest $request)
    {
        Genre::create($request->all());
        return response()->json([
            "message" => "Berhasil tambah Genre"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $genre = Genre::with('listMovies')->find($id);

        if (!$genre) {
            return response()->json([
                "message" => "ID tidak ditemukan"
            ], 404);
        }

        return response()->json([
            "message" => "Berhasil Detail data dengan id $id",
            "data" => $genre
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $genre = Genre::find($id);

        if(!$genre){
            return response()->json([
                "message" => "ID tidak ditemukan"
            ], 404);
        }

        $genre->name = $request['name'];

        $genre->save();

        return response()->json([
            "message" => "Berhasil melakukan update Genre ID: $id"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $genre = Genre::find($id);

        if(!$genre){
            return response()->json([
                "message" => "ID tidak ditemukan"
            ], 404);
        }

        $genre->delete();

        return response()->json([
            "message" => "Data dengan ID: $id berhasil dihapus"
        ]);
    }
}
