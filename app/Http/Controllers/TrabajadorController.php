<?php

namespace App\Http\Controllers;

use App\Models\trabajador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrabajadorController extends Controller
{
    public function authenticate(Request $request)
    {
        if (!Auth::attempt($request->only('cedula', 'password'))) {
            return response()->json([
                'message' => 'Invalid access credentials'
            ], 401);
        }

        //Busca al usuario en la base de datos
        $user = trabajador::where('cedula', $request['cedula'])->firstOrFail();

        //Genera un nuevo token para el usuario
        $token = $user->createToken('auth_token')->plainTextToken;

        //devuelve una respuesta JSON con el token generado y el tipo de token
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function dataUser(Request $request)
    {
        //devuelve la información del usuario
        return $request->user();
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'logged out.'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $workers = trabajador::select('nombre', 'cedula', 'role', 'gerencia')
            ->when(
                $request->has('gerencia'),
                function ($query) use ($request) {
                    $query->where('gerencia', $request->gerencia);
                }
            )->get();

        return $workers;
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'cedula' => 'required',
            'email'  => 'required',
            'password' => 'required',
            'role' => 'required',
            'sexo' => 'required'
        ]);

        try {
            $hashed = Hash::make($request->password);

            $request->merge([
                'password' => $hashed
            ]);
            $trabajador = new trabajador;
            $trabajador->fill($request->all());
            $trabajador->save();
            return response()->json([
                'message' => 'Registro completado.'
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
     * @param  \App\Models\trabajador  $trabajador
     * @return \Illuminate\Http\Response
     */
    public function show($value)
    {
        $trabajador = trabajador::where('cedula', $value)->firstOrFail();
        return $trabajador;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\trabajador  $trabajador
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $value)
    {
        try {
            $request->whenHas('password', function () use ($request) {
                $hashed = Hash::make($request->password);
                $request->merge([
                    'password' => $hashed
                ]);
            });
            $trabajador = trabajador::where('cedula', $value)->firstOrFail();
            $trabajador->fill($request->all());
            $trabajador->save();
            return response()->json([
                'message' => 'Registro Actualizado.'
            ]);
        } catch (\PDOException $e) {
            return response()->json([
                'message' => $e
            ], 500);
        }
    }

    public function passwordReset(Request $request)
    {
        $request->whenHas('password', function () use ($request) {
            $hashed = Hash::make($request->password);
            $request->merge([
                'password' => $hashed
            ]);
        });

        try {
            $user = DB::table('password_resets')->where('token', $request->token)->first();
            $trabajador = trabajador::where('email', $user->email)->firstOrFail();
            $trabajador->fill($request->only('password'));
            $trabajador->save();
            DB::table('password_resets')->where('token', $request->token)->delete();
            return response()->json([
                'message' => 'Contraseña Actualizada'
            ]);
        } catch (\PDOException $e) {
            return response()->json([
                'message' => $e
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\trabajador  $trabajador
     * @return \Illuminate\Http\Response
     */
    public function destroy($cedula)
    {
        $trabajador = trabajador::where('cedula', $cedula)->firstOrFail();
        try {
            $trabajador->delete();

            return response()->json([
                'message' => 'Registro borrado'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e
            ]);
        }
    }
}
