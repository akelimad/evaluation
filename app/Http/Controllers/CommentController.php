<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Entretien;
use App\User;
use App\Comment;
use Carbon\Carbon; 
use Auth;

class CommentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($eid, $uid)
    {
        $e = Entretien::findOrFail($eid);
        $user = User::findOrFail($uid);
        $comment = Comment::where('entretien_id', $eid)->where('user_id', $uid)->first();
        $evaluations = Entretien::findEvaluations($e);
        return view('comments.index', compact('comment', 'e', 'user', 'evaluations') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($eid, $uid)
    {
        ob_start();
        $e = Entretien::findOrFail($eid);
        $user = User::findOrFail($uid);
        echo view('comments.form', compact('e', 'user'));
        $content = ob_get_clean();
        return ['title' => 'Ajouter un commentaire', 'content' => $content];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($e_id, Request $request)
    {
        $id = $request->id;
        if($id){
            $cmt = Comment::findOrFail($id);
        }else{
            $cmt = new Comment();
        }

        if(Auth::user()->hasRole('MENTOR')) {
            $cmt->mentor_id = Auth::user()->id;
            $cmt->mentorComment = $request->comment;
            $cmt->mentor_updated_at = date('Y-m-d H:i:s');
        }else {
            $cmt->userComment = $request->comment;
            $cmt->user_id = $request->uid;
            $cmt->entretien_id = $request->eid;
        }
        $cmt->save();

        if($cmt->save()) {
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
    public function edit($eid, $uid ,$cid)
    {
        ob_start();
        $e = Entretien::findOrFail($eid);
        $user = User::findOrFail($uid);
        $c = Comment::findOrFail($cid);
        $comment = "";
        if($user->hasRole('MENTOR')) {
            $comment = $c->mentorComment;
        }else{
            $comment = $c->userComment;
        }
        echo view('comments.form', compact('e', 'user', 'c', 'comment'));
        $content = ob_get_clean();
        return ['title' => 'Modifier votre commentaire', 'content' => $content];
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mentorUpdate(Request $request,$eid, $uid, $cid)
    {
        $user = User::findOrFail($uid);
        $comment = Comment::findOrFail($cid);
        $comment->mentor_id = $request->mentor_id;
        $comment->mentorComment = $request->comment;
        $comment->mentor_updated_at = date('Y-m-d H:i:s');
        $comment->save();
        return redirect()->back()->with("mentor_comment", "Vous venez de commenter avec succès sur le(la) collaborateur(trice) ".$user->name." ".$user->last_name );
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
