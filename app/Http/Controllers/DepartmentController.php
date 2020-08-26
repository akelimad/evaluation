<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use Auth;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::getAll()->paginate(9);
        return view('departments.index', [
            'results' => $departments,
            'active' => true,
            'active' => 'dep',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function form(Request $request)
    {
        if ($request->method() == 'POST') {
            return $this->store($request);
        }
        $id = $request->id;
        ob_start();
        if(isset($id) && is_numeric($id)) {
            $department = Department::findOrFail($id);
            $title = "Modifier le département";
        } else {
            $department = new Department();
            $title = "Ajouter un département";
        }
        echo view('departments.form', compact('department'));
        $content = ob_get_clean();
        return ['title' => $title, 'content' => $content];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request->id;
        if($id){
            $department = Department::findOrFail($id);
            $department->title = $request->departments[0];
            $department->save();
        }else{
            foreach ($request->departments as $d) {
                $department = new Department();
                $department->title = $d;
                $department->user_id = Auth::user()->id;
                $department->save();
            }
        }
        if($department->save()) {
            return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
        } else {
            return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $id = $request->id;
        $department = Department::findOrFail($id);
        $department->delete();
        return ["status" => "success", "message" => "Le département a été supprimé avec succès !"];
    }

}
