<?php

namespace App\Http\Resources\Mobile\Subscription;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'request_no'=>$this->request_no,
            'order_id' =>$this->order_id,
            'plan_id'=> $this->plan_id,
            'plan_name'=>$this->plan->name,
            'plan_price'=> $this->plan->total_price,
            'plan_duration' => $this->plan->duration_value.' '.$this->plan->duration,
        ];
    }
}
