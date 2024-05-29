<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Contribution;
use App\Models\UserYear;
use App\Models\sms_auth;
use App\Models\Year;
use App\Models\User;
use Illuminate\Support\Facades\DB;
// require 'vendor/autoload.php';
use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Support\Facades\Log;
use App\Http\MobileMoney\MtnMomo;



// DB::enableQueryLog();


date_default_timezone_set('Africa/Kampala');

class ContributionController extends Controller
{
    //
   
    private $apiKey = 'b0542e7c41ca4ec08a06f30dc46fd6e1';
    private $subscriptionKey = '9d92781dace14e7cbd587193e7f8e6fd';
    private $userId = '4cc6b44c-ca5b-4dd8-ade8-858f61b89cf3';
    private $accessToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSMjU2In0.eyJjbGllbnRJZCI6IjRjYzZiNDRjLWNhNWItNGRkOC1hZGU4LTg1OGY2MWI4OWNmMyIsImV4cGlyZXMiOiIyMDI0LTA1LTA5VDEzOjE2OjUyLjY5MSIsInNlc3Npb25JZCI6ImFmMjBlNzk1LTE3NGItNDUyMC04ZTBkLTMwZDJjOTMyYTBmYiJ9.W7HlF2NT_quN8M6U_wBZXCbCXbnGxhlxtPiikbcQYfCrErBqMZr3ZGs4LrLMnzUlOjvMajhjzvgCws4yE4fKDVEGyfMElC5uclgPBXfAgglI1NhH1eMqd5Cobnk9MDGPs4uJrsQPLCA-79sI2yZY-ktRzkTS4EWc8b_HrSUnB2dCGFWE-jX4cjbaWMz4SalK1lcz225Zh3A1hRX-f7t_jiJDEjPKynjAxHtS8-cc0uunzjg5IVNiKkapGB7WEdNs-PTKhlkjYUZKUOEFTrQfr9n-KWY6HBZwUyykUTaZ-6TBmRDO0GeBHyOiHb_MTPNPgZLDXTxPGW4C8uN4m2MZFQ';

    
    public function store(Request $request, $id)
    {
        $month = [
            '1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April',
            '5' => 'May', '6' => 'June', '7' => 'July',  '8' => 'August', '9' => 'September',
            '10' => 'October', '11' => 'November', '12'=> 'December'

        ];
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

                // echo $year;
              
                $useryear = UserYear::where('user_id', $user->id)
                                        ->where('year_id', $year['id'])->first();

                
                if (!$useryear){

                    $useryear = UserYear::create([
                        'user_id' => $user->id,
                        'year_id' => $year->id
                    ]);
                }
                
                                        
                $currentDate = date('d-m-Y H:i:s');
                $notification_msg = '';
               

                // $message = "Receipt of UGx:".$request->amount." for the month ";
                // $message .= $request->month;
                // $message .= " at ".$currentDate." through ". $request->telephone ;

                $message = "AKFSI: Reciept of UGx 5000 for the month April  has been received successfully. May Allah suffice for you.";
               
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
                    'notification' => $notification_msg,
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
            $msg = "AKFSI: Assalam alaikum. 
            We are requested to make our" .date('f')."5000/= contribution to help in running  KYUMSA  activities with  less effort.  Our contributions can be sent on the following numbers: 
            SSENTAMU ABEL  
            0741659861  
            0780269754 
            May Allah accept from us all.";
            // $msg = "Assalam alaikum, We are requested to make our ".date('F')." contribution  (5000/=) to support  KYUMSA  activities. Payments can be made on 0741659861, 0780269754 (SSENTAMU ABEL)";
            
            $month = $date[1];

            $users = User::whereDoesntHave('contributions', function ($query) use ($month) {
                $query->where('month',  $month);
            })->get();

        

            $receipients = "";


            foreach ($users as $user) {
        
                $receipients .= $user->contact .',';
            }

            $numbers = rtrim($receipients, ",");


         

            $response = $this->sendSms($numbers, $msg);
            


            return response()->json([
                'status' => 'true',
                'response' => $response,
                
            ]);


        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }

    }


    public function massMessage(Request $request){
        try {
            //code...

            $validate = Validator::make($request->all(), 
            [
                'reason' => 'required',
                'message' => 'required',                
          
            ]);


            if($validate->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 401);
            }

            $response = [];

            foreach(User::all() as $user){

                $res = $this->sendSms($user->contact, $request->message);
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

        $auth = sms_auth::findorFail(1);

        

        $AT   = new AfricasTalking($auth->user_name, $auth->api_key);

        $sms = $AT->sms();
        $from ="AKFSI";
        try {
                    // Thats it, hit send and we'll take care of the rest
            $notification_msg = $sms->send([
                'to'      => $number,
                'message' => $message
            ]);

                    
            return $notification_msg;  
                    // print_r($notification_msg);
        } catch (Exception $e) {
            echo "Error: siisisis";
        }
    }


    public function testMomo(){

        try{

            $callbackUrl = 'http://hkdk.events/a4jlgobsbs0jiu';
            $domain = 'hkdk.events';
            $mtnMomo = new MtnMomo($this->subscriptionKey, $callbackUrl, $domain);

         
            // $url = 'https://sandbox.momodeveloper.mtn.com/v1_0/apiuser';
            // $createUser = $mtnMomo->apiUser($url, $this->userId);

            // $response = null;

            // if ($createUser == 201){

            //     $response  = $mtnMomo->apiToken($url, $this->userId);
            // }
            
            $response = $mtnMomo->accessToken($this->userId, $this->apiKey);

            // if ($response->status == 200){

            //     $accessToken = $response->body()->
            // }

            $requestBody = [
                "amount" => "4000",
                "currency" => "EUR",
                "externalId" => "213456999",
                "payer" => [
                    "partyIdType" => "MSISDN",
                    "partyId" => "256780269754"
                ],
                "payerMessage" => "Message",
                "payeeNote" => "Message"
            ];

            $response = $mtnMomo->requestToPay($this->accessToken, $requestBody);

            return response()->json([
                'status' => 'true',
                'rensponse' => $response
                
            ]);

        }catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }

    }
}
