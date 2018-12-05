<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Entretien;
use App\Document;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($e_id)
    {
        $entretien = Entretien::find($e_id);
        $documents = $entretien->documents;
        return view('documents.index', ['documents' => $documents, 'e'=> $entretien]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($e_id)
    {
        ob_start();
        $entretien = Entretien::find($e_id);
        echo view('documents.form', ['e' => $entretien]);
        $content = ob_get_clean();
        return ['title' => 'Ajouter un document', 'content' => $content];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($e_id, Request $request)
    {
        if($request->id == null ){
            $document = new Document();
        }else{
            $document = Document::find($request->id);
        }
        $document->titre = $request->titre;
        $document->apropos = $request->apropos;
        if($file = $request->hasFile('fichier')) {
            $file = $request->file('fichier') ;
            $fileName = time()."_".$file->getClientOriginalName() ;
            $destinationPath = public_path('/documents') ;
            $file->move($destinationPath,$fileName);
            $document->fichier = $fileName ;
        }
        $document->entretien_id = $e_id;
        $document->save();
        if($document->save()) {
            return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
        } else {
            return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($e_id, $id)
    {
        ob_start();
        $entretien = Entretien::find($e_id);
        $document = Document::find($id);
        echo view('documents.form', ['d' => $document, 'e'=>$entretien]);
        $content = ob_get_clean();
        return ['title' => 'Modifier un document', 'content' => $content];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
