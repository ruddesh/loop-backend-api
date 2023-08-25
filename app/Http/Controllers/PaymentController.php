<?php

namespace App\Http\Controllers;
use App\PaymentGateways\PaymentGatewayInterface;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use App\Models\Orders;
use App\Models\ProductOrders;
use App\Models\Products;

class PaymentController extends Controller
{
    protected $paymentGateway;

    public function __construct(PaymentGatewayInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }
    
    public function payOrder(Request $request, string $id)
    {
        try {
            $message = [];
            $validator = Validator::make(['id' => $id], ['id' => 'required|numeric']);
            if($validator->fails()){
                $response['message'] = $validator->errors();;
                $response['status'] = 'failed';
                return response()->json($response, Response::HTTP_BAD_REQUEST);
            }
            
            $order = Orders::find($id);
            if(!is_null($order)){
                $customer = $order->customer()->first();
                $productOrders = $order->productOrders()->get();
            }
            $orderPayed = $order->payed ?? false;
            $orderPayed && array_push($message, 'Order already payed.');
            empty($order) && array_push($message, 'No order found.');
            empty($productOrders) && array_push($message, 'No products found for the order.');
            empty($customer) && array_push($message, 'Customer not found.');
            if(!empty($message)){
                return response()->json(['message' => $message], Response::HTTP_BAD_REQUEST);
            }
            
            $totalPrice = 0;
            foreach($productOrders as $product){
                $product = Products::select(['productname','price'])->find($product->id);            
                $totalPrice += $product->price ?? 0;
            }
            if($totalPrice == 0){
                return response()->json(["No products added for order with id $id"], Response::HTTP_OK);
            }
            $paymentData = [
                "order_id" => (int) $id,
                "customer_email" => $customer->email_address ?? null,
                "value" => $totalPrice
            ];
            $paymentProcessed = $this->processPayment($paymentData);
            if($paymentProcessed['success']){
                Orders::where('id', $id)->update(['payed' => 1]);
            }
            return response()->json($paymentProcessed, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error("payOrder failed || ". $e->getMessage());
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
       
    }

    public function processPayment($paymentData)
    {
        return $this->paymentGateway->processPayment($paymentData);
    }
}
