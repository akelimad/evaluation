<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;
use App\Entretien;
use App\Formation;
use Carbon\Carbon; 
use App\User;
use Auth;

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
    public function index($e_id, $uid)
    {
        $e = Entretien::findOrFail($e_id);
        $user = User::findOrFail($uid);
        $formations = Formation::where('entretien_id', $e_id)->where('user_id', $uid)->orderBy('id', 'desc')->paginate(10);
        $historiques = Formation::where('entretien_id', $e_id)->where('user_id', $uid)->where('status', 2)->paginate(10);
        $evaluations = Entretien::findEvaluations($e);
        return view('formations.index', compact('formations', 'historiques', 'e', 'user', 'evaluations') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($e_id, Request $request)
    {
        if ($request->method() == 'POST') {
            return $this->store($e_id, $request);
        }
        ob_start();
        $entretien = Entretien::findOrFail($e_id);
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
        $validator = Validator::make($request->all(), [
            'date'      => 'required',
            'exercice'  => 'required',
            'title'     => "required|regex:/^[A-Za-z0-9\/\s\.'-]+$/",
        ]);
        if ($validator->fails()) {
            return ["status" => "danger", "message" => $validator->errors()->all()];
        }

        if($request->id == null ){
            $formation = new Formation();
        }else{
            $formation =  Formation::findOrFail($request->id);
        }
        $formation->date = Carbon::createFromFormat('d-m-Y', $request->date);
        $formation->exercice = $request->exercice;
        $formation->title = $request->title;
        $formation->status = 0;
        $formation->done = 0;
        $formation->entretien_id = $e_id;
        $formation->user_id = Auth::user()->id;
        $formation->coll_comment = $request->coll_comment;
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
    public function edit($e_id, $id, Request $request)
    {
        if ($request->method() == 'POST') {
            return $this->store($e_id, $request);
        }
        ob_start();
        $entretien = Entretien::findOrFail($e_id);
        $formation = Formation::findOrFail($id);
        echo view('formations.form', ['f' => $formation, 'e'=>$entretien]);
        $content = ob_get_clean();
        return ['title' => 'Modifier la formation', 'content' => $content];
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
        $formation = Formation::findOrFail($id);
        $formation->status = $request->status;
        $formation->done = $request->done == "on" ? 1 : 0 ;
        $formation->save();
        return redirect()->back()->with("update_formation", "La formation a bien été mise à jour !");
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
