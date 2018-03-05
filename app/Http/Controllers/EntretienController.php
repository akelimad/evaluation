<?php
namespace App\Http\Controllers;
ini_set('max_execution_time', 60); //1 minutes

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Entretien;
use Carbon\Carbon; 
use Auth;
use Mail;

class EntretienController extends Controller
{
    public function rand_string( $length ) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars),0,$length);
    }

    public function __construct()
    {
        $this->middleware('auth');
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
        $entretiens = Entretien::all();
        return view('entretiens.listing', compact('entretiens'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function entretiensEval()
    {
        $entretiens = Entretien::with('users.parent')->get();
        return view('entretiens/annuel.index', compact('entretiens'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($e_id)
    {
        $entretienEval = Entretien::where(['id'=>$e_id])->with('users')->first();
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
        $entretien = new Entretien();
        $entretien->date = Carbon::createFromFormat('d-m-Y', $request->date);
        $entretien->date_limit = Carbon::createFromFormat('d-m-Y', $request->date_limit); 
        $entretien->titre = $request->titre;
        $entretien->created_by = Auth::user()->id;
        $entretien->type = $request->type;
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
            $entretien->users()->attach(array_unique(array_merge($mentors_id, $users_id)));
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
            $entretien->users()->attach(array_unique(array_merge($mentors_id, $users_id)));
        }

        $user_mentors = array_unique($mentors);
        foreach ($user_mentors as $mentor) {
            $password = "admin123";
            $mentor->password = bcrypt($password);
            $mentor->save();
            Mail::send('emails.invitation', [
                'mentor' => $mentor,
                'password' => $password,
                'endDate' => $entretien->date_limit
            ], function ($m) use ($mentor) {
                $m->from('contact@lycom.ma', 'E-entretien');
                $m->to($mentor->email, $mentor->name)->subject('Invitation !');
            });
        }
        return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];

    }

    public function storeCheckedUsers(Request $request)
    {
        $entretien = Entretien::find($request->entretien_id);
        $users_id = json_decode($request->ids);
        $mentors = [];
        $mentors_id = [];
        foreach ($users_id as $user_id) {
            $user = User::find($user_id);
            if($user->parent == null){
                $mentors[] = $user;
                $mentors_id[] = $user->id;
            }else{
                $mentors[] = $user->parent;
                $mentors_id[] = $user->parent->id;
            }
            $exist = $user->entretiens->contains($user_id);
        }
        $entretien->users()->syncWithoutDetaching(array_merge($mentors_id, $users_id));

        $user_mentors = array_unique($mentors);
        foreach ($user_mentors as $mentor) {
            $password = "admin123";
            $mentor->password = bcrypt($password);
            $mentor->save();
            Mail::send('emails.invitation', [
                'mentor' => $mentor,
                'password' => $password,
                'endDate' => $entretien->date_limit
            ], function ($m) use ($mentor) {
                $m->from('contact@lycom.ma', 'E-entretien');
                $m->to($mentor->email, $mentor->name)->subject('Invitation !');
            });
        }
        return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];

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
