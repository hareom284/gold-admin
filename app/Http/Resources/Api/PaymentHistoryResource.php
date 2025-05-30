<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>substr($this->order_id,4,5),
            'name'=> $this->name,
            'amount'=> $this->total_amount,
            'date'=> $this->created_at,
        ];
    }
}
