<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Entretien;
use App\Comment;
use Carbon\Carbon; 

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($e_id)
    {
        $entretien = Entretien::find($e_id);
        $comments = $entretien->comments;
        return view('comments.index', ['comments' => $comments, 'e'=> $entretien]);
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
        echo view('comments.form', ['e' => $entretien]);
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
        if($request->id == null ){
            $comment = new Comment();
        }else{
            $comment = Comment::find($request->id);
        }
        $comment->is_task = $request->is_task == "on" ? 1 : 0;
        $comment->destinataire = $request->destinataire;
        $comment->echeance = Carbon::createFromFormat('d-m-Y', $request->echeance);
        $comment->is_done = $request->is_done == "on" ? 1 : 0;
        $comment->comment = $request->comment;
        $comment->entretien_id = $e_id;
        $comment->save();
        if($comment->save()) {
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
    public function edit($e_id ,$id)
    {
        ob_start();
        $entretien = Entretien::find($e_id);
        $comment = Comment::find($id);
        echo view('comments.form', ['c' => $comment, 'e'=>$entretien]);
        $content = ob_get_clean();
        return ['title' => 'Modifier un commentaire', 'content' => $content];
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
