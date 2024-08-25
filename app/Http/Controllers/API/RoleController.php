<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles= Role::all();
        return response()->json([
            "message" => 'Tampil semua role',
            "data" => $roles,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        Role::create($request->all());
        return response()->json([
            "message" => "Berhasil tambah Role"
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::find($id);

        if(!$role){
            return response()->json([
                "message" => "ID tidak ditemukan"
            ], 404);
        }

        $role->name = $request['name'];

        $role->save();

        return response()->json([
            "message" => "Berhasil melakukan update Role ID: $id"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);

        if(!$role){
            return response()->json([
                "message" => "ID tidak ditemukan"
            ], 404);
        }

        $role->delete();

        return response()->json([
            "message" => "Data dengan ID: $id berhasil dihapus"
        ]);
    }
}
