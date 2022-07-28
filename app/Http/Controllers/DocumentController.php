<?php

namespace App\Http\Controllers;

use App\Models\document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response as HttpResponse;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $documents = document::select("*")
            ->when(
                $request->has('owner'),
                function ($query) use ($request) {
                    $query->where('trabajador_cedula', $request->owner)->orWhere('permit', 'public');
                }
            )
            ->when(
                $request->has('permit') == 'public',
                function ($query) use ($request) {
                    $query->where('permit', $request->permit);
                }
            )
            ->get();

        return $documents;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $imageName = '';
        $request->validate([
            'owner' => 'required',
            'title' => 'required',
            'trabajador_cedula' => 'required'
        ]);
        try {
            $request->whenHas(
                'file',
                function () use ($request) {
                    $validator = Validator::make($request->all(), [
                        'file' => 'mimes:doc,docx,xls,xlsx,ppt,pptx,zip,pdf,txt,csv,png,jpg,jpeg|max:320480',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['error' => $validator->errors()], 401);
                    }
                    $imageName = time() . '.' . $request->file('file')->getClientOriginalExtension();
                    $request->file('file')->move(public_path('/documentos'), $imageName);
                }
            );
            $document = new document;
            $document->fill($request->all());
            $document->file = $imageName;
            $document->save();
            return response()->json([
                'message' => 'Documento publicado'
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
     * @param  \App\Models\document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(document $document)
    {
        $result = document::find($document);

        return $result;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, document $document)
    {

        $request->validate([
            'title' => 'required',
            'permit' => 'required'
        ]);
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:doc,docx,pdf,txt,csv,png,jpg,jpeg|max:320480',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
            $path = $request->file('file')->store('public/documentos');
            $document->file = $path;
            $document->fill($request->all());
            $document->save();

            return response()->json([
                'message' => 'Publicacion Actualizada.'
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
     * @param  \App\Models\document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(document $document)
    {
        $result = document::find($document);

        $result->delete();

        return response()->json([
            'message' => 'Deleted Successfully!!'
        ]);
    }

    public function getFile(Request $path)
    {
        $file = public_path() . '/documentos/' . $path->file;
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        if (file_exists($file)) {
            return response()->download($file, $path->title . '.' . $extension);
        } else {
            return response()->json(['message' => $file], 500);
        }
    }
}
