<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Orders;
use App\Models\ProductOrders;
use Validator;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Orders::get()->toArray();
        $res = !empty($orders) ? $orders : ['message' => 'No orders found'];
        return response()->json($res, Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $postData = $request->all();
        $validator = Validator::make($postData, ['customer_id' => 'required', 'payed' => 'required']);
        $response['status'] = 'failed';
        if ($validator->fails()) {
            $response['message'] = $validator->errors();
            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }
        $order = Orders::create($postData);
        $orderId = $order->id ?? null;
        $resCode = Response::HTTP_BAD_REQUEST;
        if(isset($orderId)){
            $response['status'] = 'success';
            $response['message'] = 'order created';
            $resCode = Response::HTTP_OK;
        }
        return response()->json($response, $resCode);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $res = ['status' => 'failed'];
        $resCode = Response::HTTP_BAD_REQUEST;
        $postData = $request->all();
        $req = array_merge($postData, ['id' => $id]);
        $validator = Validator::make($req, [
            'id' => 'required|numeric',
            'customer_id' => 'numeric',
            'payed' => 'numeric',
        ]);
        if($validator->fails()){
            $response['message'] = $validator->errors();;
            $response['status'] = 'failed';
            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }
        $order = Orders::select(['id'])->find($id);
        if(!is_null($order)){
            $updated = Orders::where('id', $id)->update($postData);
            $res = ['status' => 'success', 'order_id' => $id];
            $resCode = Response::HTTP_OK;
        }
        return response()->json($res, $resCode);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $validator = Validator::make(['id' => $id], ['id' => 'required|numeric']);
        if($validator->fails()){
            $response['message'] = $validator->errors();
            $response['status'] = 'failed';
            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }
        $deleted = Orders::destroy($id);
        $res = ['status' => 'failed'];
        $resCode = Response::HTTP_BAD_REQUEST;
        if($deleted){
            $res = ['status' => 'success', 'order_id' => $id];
            $resCode = Response::HTTP_OK;
        }
        return response()->json($res, $resCode);
    }

    public function addProductToOrder(Request $request, string $id)
    {
        $postData = $request->all();
        $req = array_merge($postData, ['id' => $id]);
        $validator = Validator::make($req, ['product_id' => 'required|numeric', 'id' => 'required|numeric']);
        if($validator->fails()){
            $response['message'] = $validator->errors();;
            $response['status'] = 'failed';
            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }
        $productId = $postData['product_id'] ?? null;
        $order = Orders::select(['id', 'payed'])->find($id);
        $orderArr = !is_null($order) ? $order->toArray() : [];
        
        if(!empty($orderArr)){
            if($orderArr['payed']){
                return response()->json(["Order with id $id is already paid."], Response::HTTP_OK);
            }
            $productOrder = ProductOrders::insert([
                'order_id' => $order['id'],
                'product_id' => $productId,
            ]);
            return response()->json(["status" => "success"], Response::HTTP_OK);
        } else {
            return response()->json(["Order with id $id does not exist."], Response::HTTP_BAD_REQUEST);
        }
    }
}
