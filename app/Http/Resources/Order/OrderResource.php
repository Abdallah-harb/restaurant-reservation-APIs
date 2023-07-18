<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            "table_id" => $this->table_id,
            "reservation_id" => $this->reservation_id,
            "customer_id" => $this->customer_id,
            "waiter_id" => $this->waiter_id,
            "total" => $this->total,
            "paid" => $this->paid,
            "date" => $this->date,
            "orderDetails" => $this->whenLoaded('orderdDetails')
        ];
    }
}
