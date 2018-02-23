<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Entretien;
use App\Skill;

class SkillController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        $skills = $entretien->skills;
        return view('skills.index', ['skills' => $skills, 'e'=> $entretien]);
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
        echo view('skills.form', ['e' => $entretien]);
        $content = ob_get_clean();
        return ['title' => 'Ajouter une compétence', 'content' => $content];
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
            $skill = new Skill();
        }else{
            $skill =  Skill::find($request->id);
        }
        $skill->domaine = $request->domaine;
        $skill->titre = $request->titre;
        $skill->niveau = $request->niveau;
        $skill->commentaire = $request->commentaire;
        $skill->transmit = $request->transmit == "on" ? 1 : 0;
        $skill->entretien_id = $e_id;
        $skill->save();
        if($skill->save()) {
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
        $skill = Skill::find($id);
        echo view('skills.form', ['s' => $skill, 'e'=>$entretien]);
        $content = ob_get_clean();
        return ['title' => 'Modifier une compétence', 'content' => $content];
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
