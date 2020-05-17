<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Validator;
use App\SizePizzat;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

use Laravel\Http\Requests;

class SizePizzaController extends Controller
{
    public function getSizesByPizzaId($id){
        try{
          $data = DB::table('size_pizza')->where('pizzaId','=', $id)->get();

        $response = [
            'success' => "OK",
            'total' => 22,
            'data' => $data,
            'message' => 'information retrieved successfully.'
        ];

        return response()->json($response, 200);
        }catch (){
            
            
        }
          
    }
    public function index(Request $request)
    {
        
        $skip= $request->get('skip');
        $take= $request->get('take');
        $search= $request->get('search');
        $total = 0;
        $data = null;
        
        $data = DB::table('pizza');
       
        $total = $data->count();
        
        if($search !=""  && $search !=null && $search !="null"){
             
            $data = DB::table('pizza')->where('name','LIKE','%'.$search.'%')->OrWhere('description','LIKE','%'.$search.'%');
                
            $total = $data->count();
            
        }
        
         if($take !=""  && $take !=null && $take !="null" && $skip !=""  && $skip !=null && $skip !="null"){
        
            $data = $data->skip($skip)->take($take)->get();
         }else{
             $data = $data->get();
             
         }


        $response = [
            'success' => "OK",
            'total' =>  $total,
            'data' => $data,
            'message' => 'information retrieved successfully.'
        ];

        return response()->json($response, 200);
    }
    
    public function store(Request $request)
    {
        
        try{
            $input = $request->all();

            $data = Product::create($input);
            $data = $data->toArray();
    
            $response = [
                'success' => "OK",
                'data' => $data,
                'message' => 'stored successfully.'
            ];
    
            return response()->json($response, 200);
        }catch(\Exception $e){
             return response()->json(['success'=>'ERROR','msg'=>$e->getMessage()]); 
        }
    }
     
    public function update(Request $request, $id)
    {
        try{
            
            if (Product::where('id', $id)->exists()) {
                $data = Product::find($id);
                $data->update($request->all());
        
                return response()->json([
                    'success' => "OK",
                    "message" => "registro updated successfully"
                ], 200);
                } else {
                return response()->json([
                    'success' => "ERROR",
                    "message" => "user not found"
                ], 404);
                
            }
         
        }catch(\Exception $e){
            return response()->json(['success'=>'ERROR','msg'=>$e->getMessage()]);
        }
    }
   
    
    public function show($id)
    {
        $data = Product::find($id);
        $data = $data->toArray();

        if (is_null($data)) {
            $response = [
                'success' => 'OK',
                'data' => 'Empty',
                'message' => 'row not found.'
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => 'ERROR',
            'data' => $data,
            'message' => 'row retrieved successfully.'
        ];

        return response()->json($response, 200);
    }
    
    public function destroy($id)
    {
        try{
        $data = Product::findOrFail($id);
        
        $data->delete();
        $data = $data->toArray();

        $response = [
            'success' => 'OK',
            'data' => $data,
            'message' => 'row deleted successfully.'
        ];

        return response()->json($response, 200);
        
        }catch(\Exception $e){
            return response()->json(['success'=>'ERROR','msg'=>$e->getMessage()]);
        }
    }

}
