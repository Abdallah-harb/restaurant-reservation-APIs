<?php

namespace App\Http\Controllers\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Helper\ResponseHelper;
use App\Http\Resources\Order\OrderDetailsResource;
use App\Http\Resources\Order\OrderResource;
use App\Models\Meal;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $allOrder = Order::with(['orderdDetails'])->get();
            if(count($allOrder) < 1){
                return  ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'No Order Yet');
            }
            return ResponseHelper::sendResponseSuccess(OrderResource::collection($allOrder));

        }catch (\Exception $ex){
            DB::rollBack();
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $neworder = Order::create([
                "table_id" => $request->table_id,
                "reservation_id" => $request->reservation_id,
                "customer_id" => $request->customer_id,
                "waiter_id" => auth('api')->user()->id,
                "date" => Carbon::now()
            ]);
            $total = 0;
            $all_order = [];
            foreach ($request->orders as $order){
                $meal = Meal::find($order['meal_id']);
                $price = $meal->price;
                    // check if order has discount
                if ($meal->discount > 0) {
                    $price = $price - ($price * $meal->discount / 100);
                }
                $Orderdetails = OrderDetail::create([
                    "order_id" => $neworder->id,
                    "meal_id" => $order['meal_id'],
                    'quantity' => $order['quantity'],
                    "amount_to_pay" =>  $price * $order['quantity'],
                ]);
                $total += $price * $order['quantity'];
                $all_Orderdetails[]=  $Orderdetails;
            }
            $neworder->total = $total;
            $neworder->paid = $request->paid;
            $neworder->save();
            DB::commit();

            return  ResponseHelper::sendResponseSuccess([
                "order" => new OrderResource($neworder),
                "order_details" => OrderDetailsResource::collection($all_Orderdetails)
            ]);
        }catch (\Exception $ex){
            DB::rollBack();
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $order = Order::with(['orderdDetails'])->whereId($id)->get();
            if(!$order){
                return  ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'No Order Yet');
            }
            return ResponseHelper::sendResponseSuccess(OrderResource::collection($order));

        }catch (\Exception $ex){
            DB::rollBack();
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $neworder = Order::whereId($id)->first();
            if(!$neworder){
                return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,"there are error on data");
            }
            $neworder->update([
                "table_id" => $request->table_id,
                "reservation_id" => $request->reservation_id,
                "customer_id" => $request->customer_id,
                "waiter_id" => auth('api')->user()->id,
                "date" => Carbon::now()
            ]);
            $total = 0;
            $all_order = [];
            OrderDetail::whereOrderId($id)->delete();
            foreach ($request->orders as $order){
                $meal = Meal::find($order['meal_id']);
                $price = $meal->price;

                if ($meal->discount > 0) {
                    $price = $price - ($price * $meal->discount / 100);
                }
                $Orderdetails = OrderDetail::create([
                    "order_id" => $neworder->id,
                    "meal_id" => $order['meal_id'],
                    'quantity' => $order['quantity'],
                    "amount_to_pay" =>  $price * $order['quantity'],
                ]);
                $total += $price * $order['quantity'];
                $all_Orderdetails[]=  $Orderdetails;
            }
            $neworder->total = $total;
            $neworder->paid = $request->paid;
            $neworder->save();
            DB::commit();

            return  ResponseHelper::sendResponseSuccess([
                "order" => new OrderResource($neworder),
                "order_details" => OrderDetailsResource::collection($all_Orderdetails)
            ]);
        }catch (\Exception $ex){
            DB::rollBack();
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $order = Order::whereId($id)->first();
            if(!$order){
                return  ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'No Order Yet');
            }
             $order->delete();
            return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,"Order delete Successfully");

        }catch (\Exception $ex){
            DB::rollBack();
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }
}
