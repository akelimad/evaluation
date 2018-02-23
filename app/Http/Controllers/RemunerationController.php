<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Entretien;
use App\Remuneration;

class RemunerationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($e_id)
    {
        $entretien = Entretien::find($e_id);
        $remunerations = $entretien->remunerations;
        return view('remunerations.index', ['remunerations' => $remunerations, 'e'=> $entretien]);
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
        echo view('remunerations.form', ['e' => $entretien]);
        $content = ob_get_clean();
        return ['title' => 'Ajouter une rémunération', 'content' => $content];
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
            $document = new Remuneration();
        }else{
            $document = Remuneration::find($request->id);
        }
        $document->type = $request->type;
        $document->amount = $request->amount;
        $document->reason = $request->reason;
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
        $remuneration = Remuneration::find($id);
        echo view('remunerations.form', ['r' => $remuneration, 'e'=>$entretien]);
        $content = ob_get_clean();
        return ['title' => 'Modifier une rémunération', 'content' => $content];
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
