<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Contribution;
use App\Models\UserYear;

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
                'user_year_id' => 'required'

                
            ]);

            if($validate->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 401);
            }

            if (UserYear::where('id', $id)->exists()){

                $contrib = Contribution::create([
                    'amount' => $request->amount,
                    'month' => $request->month,
                    'telephone' => $request->telephone,
                    'network' => $request->network,
                    'user_year_id' => $request->user_year_id
                    
                ]);

               
    
                return response()->json([
                    'status' => true,
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
}
