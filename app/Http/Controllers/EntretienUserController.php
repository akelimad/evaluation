<?php

namespace App\Http\Controllers;

use App\Entretien_user;
use Auth;
use Session;
use DB;
use Excel;
use Symfony\Component\HttpFoundation\Request;

class EntretienUserController extends Controller
{
  public function reminder(Request $request) {
    //dd($request->params);
  }

  public function delete(Request $request) {
    $eid = $request->params['eid'];
    $usersId = $request->params['usersId'];
    \DB::table('entretien_user')->where('entretien_id', $eid)->whereIn('user_id', $usersId)->delete();

    return ['status' => 'success', 'message' => "Bien supprimé"];
  }
}