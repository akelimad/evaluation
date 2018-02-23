<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Entretien;
use Carbon\Carbon; 
use Auth;
class EntretienController extends Controller
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
    public function index()
    {
        $collaborateurs = Auth::user()->children;
        return view('entretiens.index', compact('collaborateurs'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function entretiensEval()
    {
        $entretiens = Entretien::where('type' , '=', 'annuel')->where('user_id' , '=', Auth::user()->id)->get();
        $mentor = Auth::user()->parent;
        return view('entretiens/annuel.index', compact('entretiens', 'mentor'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function entretiensProf()
    {
        $entretiens = Entretien::where('type' , '=', 'professionnel')->where('user_id' , '=', Auth::user()->id)->get();
        $mentor = Auth::user()->parent;
        return view('entretiens/professionnel.index', compact('entretiens', 'mentor'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($e_id)
    {
        $entretienEval = Entretien::where(['id'=>$e_id])->with('user')->first();
        return view('entretiens/'.$entretienEval->type.'.show', ['e' => $entretienEval]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        ob_start();
        $users = User::select('id', 'email')->where('id', '<>', Auth::user()->id)->get();
        echo view('entretiens/'.$type.'.form', compact('users'));
        $content = ob_get_clean();
        return ['title' => 'Ajouter un entretien '.$type.'', 'content' => $content];
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
            $entretien = new Entretien();
        }else{
            $entretien = Entretien::find($request->id);
        }
        if(count($request->usersId)>0){
            foreach ($request->usersId as $user_id) {
                $entretien->date = Carbon::createFromFormat('d-m-Y', $request->date);
                if(!empty($request->date_limit)){
                    $entretien->date_limit = Carbon::createFromFormat('d-m-Y', $request->date_limit); 
                }
                $entretien->titre = $request->titre;
                $entretien->motif = $request->motif;
                $entretien->frequence = $request->frequence;
                if($request->id == null) $entretien->user_id = $user_id;
                $entretien->type = $request->type;
                $entretien->conclusion_coll = $request->conclusion_coll;
                $entretien->conclusion_mentor = $request->conclusion_mentor;
                $entretien->save();
            }
            if($entretien->save()) {
                return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
            } else {
                return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editEntretien($e_id)
    {
        ob_start();
        $users = Auth::user()->children;
        $entretienEval = Entretien::where(['id'=>$e_id])->first();
        echo view('entretiens/'.$entretienEval->type.'.form', ['e' => $entretienEval, 'users'=> $users]);
        $content = ob_get_clean();
        return ['title' => 'Modifier un entretien', 'content' => $content];
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
