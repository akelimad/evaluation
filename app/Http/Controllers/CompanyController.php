<?php

namespace App\Http\Controllers;

use App\Http\Service\Table;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Role;

class CompanyController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
  }

  public function getTable(Request $request) {
    $table = new Table($request);
    $query = User::with('roles')->whereHas('roles', function ($query) {
      $query->where('name', '=', 'ADMIN');
    })->orderBy('id', 'DESC');
    if ($q = $request->get('q', false)) {
      $query->whereRaw('(name LIKE ? OR first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)', ["%".$q."%", "%".$q."%", "%".$q."%", "%".$q."%"]);
    }

    $table->setPrimaryKey('id');
    $table->setDateFormat('d/m/Y H:i');
    $table->setBulkActions(true);

    $table->addColumn('name', 'Nom de la société', function ($entity) {
      return $entity->name;
    });
    $table->addColumn('first_name', 'Prénom du contact', function ($entity) {
      return $entity->first_name;
    });
    $table->addColumn('last_name', 'Nom du contact', function ($entity) {
      return $entity->last_name;
    });
    $table->addColumn('email', 'Email');

    $table->addColumn('nbr_users', "Utilisateurs", function ($entity) {
      return $entity->users->count();
    });

    $table->addColumn('created_at', 'Créée le');

    $table->addAction('edit', [
      'icon' => 'fa fa-pencil',
      'label' => 'Modifier',
      'route' => ['name' => 'company.form', 'args' => ['id' => '[id]']],
      'attrs' => [
        'chm-modal'=> '',
        'chm-modal-options'=> '{"form":{"attributes":{"id":"companyForm", "target-table":"[chm-table]"}}}',
      ],
      'bulk_action' => false,
    ]);
    // define table actions
    $table->addAction('delete', [
      'icon' => 'fa fa-trash',
      'label' => 'Supprimer',
      'callback' => 'Company.delete',
      'bulk_action' => false,
    ]);

    // render the table
    return $table->render($query);
  }

  public function index(Request $request)
  {
    return view('companies.index');
  }

  public function form(Request $request)
  {
    if ($request->method() == 'POST') {
      return $this->store($request);
    }
    $id = $request->id;
    ob_start();
    if( isset($id) && is_numeric($id) ) {
      $user = User::findOrFail($id);
      $title = "Modifier le compte";
    } else {
      $title = "Ajoute un compte";
      $user = new User();
    }
    $roles = Role::select('id', 'name')->where('name', '=', 'ADMIN')->get();
    echo view('companies.form', compact('user', 'roles'));
    $content = ob_get_clean();
    return ['title' => $title, 'content' => $content];
  }

  public function store(Request $request)
  {
    $id = $request->id;
    $rules = [
      'logo'      => 'required|max:800',
      'name'      => 'required|min:3|max:25',
      'first_name' => 'required|regex:/^[\pL\s\-]+$/u|min:3|max:25',
      'last_name' => 'required|regex:/^[\pL\s\-]+$/u|min:3|max:25',
      'email'     => 'required|unique:users,email',
      'password'  => 'required|confirmed|min:6',
    ];
    if($id) {
      $user = User::findOrFail($id);
      $rules['email'] = 'required|unique:users,email,'.$user->id;
      if(!empty($request->password) || !empty($request->password_confirmation)){
        $rules['password'] = 'required|confirmed|min:6';
        $user->password= bcrypt($request->password);
      }else{
        $rules['password'] = '';
      }
      if(!empty($user->logo)){
        $rules['logo'] = 'max:500';
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

  public function delete(Request $request){
    if (empty($request->ids)) return;

    foreach($request->ids as $id) {
      try {
        $companyAdmin = User::find($id);
        $companyUsers = $companyAdmin->users;
        foreach ($companyUsers as $companyUser) {
          $companyUser->delete();
        }
        $companyAdmin->delete();
      } catch (\Exception $e) {
        return ["status" => "danger", "message" => "Une erreur est survenue, réessayez plus tard."];
      }
    }

    return response()->json([
      'status' => 'alert',
      'title' => 'Confirmation',
      'content' => '<i class="fa fa-check-circle text-green"></i> La suppression a été effectuée avec succès',
    ]);
  }

  public function uploadLogo(Request $request){
    $user = User::findOrFail($request->id);
    $user->logo = "";
    $user->save();

  }

  public function removeLogo(Request $request){
    $user = User::findOrFail($request->id);
    $logo = $user->logo;
    $user->logo = "";
    $user->save();
    $this->UnlinkImage(public_path('uploads/logos/'.$user->id.'/'.$logo));
    return response()->json([
      'status' => 'success',
      'message' => 'Logo a bien été supprimé'
    ]);
  }

  public function UnlinkImage($filepath)
  {
    if (file_exists($filepath)) {
      unlink($filepath);
    }
  }

}
