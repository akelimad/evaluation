<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;
use App\Entretien;
use App\Objectif;


class ObjectifController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource for admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAdmin()
    {
        $objectifs = Objectif::where('parent_id', 0)->paginate(10);
        return view('objectifs.indexAdmin', compact('objectifs'));
    }

    /**
     * Display a listing of the resource for user
     *
     * @return \Illuminate\Http\Response
     */
    public function index($e_id, $uid)
    {
        $entretien = Entretien::find($e_id);
        $objectifs = Objectif::all();
        $user = $entretien->users()->where('entretien_user.user_id', $uid)->first();
        return view('objectifs.index', [
            'objectifs' => $objectifs, 
            'e'=> $entretien,
            'user'=> $user,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($e_id)
    {
        ob_start();
        $entretien = Entretien::find($e_id);
        echo view('objectifs.form', ['e' => $entretien]);
        $content = ob_get_clean();
        return ['title' => 'Ajouter un objectif', 'content' => $content];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($e_id ,Request $request)
    {
        $rules = [];
        $validator = Validator::make($request->all(), $rules);
        $messages = $validator->errors();

        if($request->objectifs){
            // $somme = 0;
            // foreach ($request->objectifs as $obj) {
            //     $somme = $somme + $obj['pourcentage'];
            // }
            // if($somme < 100) {
            //     $messages->add('under_100', 'La somme des pourcentage doit être égale à 100 %');
            // }
            // if(count($messages)>0){
            //     return ["status" => "danger", "message" => $messages];
            // }else{
                $objectif = new Objectif();
                $objectif->title = $request->title;
                $objectif->save();
                foreach ($request->objectifs as $obj) {
                    $subObj = new Objectif();
                    $subObj->title = $obj['subTitle'];
                    $subObj->note = $obj['note'];
                    $subObj->ponderation = $obj['ponderation'];
                    $subObj->parent_id = $objectif->id;
                    $subObj->save();
                }
            // }
        }
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($e_id, $id)
    {
        ob_start();
        $entretien = Entretien::find($e_id);
        $objectif = Objectif::find($id);
        echo view('objectifs.form', ['o' => $objectif, 'e'=>$entretien]);
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
        //
    }
}
