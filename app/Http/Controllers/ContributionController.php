<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Contribution;
use App\Models\UserYear;
use App\Models\Year;
use App\Models\User;
use Illuminate\Support\Facades\DB;
// require 'vendor/autoload.php';
use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Support\Facades\Log;


// DB::enableQueryLog();

define('USER_NAME', 'sandbox');
define('API_KEY', '2a6c5a830f34739fa3511a9570972d64b924e8b5726aeaa09b69e1e4f90e30d7');
date_default_timezone_set('Africa/Kampala');

class ContributionController extends Controller
{
    //

    
    public function store(Request $request, $id)
    {
        try {

            // print_r($request);
            //Validated
            $validate = Validator::make($request->all(), 
            [
                'amount' => 'required',
                'month' => 'required',
                'telephone' => 'required',
                'network' => 'required',
                'year' => 'required'
          
            ]);


            if($validate->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 401);
            }

            if (User::where('id', $id)->exists()){

                // check for the useryear record
                $user = User::findOrFail($id);

                if (!(Year::where('year', $request->year)->exists())){

                    $year = Year::create([
                        'year'=> $request->year
                    ]);

                    echo "In the loop";

                    UserYear::create([
                        'user_id' => $user->id,
                        'year_id' => $year->id
                    ]);
    
                   
                }

               $year = null;
                foreach(Year::all() as $yr){
                    // echo $yr;
                    if ($yr['year'] === $request->year){$year = $yr;}
                }
               

                // SMS NOTIFICATION

                $user->load('years');

              
                $useryear = UserYear::where('user_id', $user->id)
                                        ->where('year_id', $year['id'])->first();
                
                                        
                $currentDate = date('d-m-Y H:i:s');
                $notification_msg = '';
               

                $message = "Receipt of UGx:".$request->amount." for the month ";
                $message .= $request->month;
                $message .= " at ".$currentDate." through ". $request->telephone ;
               
                $notification_msg = $this->sendSms($user->contact, $message);

                
               

                $contrib = Contribution::create([
                    'amount' => $request->amount,
                    'month' => $request->month,
                    'telephone' => $request->telephone,
                    'network' => $request->network,
                    'user_year_id' => $useryear->id
                    
                ]);

                

                return response()->json([
                    'status' => true,
                    'notification' => $notification_msg['status'],
                    'msg'=> $message,
                    'message' => 'Created Successfully',
                    
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'User Year doesnot exist'
                ]);
            }

            

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function index(){
        try{

            $contributions = Contribution::with('userYear.user', 'userYear.year')->get();


            $result = [];

            foreach($contributions as $contribution){

                $cont['id'] = $contribution->id;
                $cont['names'] = $contribution->userYear->user->name;
                $cont['year'] = $contribution->userYear->year->year;

                $cont['amount'] = $contribution->amount;
                $cont['month'] = $contribution->month;
                $cont['telephone'] = $contribution->telephone;
                $cont['network'] = $contribution->network;
                $cont['paid_at'] = $contribution->created_at;

                $result[] = $cont;
            }


            return response()->json([
                'status' => 'true',
                'contributions' => $result
            ]);

        }catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function notification(){


        try {

            $date =  date('d-m-Y');
            $date = explode('-', $date);
            $response = [];

            if (!(Year::where('year', $date[2])->exists())){

                $year = Year::create([
                    'year'=> $date[2]
                ]);

                foreach(User::all() as $user){

                    UserYear::create([
                        'user_id' => $user->id,
                        'year_id' => $year->id
                    ]);
                }

            }

            $msg = "Assalam alaikum warahmatullahi wabarakaatuh\nWe are requested to make our ".date('F')." contribution  (5000/=) to help our young brothers and sisters at KYUMSA to have their activities with a little less effort. ";
            
            $month = $date[1];

            $users = User::whereDoesntHave('contributions', function ($query) use ($month) {
                $query->where('month',  $month);
            })->get();

           

            foreach ($users as $user) {
                // Send the message
                // echo $user->contact;
                $res = $this->sendSms($user->contact, $msg);
                $response[$user->name] = $res['status'];
            }


            return response()->json([
                'status' => 'true',
                'rensponse' => $response,
                'message' => $msg
            ]);


        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }

    }

    public function massMessage($msg){
        try {
            //code...

            $response = [];

            foreach(User::all() as $user){

                $res = $this->sendSms($user->contact, $msg);
                $response[$user->name] = $res['status'];

            }

            return response()->json([
                'status' => 'true',
                'rensponse' => $response,
                'message' => $msg
            ]);


        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }


    public function sendSms($number, $message){


        $AT   = new AfricasTalking("sandbox", "2a6c5a830f34739fa3511a9570972d64b924e8b5726aeaa09b69e1e4f90e30d7");

        $sms = $AT->sms();
        $from ="AKFSI";
        try {
                    // Thats it, hit send and we'll take care of the rest
            $notification_msg = $sms->send([
                'to'      => $number,
                'message' => $message,
                'from'    => $from
            ]);

                    
            return $notification_msg;  
                    // print_r($notification_msg);
        } catch (Exception $e) {
            echo "Error: siisisis";
        }
    }
}
