<?php

namespace App\Http\Controllers;

use App\Http\Service\Table;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Entretien;
use App\Salary;
use App\User;
use Auth;

class SalarieController extends Controller
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


  public function getTable(Request $request)
  {
    $eid = $request->get('eid');
    $uid = $request->get('uid');
    $table = new Table($request);
    $query = Salary::where('user_id', $uid)->where('entretien_id', $eid)->orderBy('created_at', 'DESC');

    $table->setPrimaryKey('id');
    $table->addColumn('created_at', 'Date');
    $table->addColumn('brut', 'Brut');
    $table->addColumn('prime', 'Prime');
    $table->addColumn('comment', 'Commentaire');
    $table->setBulkActions(true);

    // define table actions
    $table->addAction('edit', [
      'icon' => 'fa fa-pencil',
      'label' => 'Modifier',
      'route' => ['name' => 'prime.edit', 'args' => ['eid' => $eid, 'uid' => $uid, 'id' => '[id]']],
      'attrs' => [
        'chm-modal' => '',
        'chm-modal-options' => '{"form":{"attributes":{"id":"primeForm", "target-table":"[chm-table]"}}}',
      ],
      'bulk_action' => false,
      'display' => function ($row) {
        $user = User::find($row->user_id);
        return !Entretien::answeredMentor($row->entretien_id, $user->id, $user->parent->id);
      }
    ]);
    $table->addAction('delete', [
      'icon' => 'fa fa-trash',
      'label' => 'Supprimer',
      'callback' => 'chmSalary.delete',
      'bulk_action' => true,
      'display' => function ($row) {
        $user = User::find($row->user_id);
        return !Entretien::answeredMentor($row->entretien_id, $user->id, $user->parent->id);
      }
    ]);

    // render the table
    return $table->render($query);
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
    $evaluations = Entretien::findEvaluations($e);
    $user = User::findOrFail($uid);
    $evaluator_id = $user->parent->id;
    if ($user->id == Auth::user()->id) {
      $salaries = Salary::where('user_id', $uid)->where('entretien_id', $eid)->paginate(10);
    } else {
      $salaries = Salary::where('mentor_id', $user->parent->id)->where('entretien_id', $eid)->paginate(10);
    }
    return view('salaries.index', compact('e', 'user', 'salaries', 'evaluations', 'evaluator_id'));
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
    echo view('salaries.form', compact('e', 'user'));
    $content = ob_get_clean();
    return ['title' => __('Ajouter une prime'), 'content' => $content];
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    if ($request->id == null) {
      $salary = new Salary();
    } else {
      $salary = Salary::findOrFail($request->id);
    }
    $salary->brut = $request->brut;
    $salary->prime = $request->prime;
    $salary->comment = $request->comment;
    $salary->user_id = $request->uid;
    $salary->mentor_id = Auth::user()->id;
    $salary->entretien_id = $request->eid;
    $salary->save();
    if ($salary->save()) {
      return ["status" => "success", "message" => __('Les informations ont été sauvegardées avec succès')];
    } else {
      return ["status" => "warning", "message" => __('Une erreur est survenue, réessayez plus tard')];
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
  public function edit($eid, $uid, $sid, Request $request)
  {
    if ($request->method() == "POST") {
      return $this->store($request);
    }
    ob_start();
    $e = Entretien::findOrFail($eid);
    $user = User::findOrFail($uid);
    $s = Salary::findOrFail($sid);
    echo view('salaries.form', compact('e', 'user', 's'));
    $content = ob_get_clean();
    return ['title' => __('Modifier une prime'), 'content' => $content];
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function delete(Request $request)
  {
    if (empty($request->ids)) return;

    foreach($request->ids as $id) {
      $prime = Salary::find($id);
      try {
        $prime->delete();
      } catch (\Exception $e) {
        return ["status" => "danger", "message" => __("Une erreur est survenue, réessayez plus tard")];
      }
    }

    return response()->json([
      'status' => 'alert',
      'title' => 'Confirmation',
      'content' => '<i class="fa fa-check-circle text-green"></i> '. __("La suppression a été effectuée avec succès"),
    ]);
  }
}
