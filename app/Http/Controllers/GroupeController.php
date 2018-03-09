<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $survey = Survey::find($sid);
        $groupes = $survey->groupes()->paginate(10);
        return view('groupes/index', compact('groupes', 'sid'));
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
        return ['title' => 'Ajouter un groupe', 'content' => $content];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->id == null ){
            $groupe = new Groupe();
        }else{
            $groupe =  Groupe::find($request->id);
        }
        $groupe->name = $request->name;
        $groupe->description = $request->description;
        $groupe->survey_id = $request->sid;
        $groupe->save();
        if($groupe->save()) {
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
    public function edit($id)
    {
        ob_start();
        $g = Groupe::find($id);
        echo view('groupes.form', ['g' => $g]);
        $content = ob_get_clean();
        return ['title' => 'Modifier un groupe', 'content' => $content];
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
