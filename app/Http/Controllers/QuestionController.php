<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\User;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth',['except'=>array('getSlambook', 'navigateSlambook')]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get all the questions data
        $questions = Question::get();
        
        return view('questions.view')->with('questions', $questions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('questions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate the fields
        $this->validate($request, [
            'question' => 'required|unique:questions,description'
        ]);

        //Store the question into the database
        $question = new Question();
        $question->description = $request->input('question');
        $question->type = $request->input('type');

        if(!empty($request->input('visible'))) {
            $question->visible = 1;
        } else {
            $question->visible = 0;
        }
        $question->isSystem = 1;
        
        $question->save();

        return redirect('questions')->with('success','Question added successfully');
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
        $questions = Question::get();

        foreach($questions as $question) {

            $required = $request->input('required_'.$question->id);
            $visible = $request->input('visible_'.$question->id);
            $update = Question::where('id', '=', $question->id)->update(array('required'=>$required, 'visible'=>$visible));
        }

        $questions = Question::get();

        return redirect('questions')->with(['questions'=>$questions,'success'=>'Questions updated successfully!']);
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
