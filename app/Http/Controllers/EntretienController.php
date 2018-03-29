<?php
namespace App\Http\Controllers;
ini_set('max_execution_time', 60); //1 minutes

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Entretien;
use App\Entretien_user;
use App\Question;
use App\Survey;
use App\Evaluation;
use Carbon\Carbon; 
use Auth;
use Mail;
use Session;
use App\EntretienObjectif;
use App\Formation;
use App\Skill;
use App\Objectif;
use DB;

class EntretienController extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function rand_string( $length ) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars),0,$length);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function entretiensList(Request $request)
    {
        ob_start();
        $ids = json_encode($request->ids);
        $entretiens = Entretien::select('id', 'titre')->get();
        echo view('entretiens.list', compact('entretiens', 'ids'));
        $content = ob_get_clean();
        return ['title' => 'Liste des entretiens', 'content' => $content];
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
    public function indexEntretien()
    {
        $evaluations = Evaluation::all();
        $surveys = Survey::select('id', 'title')->get();
        $objectifs = EntretienObjectif::select('id', 'title')->get();
        $entretiens = Entretien::paginate(15);
        return view('entretiens.listing', compact('entretiens', 'evaluations', 'surveys', 'objectifs'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function entretiensEval()
    {
        // $entretiens = Entretien::with('users.parent')->paginate(10);
        $entretiens = DB::table('entretiens as e')
        ->join('entretien_user as eu', 'e.id', '=', 'eu.entretien_id')
        ->join('users as u', 'u.id', '=', 'eu.user_id')
        ->select('e.*','e.id as entretienId','u.*', 'u.id as userId')
        ->paginate(15);
        // dd($entretiens);
        return view('entretiens/annuel.index', compact('entretiens'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($e_id, $uid)
    {
        $entretien = Entretien::find($e_id);
        $evaluations = $entretien->evaluations;
        // $evaluation = Evaluation::where('title', $type)->first();
        $user = $entretien->users()->where('entretien_user.user_id', $uid)->first();
        return view('entretiens/annuel.show', [
            'e' => $entretien, 
            'user'=> $user, 
            'evaluations' => $evaluations,
            // 'evaluation' => $evaluation
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        ob_start();
        if(Auth::user()->hasRole(['ADMIN', 'RH'])){
            $users = User::select('id', 'email')->get();
        }else{
            $users = Auth::user()->children;
        }
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
        // if($request->id == null ){
        //     $entretien = new Entretien();
        // }else{
        //     $entretien = Entretien::find($request->id);
        // }
        $rules = [
            'date'      => 'required',
            'date_limit' => 'required|after:date',
            'titre'  => 'required|min:3',
        ];
        $validator = \Validator::make($request->all(), $rules);
        $messages = $validator->errors();
        $survey = Survey::where('title', 'standard')->first();
        $objectif = EntretienObjectif::where('title', 'standard')->first();
        if($survey  == null || $objectif == null){
            $messages->add('null_survey_obj', "Aucun questionnaire et/ou objectif standard n'a été trouvé ! il faut les créer tout d'abord.");
        }
        if(count($messages)>0){
            return ["status" => "danger", "message" => $messages];
        }
        $evaluations = Evaluation::pluck('id')->toArray(); //to get ids of all object in one array
        $entretien = new Entretien();
        $entretien->date = Carbon::createFromFormat('d-m-Y', $request->date);
        $entretien->date_limit = Carbon::createFromFormat('d-m-Y', $request->date_limit); 
        $entretien->titre = $request->titre;
        $entretien->created_by = Auth::user()->id;
        $entretien->survey_id = $survey ? $survey->id : 0;
        $entretien->objectif_id = $objectif ? $objectif->id : 0;
        $entretien->save();
        $entretien->evaluations()->attach($evaluations);

        $users_id = $request->usersId;
        $mentors = [];
        $mentors_id = [];
        if($users_id[0] != "all"){
            foreach ($users_id as $user_id) {
                $user = User::find($user_id);
                if($user->parent == null){
                    $mentors[] = $user;
                    $mentors_id[] = $user->id;
                }else{
                    $mentors[] = $user->parent;
                    $mentors_id[] = $user->parent->id;
                }
            }
            $entretien->users()->attach(array_unique($users_id));
        }else{
            $users = User::select('id', 'email', 'user_id')->get();
            $users_id = [];
            foreach ($users as $user) {
                $users_id[]= $user->id;
                if($user->parent == null){
                    $mentors[] = $user;
                    $mentors_id[] = $user->id;
                }else{
                    $mentors[] = $user->parent;
                    $mentors_id[] = $user->parent->id;
                }
            }
            $entretien->users()->attach(array_unique($users_id));
        }

        $user_mentors = array_unique($mentors);
        // foreach ($user_mentors as $mentor) {
        //     $password = "admin123";
        //     $mentor->password = bcrypt($password);
        //     $mentor->save();
        //     Mail::send('emails.mentor_invitation', [
        //         'mentor' => $mentor,
        //         'password' => $password,
        //         'endDate' => $entretien->date_limit
        //     ], function ($m) use ($mentor) {
        //         $m->from('contact@lycom.ma', 'E-entretien');
        //         $m->to($mentor->email, $mentor->name)->subject('Invitation pour évaluer vos collaborateurs');
        //     });
        // }
        return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];

    }

    public function storeCheckedUsers(Request $request)
    {
        $entretien = Entretien::find($request->entretien_id);
        $users_id = json_decode($request->ids);
        $mentors = [];
        foreach ($users_id as $user_id) {
            $user = User::find($user_id);
            if($user->parent == null){
                $mentors[] = $user;
            }else{
                $mentors[] = $user->parent;
            }
            $exist = $user->entretiens->contains($user_id);
        }
        $entretien->users()->syncWithoutDetaching($users_id);

        $user_mentors = array_unique($mentors);
        // foreach ($user_mentors as $mentor) {
        //     $password = "admin123";
        //     $mentor->password = bcrypt($password);
        //     $mentor->save();
        //     Mail::send('emails.mentor_invitation', [
        //         'mentor' => $mentor,
        //         'password' => $password,
        //         'endDate' => $entretien->date_limit
        //     ], function ($m) use ($mentor) {
        //         $m->from('contact@lycom.ma', 'E-entretien');
        //         $m->to($mentor->email, $mentor->name)->subject('Invitation !');
        //     });
        // }
        $url=url('entretiens/evaluations');
        $request->session()->flash('attach_users_entretien', "Les utilisateurs ont bien à été ajouté à l'entretien et un email est envoyé à leur mentor. <a href='{$url}'>cliquer ici pour les consulter</a>");
        return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];

    }

    public function storeEntretienEvals(Request $request)
    {
        $evaluationsIds=[];
        $entretien = Entretien::find($request->entretien_id);
        foreach ($request->choix as $key => $choix) {
            if(isset($choix['evaluation_id'])){
                $evaluationsIds[]=$choix['evaluation_id'];
            }
        }
        foreach ($request->entretiens as $key => $value) {
            if(isset($value[0])) $entretien->survey_id = $value[0];
            if(isset($value[1])) $entretien->objectif_id = $value[1];
            $entretien->save();
        }
        $entretien->evaluations()->sync($evaluationsIds);

        Session::flash('success_evaluations_save', "Les évaluations de l'entretien ont bien été mises à jour");
        return redirect('entretiens/index');
    }

    public function notifyUserInterview($eid, $uid)
    {
        $user = User::findOrFail($uid);
        $entretien = Entretien::findOrFail($eid);
        $password = "admin123";
        $user->password = bcrypt($password);
        $user->save();
        Mail::send('emails.user_invitation', [
            'user' => $user,
            'password' => $password,
            'endDate' => $entretien->date_limit
        ], function ($m) use ($user) {
            $m->from('contact@lycom.ma', 'E-entretien');
            $m->to($user->email, $user->name)->subject('Invitation pour remplir une evaluation');
        });
        return redirect()->back()->with('message', 'Un email est envoyé avec succès à '.$user->name." ".$user->last_name);
    }

    public function notifyMentorInterview($eid, $uid)
    {
        $user = User::findOrFail($uid);
        $mentor = $user->parent;
        $entretien = Entretien::findOrFail($eid);
        $password = "admin123";
        $user->password = bcrypt($password);
        $user->save();
        Mail::send('emails.mentor_invitation', [
            'mentor' => $mentor,
            'password' => $password,
            'endDate' => $entretien->date_limit
        ], function ($m) use ($mentor) {
            $m->from('contact@lycom.ma', 'E-entretien');
            $m->to($mentor->email, $mentor->name)->subject('Invitation pour évaluer votre collaborateur');
        });
        return redirect()->back()->with('relanceMentor', 'Un email de relance est envoyé avec succès à '.$mentor->name." ".$mentor->last_name." pour évaluer ".$user->name." ".$user->last_name);
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

    public function updateMotif(Request $request, $eid, $uid)
    {
        $user = User::find($uid);
        $user->entretiens()->updateExistingPivot($eid, ['motif' => $request->motif]);
        Session::flash('success_motif_save', "Le motif d'abscence a bien été sauvegardé.");
        return redirect('entretiens/evaluations');
    }

    public function apercu($eid, $uid)
    {
        ob_start();
        $e = Entretien::find($eid);
        $user = User::findOrFail($uid);
        $evaluations = $e->evaluations;
        $survey = Survey::find($e->survey_id);
        $groupes = $survey->groupes;
        $formations = Formation::where('user_id', $user->id)->where('status', 2)->get();
        $skills = Skill::all();
        $objectifs = Objectif::where('parent_id', 0)->where('entretienobjectif_id', $e->objectif_id)->paginate(10);
        $total = 0;
        foreach ($objectifs as $obj) {
            $total += $obj->sousTotal; 
        }
        echo view('entretiens.apercu', compact('evaluations','e', 'user', 'groupes', 'formations', 'skills','objectifs', 'total'));
        $content = ob_get_clean();
        return ['title' => "Aperçu de l'entretien", 'content' => $content];
    }

    public function filterEntretiens(Request $request)
    {
        $d = $request->d; //date_limit
        if(!empty($d)) $date = Carbon::createFromFormat('d-m-Y', $d)->toDateString();
        $t = $request->t; //titre
        $id = $request->id;  //id entretien
        $n = $request->n;  //nom user
        $f = $request->f; //function user
        if(isset($date)){
            $entretiens = DB::table('entretiens as e')
            ->join('entretien_user as eu', 'e.id', '=', 'eu.entretien_id')
            ->join('users as u', 'u.id', '=', 'eu.user_id')
            ->select('e.*','e.id as entretienId','u.*', 'u.id as userId')
            ->where('e.date_limit', '=', $date)
            ->where('e.id', 'like', '%'.$id.'%')
            ->where('e.titre', 'like', '%'.$t.'%')
            ->where('u.name', 'like', '%'.$n.'%')
            ->where('u.function', 'like', '%'.$f.'%')
            ->paginate(15);
        }else{
            $entretiens = DB::table('entretiens as e')
            ->join('entretien_user as eu', 'e.id', '=', 'eu.entretien_id')
            ->join('users as u', 'u.id', '=', 'eu.user_id')
            ->select('e.*','e.id as entretienId','u.*', 'u.id as userId')
            ->where('e.id', 'like', '%'.$id.'%')
            ->where('e.titre', 'like', '%'.$t.'%')
            ->where('u.name', 'like', '%'.$n.'%')
            ->where('u.function', 'like', '%'.$f.'%')
            ->paginate(15);
        }
        return view('entretiens.annuel.index', compact('entretiens', 'd', 't', 'id','n', 'f'));
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
