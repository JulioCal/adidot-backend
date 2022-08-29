<?php

namespace App\Http\Controllers;

use App\Models\group;
use Illuminate\Http\Request;

use function GuzzleHttp\Promise\all;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $group = group::select('*')
            ->when(
                $request->has('owner'),
                function ($query) use ($request) {
                    return $query->where('owner_grupo', $request->owner);
                }
            )->get();

        return $group;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'integrantes' => 'required',
            'owner_grupo' => 'required',
        ]);
        try {
            $temp = json_decode($request->integrantes, true);

            $request->merge([
                'integrantes' => $temp
            ]);
            $group = new group();
            $group->fill($request->all());
            $group->save();
            return response()->json([
                'message' => 'Grupo creado satisfactoriamente'
            ]);
        } catch (\PDOException $e) {
            return response()->json([
                'message' => $e
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $value)
    {
        $request->validate([
            'nombre' => 'required',
            'integrantes' => 'required'
        ]);
        try {
            $group = group::where('id', $value)->firstOrFail();
            $group->fill($request->all());
            $group->save();
            return response()->json(['message' => 'Grupo actualizado con exito!']);
        } catch (\PDOException $e) {
            return response()->json([
                'message' => $e
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy($value)
    {
        $result = group::where("id", $value)->firstOrFail();

        try {
            $result->delete();

            return response()->json([
                'message' => 'Grupo borrado'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrio un problema eliminando el grupo.'
            ]);
        }
    }
}
