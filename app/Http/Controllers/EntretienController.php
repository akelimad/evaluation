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
        return view('entretiens/evaluation.index', compact('entretiens', 'mentor'));
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
    public function showEntretien($type, $id)
    {
        $entretienEval = Entretien::where(['id'=>$id])->with('user')->first();
        return view('entretiens/'.$type.'.show', ['e' => $entretienEval]);
    }

    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function showEntretienProf($type, $id)
    // {
    //     $entretienProf = Entretien::where(['id'=>$id])->with('user')->first();
    //     return view('entretiens/professionnel.show', ['ep' => $entretienProf]);
    // }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createEval()
    {
        $users = Auth::user()->children;
        return view('entretiens/evaluation.create', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createProf()
    {
        $users = Auth::user()->children;
        return view('entretiens/professionnel.create', compact('users'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $entretien = new Entretien();
        $entretien->date = Carbon::createFromFormat('d-m-Y', $request->date);
        if(!empty($request->date_limit)){
            $entretien->date_limit = Carbon::createFromFormat('d-m-Y', $request->date_limit); 
        }
        $entretien->titre = $request->titre;
        $entretien->motif = $request->motif;
        $entretien->frequence = $request->frequence;
        $entretien->user_id = $request->user_id;
        $entretien->type = $request->type;
        $entretien->conclusion_coll = $request->conclusion_coll;
        $entretien->conclusion_mentor = $request->conclusion_mentor;
        $entretien->save();
        return redirect('entretiens/'.$entretien->type.'/'.$entretien->id);
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
        //
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
