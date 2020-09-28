<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Action;
use App\Email;
use App\Http\Requests;
use Symfony\Component\CssSelector\XPath\TranslatorInterface;

class ActionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
    }

    public function create()
    {
        ob_start();
        echo view('emails.actions.form');
        $content = ob_get_clean();
        return ['title' => __("Ajouter une action"), 'content' => $content];
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slug'    => 'required',
            'name'   => 'required',
        ]);
        if ($validator->fails()) {
            return ["status" => "danger", "message" => $validator->errors()->all()];
        }

        if($request->id == null ){
            $action = new Action();
        }else{
            $action =  Action::findOrFail($request->id);
        }
        $action->slug = $request->slug;
        $action->name = $request->name;
        $action->type = $request->type;
        $action->save();
        if($action->save()) {
            return ["status" => "success", "message" => __("Les informations ont été sauvegardées avec succès.")];
        } else {
            return ["status" => "warning", "message" => __("Une erreur est survenue, réessayez plus tard.")];
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        ob_start();
        $action = Action::findOrFail($id);
        echo view('emails.actions.form', compact('action'));
        $content = ob_get_clean();
        return ['title' => 'Modifier une action', 'content' => $content];
    }

    public function attachEmailAtion(Request $request, $actionId)
    {
        $action = Action::findOrFail($actionId);
        $action->emails()->sync([$request->email_id]);
        return redirect('config/emails')->with("attach_emailAction", "");
    }

    public function destroy($id)
    {
        //
    }
}
