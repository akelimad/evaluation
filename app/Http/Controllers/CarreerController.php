<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Entretien;
use App\User;
use App\Carreer;
use App\Evaluation;
use App\Survey;
use Carbon\Carbon; 

class CarreerController extends Controller
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
    public function index($eid, $uid)
    {
        // $e = Entretien::find($eid);
        // $user = User::find($uid);
        // $carreers = Carreer::where('entretien_id', $eid)->where('user_id', $uid)->get();
        // $evaluations = $e->evaluations;
        // return view('carreers.index', compact('carreers', 'e', 'user', 'evaluations') );
        $entretien = Entretien::find($eid);
        $evaluations = $entretien->evaluations;
        $sid = Evaluation::surveyId($eid, 2);
        $survey = Survey::find($sid);
        $groupes = $survey->groupes;
        $user = User::findOrFail($uid);
        return view('carreers.index', [
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
    public function create($eid, $uid)
    {
        ob_start();
        $e = Entretien::find($eid);
        $user = User::find($uid);
        echo view('carreers.form', compact('e', 'user'));
        $content = ob_get_clean();
        return ['title' => 'Ajouter une carrière', 'content' => $content];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($e_id, Request $request)
    {
        $id = $request->id;
        if($id){
            $carr = Carreer::find($id);
            $carr->userCarreer = $request->carreers[0];
            $carr->save();
        }else{
            foreach ($request->carreers as $carreer) {
                $carr = new Carreer();
                $carr->userCarreer = $carreer;
                $carr->user_id = $request->uid;
                $carr->entretien_id = $request->eid;
                $carr->save();
            }
        }
        if($carr->save()) {
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
    public function edit($eid, $uid ,$cid)
    {
        ob_start();
        $e = Entretien::find($eid);
        $user = User::find($uid);
        $c = Carreer::find($cid);
        echo view('carreers.form', compact('e', 'user', 'c'));
        $content = ob_get_clean();
        return ['title' => 'Modifier votre carrière', 'content' => $content];
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mentorUpdate(Request $request,$eid, $uid, $cid)
    {
        $user = User::find($uid);
        $carreer = Carreer::findOrFail($cid);
        $carreer->mentor_id = $request->mentor_id;
        $carreer->mentorComment = $request->mentorComment;
        $carreer->save();
        return redirect()->back()->with("mentor_comment", "Vous venez de commenter avec succès sur le(la) collaborateur(trice) ".$user->name." ".$user->last_name );
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
