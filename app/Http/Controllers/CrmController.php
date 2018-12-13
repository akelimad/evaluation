<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Role;

class CrmController extends Controller
{
  public function index(Request $request)
  {
    $societies = User::with('roles')->whereHas('roles', function ($query) {
        $query->where('name', '=', 'ADMIN');
    })->paginate(10);
    return view('crm.index', [
        'results' => $societies,
    ]);
  }

  public function form($id = null)
  {
    ob_start();
    if( isset($id) && is_numeric($id) ) {
      $user = User::find($id);
      $title = "Modifier le compte";
    } else {
      $title = "Ajoute un compte";
      $user = new User();
    }
    $roles = Role::select('id', 'name')->where('name', '=', 'ADMIN')->get();
    echo view('crm.form', compact('user', 'roles'));
    $content = ob_get_clean();
    return ['title' => $title, 'content' => $content];
  }

  public function store(Request $request)
  {
    $id = $request->input('id', false);
    $rules = [
      'logo'    => 'max:500',
      'name'      => 'required|regex:/^[\pL\s\-]+$/u|min:3|max:25',
      'first_name' => 'required|regex:/^[\pL\s\-]+$/u|min:3|max:25',
      'last_name' => 'required|regex:/^[\pL\s\-]+$/u|min:3|max:25',
      'email'     => 'required|unique:users,email',
      'password'  => 'required|confirmed|min:6',
    ];
    if($id) {
      $user = User::find($id);
      $rules['email'] = 'required|unique:users,email,'.$user->id;
      if(!empty($request->password) || !empty($request->password_confirmation)){
        $rules['password'] = 'required|confirmed|min:6';
        $user->password= bcrypt($request->password);
      }else{
        $rules['password'] = '';
      }
    }else{
      $user = new User();
    }
    $validator = \Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return ["status" => "danger", "message" => $validator->errors()->all()];
    }
    $user->name= $request->name;
    $user->first_name = $request->first_name;
    $user->last_name = $request->last_name;
    $user->email= $request->email;
    if(!$id) $user->password= bcrypt($request->password);
    $user->status= 1;
    if($request->user_id != null) {$user->user_id= $request->user_id; }
    $user->salary= $request->salary;
    $user->save();
    if($file = $request->hasFile('logo')) {
      $file = $request->file('logo') ;
      $fileName = time()."_".$file->getClientOriginalName();
      $destinationPath = public_path('/uploads/logos/'.$user->id) ;
      $file->move($destinationPath,$fileName);
      $user->logo = $fileName ;
      $user->save();
    }
    if($request->roles != null ) {
      $user->roles()->sync($request->roles); 
    }
    if($user->save()) {
      return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
    } else {
      return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
    }
  }

  public function delete(Request $request, $id){
    $user = User::find($id);
    $user->delete();
  }


}
