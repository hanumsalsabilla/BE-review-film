<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cast;
use App\Http\Requests\castsRequest;

class CastsController extends Controller
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
        $casts = Cast::all();
        return response()->json([
            "message" => 'Tampil semua cast',
            "data" => $casts,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(castsRequest $request)
    {
        Cast::create($request->all());
        return response()->json([
            "message" => "Berhasil tambah Cast"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cast = Cast::with('movies')->find($id);

        if(!$cast){
            return response()->json([
                "message" => "ID tidak ditemukan"
            ], 404);
        }

        return response()->json([
            "message" => "Berhasil Detail data dengan id $id",
            "data" => $cast->load('movies')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(castsRequest $request, string $id)
    {
        $cast = Cast::find($id);

        if(!$cast){
            return response()->json([
                "message" => "ID tidak ditemukan"
            ], 404);
        }

        $cast->name = $request['name'];
        $cast->age = $request['age'];
        $cast->bio = $request['bio'];

        $cast->save();

        return response()->json([
            "message" => "Berhasil melakukan update Cast ID: $id"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cast = Cast::find($id);

        if(!$cast){
            return response()->json([
                "message" => "ID tidak ditemukan"
            ], 404);
        }

        $cast->delete();

        return response()->json([
            "message" => "Data dengan ID: $id berhasil dihapus"
        ]);
    }
}
