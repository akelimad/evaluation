<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;
use App\Entretien;
use App\Objectif;
use Auth;
use App\EntretienObjectif;
use App\Objectif_user;

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
        $evaluations = $entretien->evaluations;
        $objectifs = Objectif::where('parent_id', 0)->where('entretienobjectif_id', $entretien->objectif_id)->paginate(10);
        $total = 0;
        foreach ($objectifs as $obj) {
            $total += $obj->sousTotal; 
        }
        $user = $entretien->users()->where('entretien_user.user_id', $uid)->first();
        return view('objectifs.index', [
            'evaluations' => $evaluations,
            'objectifs' => $objectifs, 
            'e'=> $entretien,
            'user'=> $user,
            'total'=> $this->cutNum($total/$objectifs->count()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($oid)
    {
        ob_start();
        echo view('objectifs.form', ['oid'=> $oid]);
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
                $objectif->entretienobjectif_id = $request->oid;
                $objectif->save();
                foreach ($request->objectifs as $obj) {
                    $subObj = new Objectif();
                    $subObj->title = $obj['subTitle'];
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

    function cutNum($num, $precision = 2){
        return floor($num).substr($num-floor($num),1,$precision+1);
    }

    public function updateNoteObjectifs(Request $request)
    {
        $user= Auth::user();
        foreach ($request->objectifs as $key => $subObjectif) {
            $sousTotal = 0;
            $sumPonderation = 0;
            foreach ($subObjectif as $id => $array) {
                $user->objectifs()->attach([$id => 
                    [
                        'entretien_id' => $request->entretien_id,
                        'note'=> isset($array[0]) && $array[0] != "" ? $array[0]: "", 
                        'realise'=> isset($array[1]) && $array[1] != "" ? $array[1]: "", 
                        'appreciation'=> $array[2],
                        'objNplus1'=> isset($array[4]) && $array[4] == "on" ? 1 : 0
                    ]
                ]);
                $sumPonderation += $array[3];
                $sousTotal +=  ($array[0] * $array[3]);
            }
            $objectif = Objectif::find($key);
            $objectif->sousTotal = $this->cutNum($sousTotal/$sumPonderation, 2);
            $objectif->save(); 
        }
        return redirect()->back();

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
