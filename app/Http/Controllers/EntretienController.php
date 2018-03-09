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
use Carbon\Carbon; 
use Auth;
use Mail;
use Session;

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
        $to_fill = Entretien::getEvaluations();
        $surveys = Survey::select('id', 'title')->get();
        $entretiens = Entretien::all();
        return view('entretiens.listing', compact('entretiens', 'to_fill', 'surveys'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function entretiensEval()
    {
        $entretiens = Entretien::with('users.parent')->paginate(10);
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
        $user = $entretien->users()
        ->where('entretien_user.user_id', $uid)
        ->first();
        $to_fill = Entretien::getEvaluations();
        return view('entretiens/annuel.show', ['e' => $entretien, 'u'=> $user, 'to_fill' =>$to_fill]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        ob_start();
        if(Auth::user()->hasRole('ADMIN')){
            $users = User::select('id', 'email')->where('id', '!=', Auth::user()->id)->get();
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
        $to_fill = Entretien::getEvaluations();
        foreach ($to_fill as $key => $value) {
            $evaluations[] = $key ;
        }
        $entretien = new Entretien();
        $entretien->date = Carbon::createFromFormat('d-m-Y', $request->date);
        $entretien->date_limit = Carbon::createFromFormat('d-m-Y', $request->date_limit); 
        $entretien->titre = $request->titre;
        $entretien->created_by = Auth::user()->id;
        $entretien->type = $request->type;
        $entretien->evaluations = json_encode($evaluations);
        $entretien->save();

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
        dd($request->surveys);
        if($request->evaluations){
            foreach ($request->evaluations as $key => $evaluation) {
                $entretien = Entretien::find($key);
                $entretien->evaluations = json_encode($evaluation);
                $entretien->save();
            }
        }
        Session::flash('success_evaluations_save', "Les évaluations de l'entretien ont bien été mises à jour");
        return redirect('entretiens/index');
    }

    public function notifyUserInterview($eid, $uid)
    {
        $user = User::findOrFail($uid);
        $entretien = Entretien::findOrFail($eid);
        $password = "admin123";
        // Mail::send('emails.user_invitation', [
        //     'user' => $user,
        //     'password' => $password,
        //     'endDate' => $entretien->date_limit
        // ], function ($m) use ($user) {
        //     $m->from('contact@lycom.ma', 'E-entretien');
        //     $m->to($user->email, $user->name)->subject('Invitation pour remplir une evaluation');
        // });
        return redirect()->back()->with('message', 'Un email est envoyé avec succès à '.$user->name." ".$user->last_name);
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

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->motif = $request->motif;
        $user->save();
        Session::flash('success_motif_save', "Le motif d'abscence a bien été sauvegardé.");
        return redirect('entretiens/evaluations');
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
