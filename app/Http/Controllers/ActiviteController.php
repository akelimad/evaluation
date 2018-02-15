<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Entretien;
use App\Activite;

class ActiviteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type, $id)
    {
        $activites = Entretien::find($id)->activites;
        return view('activites.index', ['activites' => $activites]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('activites.form');
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
            $activite = new Activite();
        }else{
            $activite =  Activite::find($request->id);
        }
        $activite->titre = $request->titre;
        $activite->client = $request->client;
        $activite->duree = $request->duree;
        $activite->acquisition = $request->acquisition;
        $activite->amelioration = $request->amelioration;
        $activite->commentaire = $request->commentaire;
        $activite->evaluation = $request->evaluation;
        $activite->user_id = Auth::user()->id;
        $activite->save();
        return redirect('entretiens/activites');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($type, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($type, $id)
    {
        $activite = Activite::find($id);
        return view('activites.form', ['a' => $activite]);
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
    public function destroy($type, $id)
    {
        //
    }
}
