<?php

namespace App\Http\Controllers;

use App\Department;
use App\Fonction;
use App\Http\Service\Table;
use App\Team;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\CsvData;
use Auth;
use App\User;
use App\Role;
use App\Entretien;
use App\Permission;
use Session;
use Zizaco\Entrust\Entrust;

class UserController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('permission:users')->only('index');
  }

  public function getTable(Request $request) {
    $table = new Table($request);
    $query = User::getUsers()->orderBy('id', 'DESC');
    if ($q = $request->get('q', false)) {
      $query->whereRaw('(name LIKE ? OR last_name LIKE ? OR email LIKE ?)', ["%".$q."%", "%".$q."%", "%".$q."%"]);
    }
    if ($department = $request->get('department', false)) {
      $query->where('service', '=', $department);
    }
    if ($function = $request->get('function', false)) {
      $query->where('function', '=', $function);
    }
    if ($role = $request->get('role')) {
      $query->whereHas('roles', function ($query) use ($role) {
        $query->where('id', '=', $role);
      });
    }
    if (!empty($team)){
      $query->whereHas('teams', function ($query) use ($team) {
        $query->where('team_id', '=', $team);
      });
    }

    $table->setPrimaryKey('id');
    $table->setDateFormat('d/m/Y H:i');
    $table->setBulkActions(true);

    $table->addColumn('name', 'Prénom', function ($entity) {
      return '<a href="'. route('user.profile', ['id' => $entity->id]) .'">'. $entity->name .'</a>';
    });
    $table->addColumn('last_name', 'Nom', function ($entity) {
      return '<a href="'. route('user.profile', ['id' => $entity->id]) .'">'. $entity->last_name .'</a>';
    });
    $table->addColumn('email', 'Email');
    $table->addColumn('roles', 'Rôles', function ($entity) {
      return isset($entity->roles[0]) ? $entity->roles[0]->name : '---';
    });
    $table->addColumn('function', 'Fonction', function ($entity) {
      $fonction = Fonction::find($entity->function);
      return $fonction ? $fonction->title : '---';
    });
    $table->addColumn('manager', 'Manager', function ($entity) {
      if ($entity->parent) {
        return '<a href="'. route('user.profile', ['id' => $entity->parent->id]) .'">'. $entity->parent->fullname() .'</a>';
      }
      return '---';
    });
    $table->addColumn('created_at', 'Créé le');

    // define table actions
    $table->addAction('show', [
      'icon' => 'fa fa-eye',
      'label' => 'Voir le profil',
      'route' => ['name' => 'user.profile', 'args' => ['id' => '[id]']],
      'bulk_action' => false,
    ]);
    $table->addAction('edit', [
      'icon' => 'fa fa-pencil',
      'label' => 'Modifier',
      'route' => ['name' => 'user.form', 'args' => ['id' => '[id]']],
      'attrs' => [
        'chm-modal'=> '',
        'chm-modal-options'=> '{"form":{"attributes":{"id":"userForm", "target-table":"[chm-table]"}}}',
      ],
      'bulk_action' => false,
    ]);
    // define table actions
    $table->addAction('delete', [
      'icon' => 'fa fa-trash',
      'label' => 'Supprimer',
      'callback' => 'chmUser.delete',
      'bulk_action' => true,
    ]);

    // render the table
    return $table->render($query);
  }

  public function index(Request $request)
  {
    $roles = Role::select('id', 'name')->where('name', '<>', ['ROOT'])->where('name', '<>', ['ADMIN'])->get();
    $departments = Department::getAll()->get();
    $fonctions = Fonction::getAll()->get();
    $teams = Team::getAll()->get();
    return view('users.index', [
      'roles' => $roles,
      'departments' => $departments,
      'fonctions' => $fonctions,
      'teams' => $teams,
    ]);
  }

  public function profile()
  {
    $user = Auth::user();
    return view('users.profile', compact('user'));
  }

  public function show($id)
  {
    $user = User::findOrFail($id);
    return view('users.profile', compact('user'));
  }

  public function form(Request $request)
  {
    if ($request->method() == "POST") {
      return $this->store($request);
    }
    $id = $request->id;
    $roles_ids = [];
    ob_start();
    if (isset($id) && is_numeric($id)) {
      $user = User::findOrFail($id);
      if ($user->roles) {
        foreach ($user->roles as $role) {
          $roles_ids [] = $role->id;
        }
      }
      $title = "Mettre à jour le profil";
    } else {
      $user = new User();
      $title = "Ajouter un utilisateur";
    }
    $roles = Role::select('id', 'name')->where('name', '<>', 'ROOT')
      ->where('name', '<>', 'ADMIN')->get();
    $users = User::getUsers()->get();
    $fonctions = Fonction::getAll()->get();
    $departments = Department::getAll()->get();
    $teams = Team::getAll()->get();
    $userTeamsId = $user->teams()->get()->pluck('id')->toArray();
    echo view('users.form', compact('departments', 'fonctions', 'user', 'roles', 'roles_ids', 'users', 'teams', 'userTeamsId'));
    $content = ob_get_clean();
    return ['title' => $title, 'content' => $content];
  }

  public function store(Request $request)
  {
    $id = $request->input('id', false);
    $rules = [
      'avatar' => 'max:500',
      'name' => 'required|regex:/^[\pL\s\-]+$/u|min:2|max:25',
      'last_name' => 'required|regex:/^[\pL\s\-]+$/u|min:2|max:25',
      'email' => 'required',
      'password' => 'confirmed|min:6',
      'tel' => 'regex:/^\d{2}\s?\d{2}\s?\d{2}\s?\d{2}\s?\d{2}$/',
      'function' => 'numeric',
      'service' => 'numeric',
      'mle' => 'numeric',
    ];
    $query = User::where('email', $request->email);
    if ($id) {
      $user = User::findOrFail($id);
      if (!empty($request->password) || !empty($request->password_confirmation)) {
        $rules['password'] = 'required|confirmed|min:6';
        $user->password = bcrypt($request->password);
      } else {
        $rules['password'] = '';
      }
      $exist = $query->where('id', '<>', $id)->count();
    } else {
      $exist = $query->count();
      $user = new User();
    }
    $validator = \Validator::make($request->all(), $rules);
    $messages = $validator->errors();
    if($exist > 0) $messages->add('exist_email', 'Cet email existe déjà !');
    if (count($messages) > 0) {
      return ["status" => "danger", "message" => $messages];
    }
    $user->name = $request->name;
    $user->last_name = $request->last_name;
    $user->email = $request->email;
    $password = !empty($request->password) ? $request->password : "password";
    if (!$id) $user->password = bcrypt($password);
    $user->tel = $request->tel;
    $user->function = $request->function;
    $user->service = $request->service;
    $user->date_recruiting = $request->date_recruiting;
    $user->mle = $request->mle;
    $user->status = 1;
    if ($request->user_id != null) {
      $user->user_id = $request->user_id;
    } else {
      $user->user_id = 0;
    }
    $user->society_id = User::getOwner()->id;
    $user->save();
    if ($file = $request->hasFile('avatar')) {
      $file = $request->file('avatar');
      $fileName = time() . "_" . $file->getClientOriginalName();
      $destinationPath = public_path('/uploads/avatars/' . $user->id);
      $file->move($destinationPath, $fileName);
      $user->avatar = $fileName;
      $user->save();
    }
    if ($request->roles != null) {
      $user->roles()->sync($request->roles);
    }
    $user->teams()->sync([$request->team_id]);
    if ($user->save()) {
      return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
    } else {
      return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
    }

  }

  public function delete(Request $request)
  {
    if (empty($request->ids)) return;
    foreach($request->ids as $uid) {
      $user = User::find($uid);
      try {
        $user->delete();
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

  public function import(Request $request)
  {
    return view('users.data.import');
  }

  public function parseImport(Request $request)
  {
    $path = $request->file('usersDataCsv')->getRealPath();
    $csv_data = Excel::load($path, function ($reader) {
    })->get()->toArray();
    if (count($csv_data) > 0) {
      $csv_header_fields = [];
      foreach ($csv_data[0] as $key => $value) {
        $csv_header_fields[] = $key;
      }
    }
    Session::forget('session_csv_data');
    Session::forget('session_csv_headers');
    Session::push('session_csv_data', $csv_data);
    Session::push('session_csv_headers', $csv_header_fields);
    //dd(json_encode($csv_data));
    return view('users.data.import_fields', compact('csv_header_fields', 'csv_data'));
  }

  public function getRoleByName($rolesName)
  {
    $rolesids = [];
    foreach (explode(", ", $rolesName) as $name) {
      $role = Role::where('name', $name)->first();
      if ($role) {
        $rolesids[] = $role->id;

      }
    }
    return $rolesids;
  }

  public function processImport(Request $request)
  {
    $csv_data = Session::get('session_csv_data')[0];
    $fields = Session::get('session_csv_headers')[0];
    $maxUserId = User::whereRaw('id = (select max(`id`) from users)')->first();
    $count = $maxUserId->id;
    $added = 0;
    $updated = 0;
    foreach ($csv_data as $row) {
      $prenom = $row[$fields[0]];
      $nom = $row[$fields[1]];
      $email = $row[$fields[2]];
      $fonction = $row[$fields[3]];
      $department = $row[$fields[4]];
      $roles = $row[$fields[5]];
      $mentorEmail = $row[$fields[6]];
      $tel = $row[$fields[7]];

      $mentor = User::getUsers()->where('email', '=', $mentorEmail)->first();
      if (!empty($prenom) && !empty($nom) && !empty($email) && !empty($roles) && (!empty($mentorEmail) && !is_null($mentor) || $mentorEmail == '0')) {
        $existUser = User::where('email', $email)->first();
        if ($existUser) {
          $user = $existUser;
          $updated += 1;
        } else {
          $user = new User();
          $user->id = $count + 1;
          $added += 1;
        }
        $user->name = $prenom;
        $user->last_name = $nom;
        $user->email = $email;
        $user->password = bcrypt("password");
        $user->tel = $tel;
        $user->status = 1;
        $user->function = $this->getFunctionIdByName($fonction);
        $user->service = $this->getDepartmentIdByName($department);

        if ($mentor != null) {
          $user->user_id = $mentor->id;
        } else {
          $user->user_id = 0;
        }
        $user->society_id = Auth::user()->id;
        $user->save();
        $user->roles()->sync($this->getRoleByName($roles));
        $count++;
      } else {
        return redirect('users')->with("warning", "Une erreur est survenu lors l'importation. il se peut que un des champs obligatoire(prénom, nom, email / ou email du manager n'existe pas, role, manager email) est vide!");
      }
    }
    return redirect('users')->with('success', 'Les utilisateurs ont été importés avec succès avec ' . $added . ' ajout et ' . $updated . ' mis à jour !');

  }


  public function getFunctionIdByName($function) {
    $user_id = Auth::user()->id;
    if (empty(trim($function))) return null;
    $func = Fonction::where('title', $function)->where('user_id', $user_id)->first();
    if (isset($func->id)) {
      return $func->id;
    } else {
      $f = new Fonction();
      $f->user_id = $user_id;
      $f->title = $function;
      $f->save();
      return $f->id;
    }
  }

  public function getDepartmentIdByName($department) {
    $user_id = Auth::user()->id;
    if (empty(trim($department))) return null;
    $dept = Department::where('title', $department)->where('user_id', $user_id)->first();
    if (isset($dept->id)) {
      return $dept->id;
    } else {
      $d = new Department();
      $d->user_id = $user_id;
      $d->title = $department;
      $d->save();
      return $d->id;
    }
  }


}
