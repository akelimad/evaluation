<?php

namespace App\Http\Controllers;

use App\Fonction;
use Illuminate\Http\Request;
use Auth;

class FonctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $functions = Auth::user()->fonctions()->paginate(9);
      return view('functions.index', [
        'results' => $functions,
        'active' => true,
        'active' => 'func',
      ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function form(Request $request)
    {
        $id = $request->id;
        ob_start();
        if(isset($id) && is_numeric($id)) {
            $fonction = Fonction::findOrFail($id);
            $title = "Modifier la fonction";
        } else {
            $fonction = new Fonction();
            $title = "Ajouter une fonction";
        }
        echo view('functions.form', compact('fonction'));
        $content = ob_get_clean();
        return ['title' => $title, 'content' => $content];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request->id;
        if($id){
            $fonction = Fonction::findOrFail($id);
            $fonction->title = $request->titles[0];
            $fonction->save();
        }else{
            foreach ($request->titles as $f) {
                $fonction = new Fonction();
                $fonction->title = $f;
                $fonction->user_id = Auth::user()->id;
                $fonction->save();
            }
        }
        if($fonction->save()) {
            return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
        } else {
            return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $id = $request->id;
        $fonction = Fonction::findOrFail($id);
        $fonction->delete();
    }

}
