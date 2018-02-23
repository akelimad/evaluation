<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Entretien;
use App\Objectif;


class ObjectifController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($e_id)
    {
        $entretien = Entretien::find($e_id);
        $objectifs = $entretien->objectifs;
        return view('objectifs.index', ['objectifs' => $objectifs, 'e'=> $entretien]);
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
        echo view('objectifs.form', ['e' => $entretien]);
        $content = ob_get_clean();
        return ['title' => 'Ajouter un objectif', 'content' => $content];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($e_id ,Request $request)
    {
        if($request->id == null ){
            $objectif = new Objectif();
        }else{
            $objectif =  Objectif::find($request->id);
        }
        $objectif->titre = $request->titre;
        $objectif->description = $request->description;
        $objectif->methode = $request->methode;
        $objectif->mesure = $request->mesure;
        $objectif->echeance = $request->echeance;
        $objectif->statut = $request->statut;
        $objectif->entretien_id = $e_id;
        $objectif->save();
        if($objectif->save()) {
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
        $objectif = Objectif::find($id);
        echo view('objectifs.form', ['o' => $objectif, 'e'=>$entretien]);
        $content = ob_get_clean();
        return ['title' => 'Modifier un objectif', 'content' => $content];
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
