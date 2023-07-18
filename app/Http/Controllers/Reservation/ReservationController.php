<?php

namespace App\Http\Controllers\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Helper\ResponseHelper;
use App\Http\Requests\Reservation\ReserveRequest;
use App\Http\Resources\Reservation\customerResource;
use App\Http\Resources\Reservation\ReserveResource;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $allCustomer  = Customer::get();
            $allReservation = Reservation::get();
            if(count($allReservation) <1){
                return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'No Reservation Yet');
            }
            return ResponseHelper::sendResponseSuccess([
                "customers" => customerResource::collection($allCustomer),
                "reservations" => ReserveResource::collection($allReservation)
            ]);

        }catch (\Exception $ex){
            DB::rollBack();
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReserveRequest $request)
    {
        DB::beginTransaction();
        try {
                 //check avalibilaty
            $startTime = $request->from_time;
            $endTime = $request->to_time;
            $reservations = Reservation::where('table_id', $request->table_id)
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where(function ($query) use ($startTime, $endTime) {
                        $query->where('from_time', '>=', $startTime)
                            ->where('from_time', '<', $endTime);
                    })->orWhere(function ($query) use ($startTime, $endTime) {
                        $query->where('from_time', '<=', $startTime)
                            ->where('to_time', '>', $startTime);
                    });
                })->get();
                // check number of guest
            $table = Table::find($request->table_id);
            $capacity =  $table->capacity;
            if($request->guestNumber > $capacity ){
                return  ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,"choose another table, Number of guest is  greater Than For the capacity of this table ");
            }

            if(count($reservations) > 0){
                // add in waiting
                $customer = Customer::create([
                    "name" => $request->name,
                    "phone" => $request->phone,
                ]);
                $reserve = Reservation::create([
                    "table_id" => $request->table_id,
                    "customer_id" => $customer->id,
                    "waiting" =>true,
                    "from_time" => $request->from_time,
                    "to_time" => $request->to_time,
                ]);
                return  ResponseHelper::sendResponseSuccess([
                    "customer" => new customerResource($customer),
                    "reservation" => new ReserveResource($reserve)
                ],Response::HTTP_OK,"Sorry This Table Is InAvailable in current Time, we add you in waiting ");
            }

            // add new reservation
            $customer = Customer::create([
               "name" => $request->name,
               "phone" => $request->phone,
            ]);
            $reserve = Reservation::create([
                "table_id" => $request->table_id,
                "customer_id" => $customer->id,
                "from_time" => $request->from_time,
                "to_time" => $request->to_time,
            ]);
            DB::commit();
            return ResponseHelper::sendResponseSuccess([
                "customer" => new customerResource($customer),
                "reservation" => new ReserveResource($reserve)
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
            $customer  = Customer::whereId($id)->get();
            $reservation = Reservation::whereCustomerId($id)->get();
            if(count($reservation) < 1){
                return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'There are error on data');
            }
            return ResponseHelper::sendResponseSuccess([
                "customer" => customerResource::collection($customer),
                "reservation" => ReserveResource::collection($reservation)
            ]);

        }catch (\Exception $ex){
            DB::rollBack();
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReserveRequest $request, string $id)
    {
        try {
            $customer  = Customer::whereId($id)->first();
            $reservation = Reservation::whereCustomerId($id)->first();
            if(!$reservation){
                return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'There are error on data');
            }

            $customer->update([
                "name" => $request->name,
                "phone" => $request->phone,
            ]);
            $reservation->update([
                "table_id" => $request->table_id,
                "from_time" => $request->from_time,
                "to_time" => $request->to_time,
            ]);
            return ResponseHelper::sendResponseSuccess([
                "customer" => new customerResource($customer),
                "reservation" =>new  ReserveResource($reservation)
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
            $customer  = Customer::whereId($id)->first();
            if(!$customer){
                return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'There are error on data');
            }
            $customer->delete();
            return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,"Reservation Delete Successfully");
        }catch (\Exception $ex){
            DB::rollBack();
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }
}
