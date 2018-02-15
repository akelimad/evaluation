<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\User;
use App\Role;
use App\Permission;

class UserController extends Controller
{

    public function profile(){
        $user = Auth::user();
        return view('users.profile', compact('user'));
    }

    public function show($id){
        $user = User::find($id);
        return view('users.profile', compact('user'));
    }

    public function indexUsers(){
        $users = User::with('roles')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function createUser(){
        $roles = Role::all();
        $users = User::select('id','email')->get();
        return view('users.create', compact('roles', 'users'));
    }

    public function storeUser(Request $request){
        $id = $request->input('id', false);
        if($id) {
            $user = User::find($id);
            $rules=[
                'name' => 'required|max:255',
                'email' => 'unique:users,email,'.$user->id
            ];
            $user->civilite = $request->civilite;
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            if(!empty($request->password) || !empty($request->password_confirmation)){
                $rules = [
                    'password' => 'required|min:6|confirmed',
                ];
                $user->password = bcrypt($request->password);
            }
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return ["status" => "danger", "message" => $validator->errors()->all()];
            }
            $user->detachRoles( $user->roles );
            $user->attachRole( $request->role );
            $user->save();
        } else {
            $rules = [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6',
            ];
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return ["status" => "danger", "message" => $validator->errors()->all()];
            }
            $user = new User();
            $user->name= $request->name;
            $user->last_name = $request->last_name;
            $user->email= $request->email;
            $user->password= bcrypt($request->password);
            $user->address= $request->address;
            $user->society= $request->society;
            $user->zip_code= $request->zip_code;
            $user->city= $request->city;
            $user->country= $request->country;
            $user->tel= $request->tel;
            $user->fix= $request->fix;
            $user->about= $request->about;
            $user->avatar= "";
            $user->function= $request->function;
            $user->service= $request->service;
            $user->qualification= $request->qualification;
            $user->status= $request->status == "on" ? 1 : 0;
            $user->user_id= $request->user_id;
            $user->save();
            $user->attachRole($request->role);
        }
        // if($user->save()) {
        //     return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
        // } else {
        //     return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
        // }
        return redirect('users');

    }

    public function editUser($id){
        ob_start();
        $user = User::find($id);
        $roles_ids = [];
        if($user->roles){
            foreach($user->roles as $role){
                $roles_ids []= $role->id;
            }
        }
        $roles = Role::all();
        echo view('users.edit', compact('user', 'roles','roles_ids'));
        $content = ob_get_clean();
        return ['title' => 'Modifier un utilisateur', 'content' => $content];
    }

    public function destroyUser(Request $request, $id){
        $user = User::find($id);
        $user->delete();
        return redirect('utilisateurs');
    }

    public function indexRoles(){
        $roles = Role::paginate(10);
        return view('users/roles.index' , ['roles' => $roles]);
    }
    public function createRole(){
        //ob_start();
        $permissions = Permission::all();
        return view('users.roles.create', ['permissions' => $permissions]);
        // $content = ob_get_clean();
        // return ['title' => 'Ajouter un rôle', 'content' => $content];
    }
    public function storeRole(Request $request){
        $id = $request->input('id', false);
        if($id){
            $role = Role::find($id);
            $role->name = $request->name;
            $role->display_name = $request->display_name;
            $role->description = $request->description;
            $role->save();
            $role_perms = [];
            foreach ($role->perms()->get() as $perm) {
                $role_perms[] = $perm->id;
            }
            
            $role->perms()->detach($role_perms);
            $role->perms()->attach($request->permissions);
            
        }else{
            $role = new Role();
            $role->name = $request->name;
            $role->display_name = $request->display_name;
            $role->description = $request->description;
            $role->save();
            // $role->perms()->detach($request->permissions);
            $role->attachPermissions($request->permissions);
        }
        // if($role->save()) {
        //     return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
        // } else {
        //     return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
        // }
        return redirect('roles');

    }

    public function editRole($id){
        ob_start();
        $role = Role::find($id);
        $role_perms = [];
        foreach ($role->perms()->get() as $perm) {
            $role_perms[] = $perm->id;
        }
        $permissions = Permission::all();
        echo view('users/roles.edit' , ['role' => $role, 'permissions' => $permissions,'role_perms' => $role_perms]);
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
        //ob_start();
        return view('users/permissions.create');
        // $content = ob_get_clean();
        // return ['title' => 'Créer une permission', 'content' => $content];
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
        // if($permission->save()) {
        //     return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
        // } else {
        //     return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
        // }
        return redirect('permissions');
    }
    public function editPermission($id){
        ob_start();
        $p = Permission::find($id);
        echo view('users/permissions.edit' ,['p' => $p]);
        $content = ob_get_clean();
        return ['title' => 'Modifier une permission', 'content' => $content];
    }


}
