<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "order_id" => $this->order_id,
            "meal_id " => $this->meal_id ,
            "quantity" => $this->quantity,
            "amount_to_pay" => $this->amount_to_pay
        ];
    }
}
