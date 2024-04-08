<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Year;
class YearController extends Controller
{
    //

    public function store(Request $request)
    {
        try {

            // print_r($request);
            //Validated
            $validate = Validator::make($request->all(), 
            [
                'year' => 'required',
                'status' => 'required'
                
            ]);

            if($validate->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 401);
            }

            $year = Year::create([
                'year' => $request->year,
                'active' => $request->status
                
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Year Created Successfully',
                
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id){
        try{

            $validate = Validator::make($request->all(), 
            [
                'year' => 'required',
                'status' => 'required'
                
            ]);

            if($validate->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (Year::where('id', $id)->exists()){
                $year = Year::find($id);

                $year->year = $request->year;
                $year->status = $request->status;
            }


        }catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function index(){
        try{

            $years = Year::all();

            return response()->json([
                'status' => true,
                'years' => $years
            ]);

        }catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
