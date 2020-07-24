<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        $e = Entretien::findOrFail($e_id);
        $evaluations = Entretien::findEvaluations($e);
        $skills = $e->skills()->paginate(15);
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
        // $interviewSkills = Entretien::getAll()->with('skills')->paginate(10);

        $interviewSkills = \DB::table('entretiens as e')
            ->join('skills as s', 'e.id', '=', 's.entretien_id')
            ->select('e.*')
            ->where('e.user_id', User::getOwner()->id)
            ->groupBy('s.entretien_id')
            ->get();
        // dd($interviewSkills);
        $count = Skill::count();
        return view('skills.indexAdmin', compact('interviewSkills', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        ob_start();
        $entretiens = Entretien::getAll()->select('id','titre')->get();
        $skills = [''=>''];
        echo view('skills.form', compact('entretiens', 'skills'));
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
        $rules = [
            'skills.*.axe'            => 'required',
            'skills.*.famille'        => 'required',
            'skills.*.categorie'      => 'required',
            'skills.*.competence'     => 'required',
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ["status" => "danger", "message" => $validator->errors()->all()];
        }
        if($request->id){
            $entretien = Entretien::findOrFail($request->id);
            $entretien->skills()->delete();
        }
        if($request->skills){
            foreach ($request->skills as $key => $value) {
                $skill = new Skill();
                $skill->axe = $value['axe'];
                $skill->famille = $value['famille'];
                $skill->categorie = $value['categorie'];
                $skill->competence = $value['competence'];
                $skill->entretien_id = $request->entretien_id;
                $skill->save();
            }
        }
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
        $entretien = Entretien::findOrFail($id);
        $skills = $entretien->skills;
        echo view('skills.form', compact('skills', 'entretien'));
        $content = ob_get_clean();
        return ['title' => 'Modifier les compétence', 'content' => $content];
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
            $user = User::findOrFail($request->user_id);
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
    public function destroy($eid)
    {
        $entretien = Entretien::findOrFail($eid);
        $entretien->skills()->delete();
    }
}
