<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /** 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $comment = Comment::select('*')
            ->when(
                $request->has('document'),
                function ($query) use ($request) {
                    $query
                        ->where('document_id', $request->document);
                }
            )
            ->get();
        return $comment;
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
            'owner' => 'required',
            'comment' => 'required',
            'document_id'  => 'required',
        ]);

        try {
            $comment = new Comment;
            $comment->fill($request->all());
            $comment->save();
            return response()->json([
                'message' => 'Comentario añadido al sistema'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error al procesar el comentario'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //editar comentarios?
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($value)
    {
        $result = Comment::where("id", $value)->firstOrFail();

        try {
            $result->delete();

            return response()->json([
                'message' => 'Comentario erradicado'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrio un problema eliminando el comentario.'
            ]);
        }
    }
}
