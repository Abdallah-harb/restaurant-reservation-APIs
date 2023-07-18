<?php

namespace App\Http\Resources\Reservation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReserveResource extends JsonResource
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
            "customer_id" => $this->customer_id,
            "from_time" => $this->from_time,
            "to_time" => $this->to_time
        ];
    }
}
