<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Entretien;
use App\Skill;
use App\Skill_user;
use App\User;
use Auth;

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
    public function index($e_id, $uid)
    {
        $e = Entretien::find($e_id);
        $evaluations = $e->evaluations;
        $skills = Skill::paginate(10);
        $user = $e->users()->where('entretien_user.user_id', $uid)->first();
        return view('skills.index', compact('e', 'evaluations','skills', 'user'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAdmin()
    {
        $skills = Skill::paginate(10);
        return view('skills.indexAdmin', compact('skills'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        ob_start();
        echo view('skills.form');
        $content = ob_get_clean();
        return ['title' => 'Ajouter une compétence', 'content' => $content];
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
            $skill = new Skill();
        }else{
            $skill =  Skill::find($request->id);
        }
        $skill->axe = $request->axe;
        $skill->famille = $request->famille;
        $skill->categorie = $request->categorie;
        $skill->competence = $request->competence;
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
    public function edit($id)
    {
        ob_start();
        $s = Skill::find($id);
        echo view('skills.form', compact('s'));
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
    public function updateUserSkills(Request $request)
    {
        //dd($request->skills);
        foreach ($request->skills as $id => $value) {
            $user = User::find($request->user_id);
            $user->skills()->sync([$id => 
                [
                    'mentor_id'  => $request->mentor_id,
                    'entretien_id' => $request->entretien_id,
                    'objectif' => $value['objectif'] !="" ? $value['objectif'] : 0 ,
                    'auto'     => $value['auto'] !="" ? $value['auto'] : 0 ,
                    'nplus1'   => $value['nplus1'] !="" ? $value['nplus1'] : 0 ,
                    'ecart'    => $value['ecart'] !="" ? $value['ecart'] : 0 ,
                ]
            ], false);

        }
        return redirect()->back()->with("success_update","Les informations ont été sauvegardées avec succès !");
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
