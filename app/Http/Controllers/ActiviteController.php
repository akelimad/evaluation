<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Entretien;
use App\Evaluation;
use App\Groupe;

class ActiviteController extends Controller
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
    public function index($e_id, $uid)
    {
        $entretien = Entretien::find($e_id);
        $evaluations = $entretien->evaluations;
        $evaluation = Evaluation::where('title', 'Evaluations')->first();
        $survey = $evaluation->survey;
        $groupes = $survey->groupes;
        $user = $entretien->users()->where('entretien_user.user_id', $uid)->first();
        return view('activites.index', [
            'evaluations' => $evaluations, 
            'survey' => $survey, 
            'e'=> $entretien,  
            'groupes' => $groupes, 
            'user' => $user
        ]);
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
        echo view('activites.form', ['e' => $entretien]);
        $content = ob_get_clean();
        return ['title' => 'Ajouter une activité', 'content' => $content];
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
            $activite = new Activite();
        }else{
            $activite =  Activite::find($request->id);
        }
        $activite->titre = $request->titre;
        $activite->client = $request->client;
        $activite->duree = $request->duree;
        $activite->acquisition = $request->acquisition;
        $activite->amelioration = $request->amelioration;
        $activite->commentaire = $request->commentaire;
        $activite->evaluation = $request->evaluation;
        $activite->entretien_id = $e_id;
        $activite->save();
        if($activite->save()) {
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
    public function show($type, $id)
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
        $entretien = Entretien::find($e_id);
        $activite = Activite::find($id);
        echo view('activites.form', ['a' => $activite, 'e'=>$entretien]);
        $content = ob_get_clean();
        return ['title' => 'Modifier une activité', 'content' => $content];
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
    public function destroy($type, $id)
    {
        //
    }
}
