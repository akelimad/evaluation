<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Answer;
use App\Token;
use App\Entretien_user;
use Auth;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->mentor_id);
        foreach ($request->answers as $key => $answers) {
            foreach ($answers as $answer) {
                $a = new Answer();
                $a->answer      = $answer;
                $a->question_id = $key;
                $a->user_id = $request->user_id ;
                if( $request->mentor_id != "NULL" ){
                    $a->mentor_id = $request->mentor_id ;
                }
                $a->entretien_id = $request->entretien_id;
                $a->save();
            }
        }
        // $token = Token::where('entretien_id', $request->entretien_id)->where('user_id', $request->user_id)->first();
        // if($token){
        //     $token->mentor_id = $request->mentor_id;
        //     $token->save();
        // }else{
        //     $token = new Token();
        //     $token->entretien_id = $request->entretien_id;
        //     $token->user_id = $request->user_id;
        //     $token->mentor_id = $request->mentor_id;
        //     $token->save();
        // }
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
