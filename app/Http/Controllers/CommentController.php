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
    if (!$e->canBeFilledByUser($uid)) {
      return redirect()->route('home')->with("danger", Entretien::canBeFilledByUserMessage());
    }
    $user = User::findOrFail($uid);
    $evaluator_id = $user->parent->id;
    $comment = Comment::where('entretien_id', $eid)->where('user_id', $uid)->first();
    $evaluations = Entretien::findEvaluations($e);
    return view('comments.index', compact('comment', 'e', 'user', 'evaluations', 'evaluator_id'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create($eid, $uid, Request $request)
  {
    if ($request->method() == "POST") {
      return $this->store($request);
    }
    ob_start();
    $e = Entretien::findOrFail($eid);
    $user = User::findOrFail($uid);
    $comment = new Comment();
    echo view('comments.form', compact('e', 'user', 'comment'));
    $content = ob_get_clean();
    return ['title' => __("Ajouter un commentaire"), 'content' => $content];
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $id = $request->id;
    $user = User::find($request->uid);
    $isMentor = $user->id != Auth::user()->id;
    if ($id) {
      $cmt = Comment::find($id);
    } else {
      $cmt = new Comment();
    }

    $cmt->entretien_id = $request->eid;
    $cmt->user_id = $user->id;
    $cmt->mentor_id = $user->parent->id;
    if($isMentor) {
      $cmt->mentorComment = $request->comment;
      $cmt->mentor_updated_at = date('Y-m-d H:i:s');
    } else {
      $cmt->userComment = $request->comment;
    }
    $cmt->save();

    if ($cmt->save()) {
      return ["status" => 'reload'];
    } else {
      return ["status" => "warning", "message" => __("Une erreur est survenue, réessayez plus tard")];
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function edit($eid, $uid, $id, Request $request)
  {
    if ($request->method() == "POST") {
      return $this->store($request);
    }
    ob_start();
    $e = Entretien::find($eid);
    $user = User::find($uid);
    $comment = Comment::find($id);

    echo view('comments.form', compact('e', 'user', 'comment'));
    $content = ob_get_clean();
    return ['title' => __("Modifier votre commentaire"), 'content' => $content];
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
    return redirect()->back();
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}
