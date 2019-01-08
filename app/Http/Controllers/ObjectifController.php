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
use App\User;

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
    public function indexAdmin($oid)
    {
        $objectifs = Objectif::where('parent_id', 0)->where('entretienobjectif_id', $oid)->paginate(15);
        $count = Objectif::count();
        return view('objectifs.indexAdmin', compact('objectifs', 'count', 'oid'));
    }

    /**
     * Display a listing of the resource for user
     *
     * @return \Illuminate\Http\Response
     */
    public function index($e_id, $uid)
    {
        $entretien = Entretien::findOrFail($e_id);
        $evaluations = $entretien->evaluations;
        $objectifs = Objectif::where('parent_id', 0)->where('entretienobjectif_id', $entretien->objectif_id)->paginate(10);
        $total = 0;
        if(count($objectifs)>0){
            foreach ($objectifs as $obj) {
                $total += $obj->sousTotal; 
            }
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
        $objectif = [''=>''];
        echo view('objectifs.form', compact('oid', 'objectif'));
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
        if($request->gid){
            $objectif = Objectif::findOrFail($request->gid);
            $objectif->children()->delete();
            $objectif->title = $request->title;
            $objectif->save();
            foreach ($request->objectifs as $obj) {
                $subObj = new Objectif();
                $subObj->title = $obj['subTitle'];
                $subObj->ponderation = $obj['ponderation'];
                $subObj->parent_id = $objectif->id;
                $subObj->save();
            }
        }else{
            if($request->objectifs){
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
            }
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
    public function edit($oid, $gid)
    {
        ob_start();
        $groupe = Objectif::findOrFail($gid);
        $objectif = $groupe->children;
        echo view('objectifs.form', compact('objectif','oid', 'groupe'));
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
        //dd($request->objectifs);
        $auth= Auth::user(); $user_id = $request->user_id;
        if($auth->id == $user_id){
            $user = $auth;
            $mentor_id = $auth->parent->id;
        }else{
            $user = User::findOrFail($user_id);
            $mentor_id = $auth->id;
        }
        // dump($user);
        // dump($mentor_id);
        // die();
        foreach ($request->objectifs as $key => $subObjectif) {
            // $sousTotal = 0;
            // $sumPonderation = 0;
            foreach ($subObjectif as $id => $array) {
                $userHasObjectif = Objectif_user::where('objectif_id', $id)->where('user_id', $user->id)->where('entretien_id',$request->entretien_id)->where('userNote', '<>',0)->first();
                $mentorHasObjectif = Objectif_user::where('objectif_id', $id)->where('user_id', $user->id)->where('entretien_id',$request->entretien_id)->where('mentor_id', $mentor_id)->first();
                // dump($userHasObjectif);
                // dump($mentorHasObjectif);
                // die();
                if($userHasObjectif){
                    Objectif_user::where('objectif_id', $id)->where('user_id', $user->id)->where('entretien_id',$request->entretien_id)->where('userNote', '<>',0)->update([
                        'mentor_id'=> $mentor_id,
                        'mentorNote'=> isset($array['mentorNote']) ? $array['mentorNote'] : NULL,
                        'mentorAppreciation'=> isset($array['mentorAppr']) ? $array['mentorAppr'] : NULL,
                    ]);
                }else if($mentorHasObjectif){
                    Objectif_user::where('objectif_id', $id)->where('user_id', $user->id)->where('entretien_id',$request->entretien_id)->where('mentor_id', $mentor_id)->update([
                        'userNote'=> isset($array['userNote']) ? $array['userNote']: "", 
                        'userAppreciation'=> isset($array['userAppr']) ? $array['userAppr']: "",
                    ]);
                }else{
                    $user->objectifs()->attach([$id => 
                        [
                            'entretien_id' => $request->entretien_id,
                            'userNote'=> isset($array['userNote']) ? $array['userNote']: "", 
                            'realise'=> isset($array['realise']) ? $array['realise']: "", 
                            'ecart'=> isset($array['ecart']) ? $array['ecart']: "", 
                            'userAppreciation'=> isset($array['userAppr']) ? $array['userAppr']: "",
                            'objNplus1'=> isset($array['objNplus1']) && $array['objNplus1'] == "on" ? 1 : 0,
                            'mentor_id'=> $mentor_id,
                            'mentorNote'=> isset($array['mentorNote']) ? $array['mentorNote'] : NULL,
                            'mentorAppreciation'=> isset($array['mentorAppr']) ? $array['mentorAppr'] : NULL,
                        ]
                    ]);
                }
                // $sumPonderation += $array[3];
                // $sousTotal +=  ($array[0] * $array[3]);
            }
            // $objectif = Objectif::findOrFail($key);
            // $objectif->sousTotal = $this->cutNum($sousTotal/$sumPonderation, 2);
            // $objectif->save(); 
        }
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($oid, $gid)
    {
        $objectif = Objectif::findOrFail($gid);
        $objectif->children()->delete();
    }
}
