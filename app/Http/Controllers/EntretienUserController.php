<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Campaign;
use App\Comment;
use App\Email;
use App\Entretien;
use App\Entretien_evaluation;
use App\Entretien_user;
use App\EntretienObjectif;
use App\Evaluation;
use App\Formation;
use App\Http\Mail\MailerController;
use App\Http\Service\Table;
use App\Salary;
use App\Skill;
use App\Survey;
use App\User;
use Auth;
use Illuminate\Filesystem\Filesystem;
use Session;
use DB;
use Excel;
use Symfony\Component\HttpFoundation\Request;

class EntretienUserController extends Controller
{
  public function getTable(Request $request)
  {
    $table = new Table($request);
    $e = Entretien::find($request->get('eid', 0));
    $query = Entretien_user::where('entretien_id', $e->id);

    $table->setPrimaryKey('id');
    $table->setBulkActions(true);
    $table->addColumn('coll', 'Evalué', function ($row) {
      $user = User::find($row->user_id);
      $e = Entretien::find($row->entretien_id);
      return $e->isFeedback360() ? $e->users[0]->fullname() : $user->fullname();
    });
    $table->addColumn('coll_answer_status', 'Statut', function ($row) {
      $e = Entretien::find($row->entretien_id);
      $user = User::find($row->user_id);
      $userParentId = $user->parent ? $user->parent->id : 0;
      $statusInfo = \App\Entretien_user::getStatus($user->id, $userParentId, $e->id, 'user');
      return '<span class="badge ' . $statusInfo['labelClass'] . '">' . $statusInfo['name'] . '</span>';
    });
    $table->addColumn('manager', 'Evaluateur', function ($row) {
      $e = Entretien::find($row->entretien_id);
      if ($e->isFeedback360()) {
        $user = User::find($row->mentor_id);
      } else {
        $user = User::find($row->user_id);
      }
      $userParentFullname = $user->parent ? $user->parent->fullname() : 'introuvable';
      return $e->isFeedback360() ? $user->fullname() : $userParentFullname;
    });
    $table->addColumn('manager_answer_status', 'Statut', function ($row) {
      $user = User::find($row->user_id);
      $e = Entretien::find($row->entretien_id);
      $userParentId = $user->parent ? $user->parent->id : 0;
      if ($e->isFeedback360()) {
        $userParentId = $row->mentor_id;
      }
      $statusInfo = \App\Entretien_user::getStatus($user->id, $userParentId, $e->id, 'mentor');
      return '<span class="badge ' . $statusInfo['labelClass'] . '">' . $statusInfo['name'] . '</span>';
    });
    // define table actions
    $table->addAction('apercu', [
      'icon' => 'fa fa-search',
      'label' => 'Aperçu',
      'route' => ['name' => 'entretien.apercu', 'args' => ['id' => '[id]']],
      'attrs' => [
        'chm-modal' => '',
        'chm-modal-options' => '{"width": "1000px"}',
      ],
      'bulk_action' => false,
    ]);
    if (!$e->isFeedback360()) {
      $table->addAction('reminder_coll', [
        'icon' => 'fa fa-bell',
        'label' => "Rappeler à l'évalué de remplir son entretien",
        'callback' => 'chmEntretien.reminder',
        'callback_params' => ['role' => 'coll'],
        'bulk_action' => true,
      ]);
    }
    $table->addAction('reminder_manager', [
      'icon' => 'fa fa-bell',
      'label' => "Rappeler à l'évaluateur de remplir son entretien",
      'callback' => 'chmEntretien.reminder',
      'callback_params'=> ['role' => 'mentor'],
      'bulk_action' => true,
    ]);
    $table->addAction('reopen', [
      'icon' => 'fa fa-refresh',
      'label' => 'Réouvrir',
      'callback' => 'chmEntretien.reOpen',
      'bulk_action' => true,
    ]);
    $table->addAction('export', [
      'icon' => 'fa fa-file-excel-o',
      'label' => 'Exporter au format Excel',
      'callback' => 'chmEntretien.export',
      'callback_params' => ['eid' => $e->id],
      'bulk_action' => true,
    ]);
    $table->addAction('delete', [
      'icon' => 'fa fa-trash',
      'label' => 'Supprimer',
      'callback' => 'chmEntretien.deleteUsers',
      'bulk_action' => true,
    ]);

    // render the table
    return $table->render($query);
  }

  public function getTableNotes(Request $request) {
    $table = new Table($request);
    $e = Entretien::find($request->get('eid', 0));
    $query = Entretien_user::where('entretien_id', $e->id);

    $table->setPrimaryKey('id');
    $table->addColumn('coll', 'Evalué', function ($row) {
      $user = User::find($row->user_id);
      $e = Entretien::find($row->entretien_id);
      return $e->isFeedback360() ? $e->users[0]->fullname() : $user->fullname();
    });
    $table->addColumn('manager', 'Evaluateur', function ($row) {
      $e = Entretien::find($row->entretien_id);
      if ($e->isFeedback360()) {
        $user = User::find($row->mentor_id);
      } else {
        $user = User::find($row->user_id);
      }
      $userParentFullname = $user->parent ? $user->parent->fullname() : 'introuvable';
      return $e->isFeedback360() ? $user->fullname() : $userParentFullname;
    });
    $table->addColumn('eval_note', 'Evaluation Note / 100', function ($row) use($e) {
      $sid = Entretien_evaluation::getItemsId($e->id, 1);
      $sid = isset($sid[0]) ? $sid[0] : 0;
      $survey = Survey::find($sid);
      if (!$survey) return 0;
      $totalNote = Answer::getTotalNote($survey->id, $row->user_id, $e->id);
      return $totalNote;
    });
    $table->addColumn('carrer_note', 'Carrière Note / 100', function ($row) use($e) {
      $sid = Entretien_evaluation::getItemsId($e->id, 2);
      $sid = isset($sid[0]) ? $sid[0] : 0;
      $survey = Survey::find($sid);
      if (!$survey) return 0;
      $totalNote = Answer::getTotalNote($survey->id, $row->user_id, $e->id);
      return $totalNote;
    });
    $table->addColumn('avg', 'Moyenne / 100', function ($row) use($e) {
      $eval_sid = Entretien_evaluation::getItemsId($e->id, 1);
      $eval_sid = isset($eval_sid[0]) ? $eval_sid[0] : 0;
      $eval_survey = Survey::find($eval_sid);
      if (!$eval_survey) {
        $totalEvalNote = 0;
      } else {
        $totalEvalNote = Answer::getTotalNote($eval_survey->id, $row->user_id, $e->id);
      }

      $career_sid = Entretien_evaluation::getItemsId($e->id, 2);
      $career_sid = isset($career_sid[0]) ? $career_sid[0] : 0;
      $carrer_survey = Survey::find($career_sid);
      if (!$carrer_survey)  {
        $totalCareerNote = 0;
      } else {
        $totalCareerNote = Answer::getTotalNote($carrer_survey->id, $row->user_id, $e->id);
      }

      $avg = ($totalEvalNote + $totalCareerNote) / 2;
      return number_format($avg, 2) + 0;
    });

    // render the table
    return $table->render($query);
  }

  public function apercu($id)
  {
    ob_start();
    $eu = Entretien_user::find($id);
    $eid = $eu->entretien_id;
    $uid = $eu->user_id;
    $e = Entretien::findOrFail($eu->entretien_id);
    if ($e->user_id != User::getOwner()->id) {
      abort(403);
    }
    $user = User::findOrFail($uid);
    $evaluations = Entretien::findEvaluations($e);

    $itemsId = Entretien_evaluation::getItemsId($eid, 9);
    $objectifsPersonnal = EntretienObjectif::whereIn('id', $itemsId)->where('type', 'Personnel')->get();
    $objectifsTeam = EntretienObjectif::whereIn('id', $itemsId)->where('type', 'Equipe')->get();

    $formations = Formation::where('user_id', $user->id)->where('entretien_id', $e->id)->get();
    $salaries = Salary::where('mentor_id', $user->parent ? $user->parent->id : $user->id)->where('entretien_id', $e->id)->get();
    $skill = Skill::where('function_id', $user->function)->first();
    if (!$skill) $skill = new Skill();
    $comment = Comment::where('entretien_id', $eid)->where('user_id', $uid)->first();
    $entreEvalsTitle = [];
    foreach ($evaluations as $eval) {
      $entreEvalsTitle[] = $eval->title;
    }
    echo view('entretiens.apercu', compact('eu', 'entreEvalsTitle', 'e', 'user', 'salaries', 'objectifsPersonnal', 'objectifsTeam', 'formations', 'skill', 'comment', 'evaluations'));
    $content = ob_get_clean();
    return ['title' => "Aperçu de l'entretien", 'content' => $content];
  }

  public function printPdf($id)
  {
    $eu = Entretien_user::find($id);
    $eid = $eu->entretien_id;
    $uid = $eu->user_id;
    $e = Entretien::findOrFail($eu->entretien_id);
    if ($e->user_id != User::getOwner()->id) {
      abort(403);
    }
    $user = User::findOrFail($uid);
    $surveyId = Evaluation::surveyId($e->id, 1);
    $survey = Survey::find($surveyId);
    $comment = Comment::where('entretien_id', $eid)->where('user_id', $uid)->first();
    $entreEvalsTitle = $e->evaluations->pluck('title')->toArray();
    $itemsId = Entretien_evaluation::getItemsId($eid, 9); // Objectifs = 9
    $formations = Formation::where('user_id', $user->id)->where('entretien_id', $e->id)->get();
    $primes = Salary::where('mentor_id', $user->parent ? $user->parent->id : $user->id)->where('entretien_id', $e->id)->get();
    $objectifsPersonnal = EntretienObjectif::whereIn('id', $itemsId)->where('type', 'Personnel')->get();
    $objectifsTeam = EntretienObjectif::whereIn('id', $itemsId)->where('type', 'Equipe')->get();
    $skill = Skill::where('function_id', $user->function)->first();
    if (!$skill) $skill = new Skill();
    $evaluator_id = $eu->mentor_id;

    // skills charts
    $chartData = [];
    foreach($skill->getSkillsTypes() as $key => $type) {
      $field = 'skill_type_'.$type['id'];
      $data = [
        'type' => 'radar',
        'data' => [
          'labels' => $skill->getDataAsArray($key),
          'datasets' => [
            [
              'label' => __('Collaborateur'),
              'data' => array_values(\App\Skill::getFieldNotes($e->id, $user->id, $user->parent->id, $field, 'user'))
            ],
            [
              'label' => __('Manager'),
              'data' => array_values(\App\Skill::getFieldNotes($e->id, $user->id, $user->parent->id, $field, 'mentor'))
            ],
          ]
        ]
      ];
      $chartData['skill_type_'.$key] = urlencode(json_encode($data));
    }

    // objectifs personnal charts
    foreach($objectifsPersonnal as $objectif) {
      $collValues = isset(\App\Objectif_user::getValues($e->id, $user->id, $objectif->id)['collValues']) ? \App\Objectif_user::getValues($e->id, $user->id, $objectif->id)['collValues'] : [];
      $mentorValues = isset(\App\Objectif_user::getValues($e->id, $user->id, $objectif->id)['mentorValues']) ? \App\Objectif_user::getValues($e->id, $user->id, $objectif->id)['mentorValues'] : [];
      $data = [
        'type' => 'radar',
        'data' => [
          'labels' => $objectif->getIndicatorsTitle(),
          'datasets' => [
            [
              'label' => __('Collaborateur'),
              'data' => array_values($collValues)
            ],
            [
              'label' => __('Manager'),
              'data' => array_values($mentorValues)
            ],
          ]
        ]
      ];
      $chartData[$objectif->id] = urlencode(json_encode($data));
    }

    // objectifs team chart
    foreach($objectifsTeam as $objectif) {
      $teamValues = isset(\App\Objectif_user::getValues($e->id, $user->id, $objectif->id)['teamValues']) ? \App\Objectif_user::getValues($e->id, $user->id, $objectif->id)['teamValues'] : [];
      $data = [
        'type' => 'radar',
        'data' => [
          'labels' => $objectif->getIndicatorsTitle(),
          'datasets' => [
            [
              'label' => __('Manager'),
              'data' => array_values($teamValues)
            ],
          ]
        ]
      ];
      $chartData[$objectif->id] = urlencode(json_encode($data));
    }

    $pdf = \PDF::loadView('entretiens.print-pdf', compact('e', 'user', 'survey', 'comment', 'skill', 'entreEvalsTitle', 'formations', 'primes', 'chartData', 'objectifsPersonnal', 'objectifsTeam', 'evaluator_id'));

    return $pdf->download('synthese-entretien-evaluation.pdf');

  }

  public function reminder(Request $request) {

    $ids = $request->get('ids', []);
    if (empty($ids)) return;
    $usersId = [];
    $eid = 0;
    $entretien = null;
    foreach ($ids as $id) {
      $e_u = Entretien_user::find($id);
      $eid = $e_u->entretien_id;
      $entretien = Entretien::find($eid);
      $usersId[] = [
        'user_id' => $e_u->user_id,
        'mentor_id' => $entretien->isFeedback360() ? $e_u->mentor_id : $e_u->user_id,
      ];
    }

    $role = $request->params['role'];

    if (empty($usersId) || !is_numeric($eid)) {
      return ['status' => 'danger', 'message' => __("Aucun utilisateur n'a été sélectionné ou entretien ID introuvable")];
    }

    if ($role == 'coll') {
      $template = Email::getAll()->where('ref', 'auto_eval')->first();
    } else {
      $template = Email::getAll()->where('ref', 'mentor_eval')->first();
    }


    $i = 0;
    foreach ($usersId as $item) {
      $canReceive = true;
      if ($role == 'mentor') {
        $reciever = User::findOrFail($item['mentor_id']);
        $mentorHasSubmitted = Entretien_user::mentorHasSubmitedEval($eid, $item['user_id'], $item['mentor_id']);
        if ($mentorHasSubmitted) $canReceive = false;
      } else {
        $reciever = User::findOrFail($item['user_id']);
        $userHasSubmitted = Entretien_user::userHasSubmitedEval($eid, $uid);
        if ($userHasSubmitted) $canReceive = false;
      }
      if (!$canReceive) continue;
      MailerController::send($reciever, $entretien, $template);
      $i++;
    }

    return [
      'status' => 'success',
      'message' => __("Un email de relance a bien été envoyé à(aux) :count utilisateur(s) sélectionné(s)", ['count' => $i])
    ];
  }

  public function delete(Request $request) {
    $ids = $request->get('ids', []);
    if (empty($ids)) return;
    $deleteEvalEmail = Email::getAll()->where('ref', 'delete_eval')->first();
    foreach ($ids as $id) {
      $e_u = Entretien_user::find($id);
      $entretien = Entretien::find($e_u->entretien_id);
      \DB::table('entretien_user')->where('id', $id)->delete();
      /*if ($entretien->isFeedback360()) {
        $reciever = User::find($e_u->mentor_id);
      } else {
        $reciever = User::find($e_u->user_id);
      }
      $campaignData = [
        'entretien_id' => $entretien->id,
        'email_id' => $deleteEvalEmail->id,
        'receiver' => $reciever->email,
        'shedule_type' => 'now',
        'sheduled_at' => date('Y-m-d H:i'),
      ];
      Campaign::create($campaignData);*/
    }

    return ['status' => 'success', 'message' => __("La suppression a bien été effectuée, un email de suppression de l'évaluation sera envoyé pour les utilisateurs selectionnées")];
  }

  public function reopen(Request $request)
  {
    if ($request->method() == 'POST') {
      $fields = $request->fields;
      $ids = json_decode($request->get('ids', []), true);
      if (empty($ids)) return;
      $fieldsToUpdate = [];
      foreach ($ids as $id) {
        $row = Entretien_user::find($id);
        if (in_array('user', $fields)) {
          $fieldsToUpdate['user_submitted'] = $row->user_submitted == 2 ? 1 : $row->user_submitted;
        }
        if (in_array('mentor', $fields)) {
          $fieldsToUpdate['mentor_submitted'] = $row->mentor_submitted == 2 ? 1 : $row->mentor_submitted;
        }
        Entretien_user::where('user_id', $row->user_id)->where('mentor_id', $row->mentor_id)
          ->where('entretien_id', $row->entretien_id)->update($fieldsToUpdate);;
      }
      return [
        'status' => "success",
        'message' => __("L'opération a été effectué avec succès"),
        'redirectUrl' => route('home')
      ];
    }
    ob_start();
    $ids = $request->get('ids', []);
    echo view('entretiens.reopen', compact('ids'));
    $content = ob_get_clean();
    return ['title' => __("Réouvrir"), 'content' => $content];
  }

  public function export(Request $request) {
    try {
      $filesys = new Filesystem();
      $filesys->deleteDirectory(public_path('/excel'));
      $ids = $request->get('ids', []);
      $params = $request->get('params', []);
      $eid = isset($params['eid']) ? $params['eid'] : 0;
      if (empty($ids) || $eid == 0) return;
      $filename = '';
      $entretien = Entretien::find($eid);
      $evaluationsTitle = $entretien->evaluations->pluck('title')->toArray();
      $time = time();
      $eid = 0;
      foreach ($ids as $id) {
        $e_u = Entretien_user::find($id);
        $eid = $e_u->entretien_id;
        $e = Entretien::find($eid);
        $uid = $e_u->user_id;
        $user = User::find($uid);
        $evaluator = $e->isFeedback360() ? User::find($e_u->mentor_id) : $user->parent;

        $filename = 'Réponses-'.$user->fullname().'-'.$evaluator->fullname();
        $export = Excel::create($filename, function($excel) use ($e_u, $eid, $uid, $evaluationsTitle) {

          if (in_array('Evaluation annuelle', $evaluationsTitle)) {
            $excel->sheet('Evaluation annuelle', function($sheet) use ($e_u, $eid, $uid) {
              $sid = Entretien_evaluation::getItemsId($e_u->entretien_id, 1);
              $sid = isset($sid[0]) ? $sid[0] : 0;
              $survey = Survey::find($sid);
              if (!$survey) $survey = new Survey();
              $evaluator_id = $e_u->mentor_id;
              $sheet->loadView('entretiens.xls.evaluations', compact('survey', 'eid', 'uid', 'evaluator_id'));
            });
          }

          if (in_array('Carrières', $evaluationsTitle)) {
            $excel->sheet('Carrières', function ($sheet) use ($e_u, $eid, $uid) {
              $sid = Entretien_evaluation::getItemsId($e_u->entretien_id, 2);
              $sid = isset($sid[0]) ? $sid[0] : 0;
              $survey = Survey::find($sid);
              if (!$survey) $survey = new Survey();
              $sheet->loadView('entretiens.xls.careers', compact('survey', 'eid', 'uid'));
            });
          }

          if (in_array('Formations', $evaluationsTitle)) {
            $excel->sheet('Formations', function ($sheet) use ($e_u, $eid, $uid) {
              $formations = Formation::where('user_id', $uid)
                ->where('entretien_id', $eid)
                ->orderBy('date', 'DESC')
                ->get();
              $sheet->loadView('entretiens.xls.formations', compact('formations', 'eid', 'uid'));
            });
          }

          if (in_array('Primes', $evaluationsTitle)) {
            $excel->sheet('Primes', function ($sheet) use ($e_u, $eid, $uid) {
              $primes = Salary::where('user_id', $uid)
                ->where('entretien_id', $eid)
                ->orderBy('created_at', 'DESC')
                ->get();
              $sheet->loadView('entretiens.xls.primes', compact('primes', 'eid', 'uid'));
            });
          }

          if (in_array('Commentaires', $evaluationsTitle)) {
            $excel->sheet('Commentaires', function ($sheet) use ($e_u, $eid, $uid) {
              $comment = Comment::where('entretien_id', $eid)->where('user_id', $uid)->first();
              if (!$comment) $comment = new Comment();
              $sheet->loadView('entretiens.xls.comments', compact('comment', 'eid', 'uid'));
            });
          }

          if (in_array('Compétences', $evaluationsTitle)) {
            $excel->sheet('Compétences', function ($sheet) use ($e_u, $eid, $uid) {
              $user = User::find($uid);
              $parent_id = $user->parent ? $user->parent->id : 0;
              $skill = Skill::where('function_id', $user->function)->first();
              if (!$skill) $skill = new Skill();
              $sheet->loadView('entretiens.xls.skills', compact('skill', 'eid', 'uid', 'parent_id'));
            });
          }

          if (in_array('Objectifs', $evaluationsTitle)) {
            $excel->sheet('Objectifs', function ($sheet) use ($e_u, $eid, $uid) {
              $itemsId = Entretien_evaluation::getItemsId($eid, 9);
              $objectifsPersonnal = EntretienObjectif::whereIn('id', $itemsId)->where('type', 'Personnel')->get();
              $objectifsTeam = EntretienObjectif::whereIn('id', $itemsId)->where('type', 'Equipe')->get();
              $sheet->loadView('entretiens.xls.objectifs', compact('objectifsPersonnal', 'objectifsTeam', 'eid', 'uid'));
            });
          }

        })->store('xlsx', public_path('/excel/exports/'.$time));
      }

      $tmpZipUrl = "excel/Réponses-campagne-".strtolower($entretien->titre)."-".date('dmYHi').".zip";
      $zip = new \ZipArchive();
      if ($zip->open($tmpZipUrl, \ZipArchive::CREATE)) {
        $files = array_diff(scandir(public_path('/excel/exports/'.$time)), array('.', '..'));
        foreach ($files as $key => $filename) {
          $zip->addFile(public_path('/excel/exports/'.$time.'/'.$filename), $filename);
        }
        $zip->close();
      } else {
        return ['status' => 'danger', 'message' => "Impossible de créer le zip"];
      }

      $file_link = $_ENV['APP_URL'] . "/".$tmpZipUrl;
      $filesys->deleteDirectory(public_path('/excel/exports/'));

      return [
        'status' => 'file_success',
        'file_link' => $file_link
      ];
    } catch (\Exception $e) {
      return [
        'status' => 'alert',
        'title' => __("Erreur survenue"),
        'content' => __("Une erreur est survenue lors de l'exportation, réessayez plus tard !: :error", ['error' => $e->getMessage()])
      ];
    }

  }

}