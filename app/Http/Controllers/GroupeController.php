<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Requests;
use App\Groupe;
use App\Survey;

class GroupeController extends Controller
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
    public function index($sid)
    {
        $survey = Survey::findOrFail($sid);
        $groupes = $survey->groupes()->paginate(10);
        return view('groupes/index', compact('survey', 'groupes', 'sid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($sid)
    {
        ob_start();
        echo view('groupes.form', compact('sid'));
        $content = ob_get_clean();
        return ['title' => 'Ajouter un type de questions', 'content' => $content];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|min:2|max:128|regex:/^[a-zA-Z0-9À-ú\s\-_"°^\'’.\s]+$/',
        ]);
        if ($validator->fails()) {
            return ["status" => "danger", "message" => $validator->errors()->all()];
        }

        if($request->id == null ){
            $groupe = new Groupe();
        }else{
            $groupe =  Groupe::findOrFail($request->id);
        }
        $groupe->name = $request->name;
        $groupe->description = $request->description;
        $groupe->notation_type = $request->notation_type ? $request->notation_type : '';
        $groupe->survey_id = $request->sid;
        $groupe->save();
        $url=url('surveys/'.$request->sid.'/groupes');
        $request->session()->flash('success', "Le type de questions a été ajouté avec succès.");
        // $request->session()->flash('success', "La groupe à été ajouté avec succès. <a href='{$url}'>cliquer ici pour voir la liste des groupe du questionnaire</a>");
        if($groupe->save()) {
            return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès'];
        } else {
            return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard'];
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
    public function edit($sid, $gid)
    {
        ob_start();
        $g = Groupe::findOrFail($gid);
        echo view('groupes.form', compact('g', 'sid'));
        $content = ob_get_clean();
        return ['title' => 'Modifier le type de questions', 'content' => $content];
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
    public function destroy($sid, $gid)
    {
        $groupe = Groupe::findOrFail($gid);
        $groupe->questions()->delete();
        $groupe->delete();
        return ["status" => "success", "message" => 'Le groupe a bien été supprimé !'];
    }
}
