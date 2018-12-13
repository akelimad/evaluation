<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\CsvData;
use App\Http\Requests;
use Auth;
use App\User;
use App\Role;
use App\Entretien;
use App\Permission;
use App\Setting;
use Session;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function profile(){
        $user = Auth::user();
        return view('users.profile', compact('user'));
    }

    public function show($id){
        $user = User::find($id);
        return view('users.profile', compact('user'));
    }

    public function indexUsers(Request $request){
        $per_page = $selected = 10;
        if( isset($request->per_page) && $request->per_page != "all" ){
            $per_page = $request->per_page;
            $selected = $per_page;
        }else if(isset($request->per_page) && $request->per_page == "all"){
            $per_page = 500;
            $selected = "all";
        }
        $entretiens = Entretien::select('id', 'titre')->get();

        $users = User::getUsers()->paginate($per_page);
        // dd($societyUsers);

        // $users = User::with('roles')->where('email', '<>', Auth::user()->email)->orderBy('id', 'DESC')->paginate($per_page);
        $roles = Role::select('id', 'name')->where('name', '<>', 'ADMIN')->get();
        return view('users.index', [
            'results'    => $users,
            'selected'   => $selected,
            'roles'      => $roles,
            'entretiens' => $entretiens
        ]);
    }

    public function filterUsers(Request $request){
        $name = $request->name;
        $service = $request->service;
        $function = $request->function;
        $roleSelected = $request->role;
        $roles = Role::select('id', 'name')->get();
        $users = User::with('roles')->paginate(15);
        $per_page = $selected = 10;
        if( isset($request->per_page) && $request->per_page != "all" ){
            $per_page = $request->per_page;
            $selected = $per_page;
        }else if(isset($request->per_page) && $request->per_page == "all"){
            $per_page = 500;
            $selected = "all";
        }
        if(!empty($roleSelected)){
            $users = User::with('roles')
            ->where('name', 'like', '%'.$name.'%')
            ->where('service', '=', $service)
            ->where('function', '=', $function)
            ->whereHas('roles', function ($query) use ($roleSelected) {$query->where('id', '=', $roleSelected); } )
            ->paginate($per_page);
        }else{
            $users = User::with('roles')
            ->where('name', 'like', '%'.$name.'%')
            ->where('service', '=', $service)
            ->where('function', '=', $function)
            ->paginate($per_page);
        }
        return view('users.index', [
            'results'=> $users,
            'selected' => $selected,
            'roles'=> $roles,
            'name'=> $name,
            'service'=> $service,
            'function'=> $function,
            'roleSelected' => $roleSelected
        ]);
    }

    public function createUser(){
        ob_start();
        $roles = Role::select('id', 'name')
            ->where('name', '<>', 'ROOT')
            ->where('name', '<>', 'ADMIN')
            ->get();
        $users = User::getUsers()->with('roles')->whereHas('roles', function ($query) {
            $query->where('name', '=', 'MENTOR');
        })->get();
        $functions = Setting::asList('society.functions', false, true);
        $services = Setting::asList('society.services', false, true);
        echo view('users.form', compact('services', 'functions', 'roles', 'users'));
        $content = ob_get_clean();
        return ['title' => 'Ajouter un utilisateur', 'content' => $content];
    }

    public function storeUser(Request $request){
        $id = $request->input('id', false);
        $rules = [
            'avatar'    => 'max:500',
            'name'      => 'required|regex:/^[\pL\s\-]+$/u|min:3|max:25',
            'last_name' => 'required|regex:/^[\pL\s\-]+$/u|min:3|max:25',
            'email'     => 'required|unique:users,email',
            'password'  => 'required|confirmed|min:6',
            'society'   => 'regex:/^[\pL\s\-]+$/u',
            'city'      => 'regex:/^[\pL\s\-]+$/u',
            'country'   => 'regex:/^[\pL\s\-]+$/u',
            'society'   => 'regex:/^[\pL\s\-]+$/u',
            'tel'       => 'regex:/^\d{2}\s?\d{2}\s?\d{2}\s?\d{2}\s?\d{2}$/',
            'fix'       => 'regex:/^\d{2}\s?\d{2}\s?\d{2}\s?\d{2}\s?\d{2}$/',
            'function'  => 'numeric',
            'service'   => 'numeric',
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
        $user->last_name = $request->last_name;
        $user->email= $request->email;
        if(!$id) $user->password= bcrypt($request->password);
        $user->address= $request->address;
        $user->society= $request->society;
        $user->zip_code= $request->zip_code;
        $user->city= $request->city;
        $user->country= $request->country;
        $user->tel= $request->tel;
        $user->fix= $request->fix;
        $user->about= $request->about;
        $user->function= $request->function;
        $user->service= $request->service;
        $user->qualification= $request->qualification;
        $user->status= 1;
        if($request->user_id != null) {$user->user_id= $request->user_id; }
        $user->salary= $request->salary;
        $user->society_id = Auth::user()->id;
        $user->save();
        if($file = $request->hasFile('avatar')) {
            $file = $request->file('avatar') ;
            $fileName = time()."_".$file->getClientOriginalName();
            $destinationPath = public_path('/uploads/avatars/'.$user->id) ;
            $file->move($destinationPath,$fileName);
            $user->avatar = $fileName ;
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

    public function editUser($id){
        ob_start();
        $user = User::find($id);
        $users = User::select('id','email', 'name', 'last_name')->get();
        $roles_ids = [];
        if($user->roles){
            foreach($user->roles as $role){
                $roles_ids []= $role->id;
            }
        }
        $roles = Role::select('id', 'name')->where('name', '<>', 'ADMIN')->get();
        $functions = Setting::asList('society.functions', false, true);
        $services = Setting::asList('society.services', false, true);
        echo view('users.form', compact('services' ,'functions', 'user', 'users', 'roles','roles_ids'));
        $content = ob_get_clean();
        return ['title' => 'Modifier un utilisateur', 'content' => $content];
    }

    public function deleteUser(Request $request, $id){
        $user = User::find($id);
        $user->delete();
        return redirect('users');
    }

    public function importUsers(Request $request){
        return view('users.data.import');
    }

    public function parseImport(Request $request){
        $path = $request->file('usersDataCsv')->getRealPath();
        $csv_data = Excel::load($path, function($reader) {})->get()->toArray();
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
        return view('users.data.import_fields', compact( 'csv_header_fields','csv_values_fields', 'csv_data'));
    }

    public function getRoleByName($rolesName){
        $rolesids = [];
        foreach (explode(", ",$rolesName) as $name) {
            $role = Role::where('name', $name)->first();
            if($role){
                $rolesids[]= $role->id;
                
            }
        }
        return $rolesids;
    }

    public function processImport(Request $request){
        $csv_data = Session::get('session_csv_data')[0];
        $fields = Session::get('session_csv_headers')[0];
        $maxUserId = User::whereRaw('id = (select max(`id`) from users)')->first();
        $count = $maxUserId->id;
        $added = 0;
        $updated = 0;
        foreach ($csv_data as $row) {
            if (!empty($row[$fields[0]]) && !empty($row[$fields[1]]) && !empty($row[$fields[2]]) && $row[$fields[3]] && !empty($row[$fields[4]]) || $row[$fields[4]] =="0" ) {
                $existUser = User::where('email', $row[$fields[2]])->first();
                if($existUser){
                    $user = $existUser;
                    $updated +=1;
                }else{
                    $user = new User();
                    $user->id= $count+1;
                    $added +=1;
                }
                $user->name= $row[$fields[0]];
                $user->last_name = $row[$fields[1]];
                $user->email= $row[$fields[2]];
                $user->password= bcrypt("password");
                $user->address= $row[$fields[5]];
                $user->zip_code= $row[$fields[6]];
                $user->city= $row[$fields[7]];
                $user->country= $row[$fields[8]];
                $user->fix= $row[$fields[9]];
                $user->tel= $row[$fields[10]];
                $user->function= $row[$fields[11]];
                $user->service= $row[$fields[12]];
                $user->qualification= $row[$fields[13]];
                $user->status= 1;
                $mentor = User::where('email', '=', $row[$fields[4]])->first();
                if($mentor != null){
                    $user->user_id= $mentor->id;
                }else{
                    $user->user_id= 0;
                }
                $user->society_id = Auth::user()->id;
                $user->save();
                $user->roles()->sync($this->getRoleByName($row[$fields[3]]));
                $count++;
            }else{
                return redirect('users')->with('exist_already', 'Une erreur est survenu lors l\'importation. il se peut que un des champs obligatoire(Prénom, nom, email, role, Mentor email) est vide!');
            }
        }
        return redirect('users')->with('import_success', 'Les utilisateurs ont été importés avec succès avec '.$added.' ajout et '.$updated.' mis à jour !');

    }

    public function indexRoles(){
        $roles = Role::where('name', '<>', 'ADMIN')->get();
        return view('users/roles.index' , ['roles' => $roles]);
    }
    public function createRole(){
        ob_start();
        $permissions = Permission::all();
        echo view('users.roles.form', ['permissions' => $permissions]);
        $content = ob_get_clean();
        return ['title' => 'Ajouter un rôle', 'content' => $content];
    }
    public function storeRole(Request $request){
        $id = $request->input('id', false);
        if($id){
            $role = Role::find($id);
            $role->name = $request->name;
            $role->display_name = $request->display_name;
            $role->description = $request->description;
            $role->save();
            if($request->permissions){
            $role_perms = [];
                foreach ($role->perms()->get() as $perm) {
                    $role_perms[] = $perm->id;
                }
                $role->perms()->detach($role_perms);
                $role->perms()->attach($request->permissions);
            }
            
        }else{
            $role = new Role();
            $role->name = $request->name;
            $role->display_name = $request->display_name;
            $role->description = $request->description;
            $role->save();
            // $role->perms()->detach($request->permissions);
            if($request->permissions){
                $role->attachPermissions($request->permissions);   
            }
        }
        if($role->save()) {
            return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
        } else {
            return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
        }

    }

    public function editRole($id){
        ob_start();
        $role = Role::find($id);
        $role_perms = [];
        foreach ($role->perms()->get() as $perm) {
            $role_perms[] = $perm->id;
        }
        $permissions = Permission::all();
        echo view('users/roles.form' , ['role' => $role, 'permissions' => $permissions,'role_perms' => $role_perms]);
        $content = ob_get_clean();
        return ['title' => 'Editer le rôle', 'content' => $content];
    }

    public function deleteRole(Request $request, $id){
        $role = Role::find($id);
        $role->delete();
        return redirect('utilisateurs/roles');
    }

    public function indexPermisions(){
        $permissions = Permission::paginate(10);
        return view('users/permissions.index' ,['permissions' => $permissions]);
    }
    public function createPermission(){
        ob_start();
        return view('users/permissions.create');
        $content = ob_get_clean();
        return ['title' => 'Créer une permission', 'content' => $content];
    }
    public function storePermission(Request $request){
        $id = $request->input('id', false);
        if($id){
            $permission = Permission::find($id);
        }else{
            $permission = new Permission();
        }
        $permission->name = $request->name;
        $permission->display_name = $request->display_name;
        $permission->description = $request->description;
        $permission->save();
        if($permission->save()) {
            return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
        } else {
            return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
        }
    }
    public function editPermission($id){
        ob_start();
        $p = Permission::find($id);
        echo view('users/permissions.edit' ,['p' => $p]);
        $content = ob_get_clean();
        return ['title' => 'Modifier une permission', 'content' => $content];
    }


}
