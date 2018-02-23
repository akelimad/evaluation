<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Entretien;
use App\Formation;
use Carbon\Carbon; 

class FormationController extends Controller
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
        $formations = $entretien->formations;
        return view('formations.index', ['formations' => $formations, 'e'=> $entretien]);
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
        echo view('formations.form', ['e' => $entretien]);
        $content = ob_get_clean();
        return ['title' => 'Ajouter une formation', 'content' => $content];
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
            $formation = new Formation();
        }else{
            $formation =  Formation::find($request->id);
        }
        $formation->titre = $request->titre;
        $formation->perspective = $request->perspective;
        $formation->date = Carbon::createFromFormat('d-m-Y', $request->date);
        $formation->transmit = $request->transmit == "on" ? 1 : 0;
        $formation->entretien_id = $e_id;
        $formation->save();
        if($formation->save()) {
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
        $formation = Formation::find($id);
        echo view('formations.form', ['f' => $formation, 'e'=>$entretien]);
        $content = ob_get_clean();
        return ['title' => 'Modifier une formation', 'content' => $content];
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
