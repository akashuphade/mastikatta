<?php

namespace App\Http\Controllers;

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

    /**
     * Get slambook welcome page to get the friends basic info
     * 
     * @return Slambook's welcome view
     */
    public function getWelcomePage(Request $request, $userId)
    {
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
        
        $questions = Question::orderBy('id', 'asc')->offset($offset)->limit($this->limit)->get();
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

        //Save questions data
        foreach($questions as $question) {
            //Save the page data 
            $response = new ResponseDetail();
            $response->response_master_id = $request->session()->get('master_id', 'default');
            $response->question_id = $question->id;

            //If image of attached then save it to storage
            if($question->type =='file') {
                //Get the original file name
                $filenameWithExtension = $request->file('q_'.$question->id)->getClientOriginalName();

                //Extract only file name 
                $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);

                //Extract the extension
                $extension = pathinfo($filenameWithExtension, PATHINFO_EXTENSION);

                $fileNameToStore = $filename . '_' . time() . "." .$extension;

                //Save the image
                $request->file('q_'.$question->id)->storeAs('public/profile_photos', $fileNameToStore);
                
                $response->answer = $fileNameToStore;
            } else {
                $response->answer = $request->input('q_'.$question->id);
            }
            $response->save();
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
        
        $response = new ResponseMaster();
        $response->name = $request->input('name');
        $response->email = $request->input('email');
        $response->user_id = $request->session()->get('userId', 'default');

        $response->save();
        $request->session()->put('master_id', $response->id);

        return redirect('slambook');
    }
}
