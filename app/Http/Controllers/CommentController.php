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
    public function index()
    {
        $comment = DB::table('comments')->get();

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
            'parent'  => 'required',
        ]);

        try{
            return response()->json([
                'message'=>' Created Successfully!!'
            ]);
            }catch(\Exception $e){
            return response()->json([
                'message'=>'Something went wrong while creating!!'
            ],500);
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
        return response()->json([
            'trabajador'=>$comment
        ]);
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
         $request->validate([
        'owner' => 'required',
        'comment' => 'required',
        'parent'  => 'required',
        ]);

        try{

            $comment->fill($request->post())->update();

            return response()->json([
                'message'=>'Updated Successfully!!'
            ]);

        }catch(\Exception $e){
              return response()->json([
                'message'=>'Something went wrong!!'
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        try {
            $comment->delete();
    
            return response()->json([
                'message'=>'Deleted Successfully!!'
            ]);
            
            } catch (\Exception $e) {
            return response()->json([
                'message'=>'Something went wrong while deleting!!'
            ]);}
    }
}
