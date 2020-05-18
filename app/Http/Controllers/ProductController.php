<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Payment;
use App\Purchase;
use App\PurchaseDetail;
use Validator;
//use Illuminate\Support\Facades\Input;

//use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\DB;
//use Laravel\Http\Requests;

class ProductController extends Controller
{
    public function getDetailByOrderId($id, Request $request){
        try{

            $skip= $request->get('skip');
            $take= $request->get('take');
            $search= $request->get('search');
            $total = 0;
            $data = null;

            $data=DB::table('purchase_detail as pd')
                ->join('purchase as pu','pd.purchaseId','=','pu.purchaseId')
                ->join('pizza as p','pd.pizzaId','=','p.pizzaId')
                ->join('size_pizza as sp','pd.sizePizzaId','=','sp.sizePizzaId')
                ->join('size as s','sp.sizeId','=','s.sizeId')
                ->select('pu.purchaseId',
                    'pu.clientId',
                    'pd.quantity',
                    'p.name as pizza',
                    'p.image','p.ingredients',
                    's.name',
                    'sp.price as purchasePrice',
                    'sp.euroPrice as amountEuro')
                ->where('pu.purchaseId','=',$id);

            $total = $data->count();

//            if($search !=""  && $search !=null && $search !="null"){
//
//                $data = $data->where('p.name','LIKE','%'.$search.'%');
//                $total = $data->count();
//
//            }
            $data = $data->skip($skip)->take($take)->get();

            $response = [
                'success' => "OK",
                'total' =>  $total,
                'data' => $data,
                'message' => 'information retrieved successfully.'
            ];

            return response()->json($response, 200);

        }catch(\Exception $e){
            return response()->json(['success'=>'ERROR','msg'=>$e->getMessage()]);
        }
    }

    public function getOrdersByClient($id, Request $request){

        try{
            $skip= $request->get('skip');
            $take= $request->get('take');
            $search= $request->get('search');
            $total = 0;
            $data = null;

            $data = Purchase::where('clientId','=',$id);

            if (is_null($data)) {
                $response = [
                    'success' => 'ERROR',
                    'data' => 'Empty',
                    'message' => 'rows not found.'
                ];
                return response()->json($response, 404);
            }

            $total = $data->count();

            $data = $data->skip($skip)->take($take)->get();

            $response = [
                'success' => 'OK',
                'total' => $total,
                'data' => $data,
                'message' => 'row retrieved successfully.'
            ];

            return response()->json($response, 200);
        }catch(\Exception $e){

            return response()->json(['success'=>'ERROR','msg'=>$e->getMessage()]);
        }
    }

    public function purchasePizza(Request $request){
        
        try{
            
            DB::beginTransaction();

            $paymentObject = $request->get('payment');
            $purchaseObject = $request->get('purchase');
            $purchaseDetailObject = $request->get('purchaseDetail');
            
            $payment = Payment::create($paymentObject);
            
            if($payment){
                
                 $paymentId = $payment->paymentId;
                 
                 $purchaseObject["paymentId"] = $paymentId;
                 $purchase = Purchase::create($purchaseObject);
                 
                 $cont=0;
                 while($cont <count($purchaseDetailObject)){
                    $obj = $purchaseDetailObject[$cont];
                    
                    $obj['purchaseId'] = $purchase->purchaseId;
                    PurchaseDetail::create($obj);
                    $cont++;
                }
            }
            
            
             
            DB::commit();
             
            return response()->json(['purchaseDetail'=>$purchaseDetailObject,'purchase'=>$purchase,'payment'=>$payment,'success'=>'OK','msg'=>'successfull']);
             
         }catch(\Exception $e){
            DB::rollback();
            return response()->json(['success'=>'ERROR','msg'=>$e->getMessage()]);
        }
    }

    public function getSizesByPizzaId($id){
        try{
          $data = DB::table('size_pizza as sp')
          ->join('size as s','sp.sizeId','=','s.sizeId')
          ->join('pizza as p','sp.pizzaId','=','p.pizzaId')
          ->select('sp.sizePizzaId','s.name','sp.price','sp.deliveryCost','sp.euroPrice','p.ingredients')

          ->where('sp.pizzaId','=', $id)->get();
          

        $response = [
            'success' => "OK",
            'total' => 22,
            'data' => $data,
            'message' => 'information retrieved successfully.'
        ];

        return response()->json($response, 200);
        }catch (\Exception $e){
                return response()->json(['success'=>'ERROR','msg'=>$e->getMessage()]);
            
        }
          
    }
    
    public function getIngredientsByPizzaId($id){
        try{
          $data = DB::table('pizza_ingredient as pi')
          ->join('ingredient as i','pi.ingredientId','=','i.ingredientId')
          ->select('pi.pizza_ingredientId as value','i.name as label')
          ->where('pi.pizzaId','=', $id)->get();
          

        $response = [
            'success' => "OK",
            'total' => 22,
            'data' => $data,
            'message' => 'information retrieved successfully.'
        ];

        return response()->json($response, 200);
        }catch (\Exception $e){
                return response()->json(['success'=>'ERROR','msg'=>$e->getMessage()]);
            
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
            'success' => 'OK',
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
