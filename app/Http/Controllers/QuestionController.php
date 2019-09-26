<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Question;
use App\Groupe;
use App\Survey;
use App\Answer;
use Auth;

class QuestionController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index($sid, $gid)
  {
    $survey = Survey::findOrFail($sid);
    $groupe = Groupe::findOrFail($gid);
    return view('questions.index', compact('survey','groupe'));
  }

  public function create($sid, $gid, Request $request)
  {
    ob_start();
    $parent_id = $request->parent_id;
    $qChoices = ['' => ''];
    $count = count($qChoices) - 1;
    echo view('questions.form', compact('sid','gid', 'parent_id', 'qChoices', 'count'));
    $content = ob_get_clean();
    return ['title' => 'Ajouter une question', 'content' => $content];
  }

  public function store(Request $request)
  {
    if($request->id == null ){
      $question = new Question();
      $action ="ajoutée";
    }else{
      $question =  Question::findOrFail($request->id);
      $action ="modifiée";
    }

    $question->titre = $request->titre;
    $question->type = $request->type;
    $question->parent_id =  0;
    $question->groupe_id = $request->groupe_id;
    $question->options = json_encode($request->options);
    $question->save();
    if(isset($request->subQuestions) && count($request->subQuestions)>0 ){
      $question->children()->delete();
      foreach ($request->subQuestions as $key => $value) {
        if (empty($value['titre'])) continue;
        $choice = new Question();
        $choice->titre = $value['titre'];
        $choice->parent_id = $question->id;
        $choice->groupe_id = $request->groupe_id;
        $choice->options = json_encode(['label' => $value['label']]);
        $choice->save();
      }
    }

    $url=url('surveys/'.$request->survey_id.'/groupes/'.$request->groupe_id.'/questions/'.$question->id);
    $request->session()->flash('success', "La question à été ".$action." avec succès. <a href='$url'>cliquer ici</a> pour la consulter");
    if($question->save()) {
      return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
      redirect('users');
    } else {
      return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
    }
  }

  public function show($sid ,$gid, $qid)
  {
    $survey = Survey::findOrFail($sid);
    $groupes = $survey->groupes;
    $gr = Groupe::findOrFail($gid);
    $qs = Question::findOrFail($qid);
    return view('questions.show', compact('groupes', 'sid', 'gr', 'qs'));
  }

  public function edit($sid, $gid, $qid)
  {
    ob_start();
    $q = Question::findOrFail($qid);
    $qChoices = $q->children->push(new Question());
    $count = count($qChoices) - 1;
    echo view('questions.form', compact('sid','gid', 'q', 'qChoices', 'count'));
    $content = ob_get_clean();
    return ['title' => 'Modifier la question', 'content' => $content];
  }

  public function update(Request $request, $id)
  {
    //
  }

  public function destroy($sid, $gid, $qid)
  {
    $question = Question::findOrFail($qid);
    $question->delete();
    $question->children()->delete();
    return redirect('surveys/'.$sid.'/groupes/'.$gid.'/questions');
  }
}
