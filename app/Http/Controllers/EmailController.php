<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Email;
use App\Http\Requests;

class EmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $emails = Email::paginate(10);
        return view('emails.index', compact('emails'));
    }

    public function create()
    {
        ob_start();
        echo view('emails.form');
        $content = ob_get_clean();
        return ['title' => 'Ajouter un email', 'content' => $content];
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sender'    => 'required',
            'subject'   => 'required',
            'message'   => "required",
        ]);
        if ($validator->fails()) {
            return ["status" => "danger", "message" => $validator->errors()->all()];
        }

        if($request->id == null ){
            $email = new Email();
        }else{
            $email =  Email::find($request->id);
        }
        $email->sender = $request->sender;
        $email->subject = $request->subject;
        $email->message = $request->message;
        $email->save();
        if($email->save()) {
            return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
        } else {
            return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        ob_start();
        $email = Email::find($id);
        echo view('emails.form', compact('email'));
        $content = ob_get_clean();
        return ['title' => 'Modifier un email', 'content' => $content];
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
        //
    }

}
