<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\EntretienObjectif;
use App\Objectif;
use Auth;

class EntretienObjectifController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $objectifs = Auth::user()->entretiensObjectifs()->paginate(10);
        return view('entretienObjectif.index', compact('objectifs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        ob_start();
        echo view('entretienObjectif.form');
        $content = ob_get_clean();
        return ['title' => 'Ajouter un objectif', 'content' => $content];
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
            $objectif = new EntretienObjectif();
        }else{
            $objectif = EntretienObjectif::findOrFail($request->id);
        }
        $objectif->title = $request->title;
        $objectif->description = $request->description;
        $objectif->user_id = Auth::user()->id;
        $objectif->save();
        if($objectif->save()) {
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
        ob_start();
        $objectifs = Objectif::where('parent_id', 0)->where('entretienobjectif_id', $id)->paginate(10);
        echo view('entretienObjectif.show', compact('objectifs'));
        $content = ob_get_clean();
        return ['title' => "Détails de l'objectif", 'content' => $content];
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
        $o = EntretienObjectif::findOrFail($id);

        echo view('entretienObjectif.form', compact('o'));
        $content = ob_get_clean();
        return ['title' => 'Modifier un objectif', 'content' => $content];
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
        $objectif = EntretienObjectif::findOrFail($id)->delete();
        $sub = Objectif::where('entretienobjectif_id', $id)->delete();
    }
}
