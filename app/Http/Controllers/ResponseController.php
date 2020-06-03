<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use Illuminate\Http\Request;
use App\Models\Question;
use App\User;
use App\Models\ResponseMaster;
use App\Models\ResponseDetail;

class ResponseController extends Controller
{

    //Set default limit for slambook questions
    protected $limit = 5;


    /**
     * Get the list of responses
     * 
     * @return view of responses
     */
    public function index()
    {
        $responses = ResponseMaster::where('user_id', '=', Auth()->user()->id)->get();
        return view('home')->with('responses', $responses);
    }



    public function show($id) 
    {
        
        $response = ResponseMaster::where('id', '=', $id)->with('details.question')->get();
        return view('responses.show')->with('response', $response);
    }


    /**
     * Get slambook welcome page to get the friends basic info
     * 
     * @return Slambook's welcome view
     */
    public function getWelcomePage(Request $request, $userId)
    {
        //Decrypt the userID passed in the url 
        try {
            $userId = Crypt::decrypt($userId);    
        } catch (DecryptException $th) {
            return redirect('error')->with('success', 'Link is not valid');
        }
        
        //Check current session has userId and it is same as passed one
        if(empty($request->session()->has('userId')) || $request->session()->get('userId', 'default') != $userId) {
            
            //Get user information
            $user = User::where('id', '=', $userId)->first();
            
            //If no user then give error 
            if (empty($user)) {
                return redirect('error')->with('success', 'Invalid link provided');
            }

            //Set new userId and user name
            $request->session()->put('userId', $user->id);
            $request->session()->put('userName', $user->name);
        } 

        return view('responses.welcome');
    }
    /**
     * Show Slambook questions
     * 
     *
     * @return Will return the slambook pages to fill 
     */
    public function getSlambook(Request $request, $page=1)
    {
        //Get user information
        if ($page == 1) {
            
            $request->session()->put('currentPage', '1');
        } 

        $currentPage = $request->session()->get('currentPage', 'default');

        $offset = ($currentPage-1) * $this->limit;

        //If current page is first only then get total pages
        if ($currentPage == 1) {
            $totalRecords = Question::get()->count();
            $pages = (int)ceil($totalRecords/$this->limit);
            $request->session()->put('totalPages', $pages);
        }

        //If it is final page then save all the data
        if ($currentPage > $request->session()->get('totalPages', 'default')) {

            //First save data in master table
            $responseMaster = new ResponseMaster();
            $responseMaster->name = $request->session()->get('name', 'default');
            $responseMaster->email = $request->session()->get('email', 'default');
            $responseMaster->user_id = $request->session()->get('userId', 'default');
            $responseMaster->save();

            //Get the id of last saved entry
            $masterId = $responseMaster->id;

            //Save the data in the details table
            $questionsToSave = Question::where('visible', '=', '1')->orderBy('id', 'asc')->get();
            
            foreach($questionsToSave as $question) {
            
                $responseDetail = new ResponseDetail();
                $responseDetail->response_master_id = $masterId;
                $responseDetail->question_id = $question->id;
                $responseDetail->answer = $request->session()->get('q_'.$question->id, 'default');
                $responseDetail->save();
            }
            
            return redirect('responses/final');
        } else {
            $questions = Question::orderBy('id', 'asc')->offset($offset)->limit($this->limit)->get();
        }
        
        return view('responses.slambook')->with('questions', $questions);
    }

    public function navigateSlambook(Request $request, $action)
    {

        //current page 
        $currentPage = $request->session()->get('currentPage', 'default');
        $offset = ($currentPage-1) * $this->limit;
        
        //Get questions to validate from the database
        $questions = Question::orderBy('id', 'asc')->offset($offset)->limit($this->limit)->get();

        $rules = [];
        $customMessages = [];
        foreach($questions as $question) {
            
            switch ($question->type) {
                case 'text':
                    $rules['q_'.$question->id] = "required";
                    $customMessages['q_'.$question->id.'.required'] = "Please enter ".$question->description;
                    break;
                
                case 'longtext':
                    $rules['q_'.$question->id] = "required";
                    $customMessages['q_'.$question->id.'.required'] = "Please enter ".$question->description;
                    break;
                
                case 'date':
                    $rules['q_'.$question->id] = "required";
                    $customMessages['q_'.$question->id.'.required'] = "Please select date for ".$question->description;
                    break;

                case 'email':
                    $rules['q_'.$question->id] = "required|email";
                    $customMessages['q_'.$question->id.'.required'] = "Please enter ".$question->description;
                    $customMessages['q_'.$question->id.'.email'] = "Please enter valid E-mail";
                    break;
                
                case 'file':
                    $rules['q_'.$question->id] = "required";
                    $customMessages['q_'.$question->id.'.required'] = "Please choose ".$question->description;
                    break;

                default:
                    $rules['q_'.$question->id] = "required";
                    $customMessages['q_'.$question->id.'.required'] = "Please enter ".$question->description;
                    break;
            }
        }

        
        //validate the data
        $this->validate($request, $rules, $customMessages);

        //Store the question responses in the session data
        foreach($questions as $question) {
            $request->session()->put('q_'.$question->id, $request->input('q_'.$question->id));
        }

        switch ($action) {
            case 'next':
                $request->session()->put('currentPage', $request->session()->get('currentPage', 'default') + 1);
                break;
            
            case 'prev':
                $request->session()->put('currentPage', $request->session()->get('currentPage', 'default') - 1);
                break;
        }
        
        return redirect("slambook/{$request->session()->get('currentPage', 'default')}");

    }

    /**
     * To store the friends unique information
     */
    public function store(Request $request)
    {
        
        if(empty($request->session()->get('userId', 'default'))) {
            return redirect('error')->with('success', 'Something went wrong! Please contact link provider');
        }
        
        //validate the data
        $this->validate($request, 
            [
                'name'=>'required',
                'email' => 'required|email|unique:response_masters,email'            ]
        );
        
        
        $request->session()->put('name', $request->input('name'));
        $request->session()->put('email', $request->input('email'));
        
        return redirect('slambook');
    }

}
