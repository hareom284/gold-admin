<?php

namespace App\Http\Resources\Mobile\Genral;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResoruce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'plan_id' => $this->plan_id,
            'name' => $this->plan->name,
            'price'=>$this->plan->price,
            'discount'=>$this->plan->discount,
            'total_price'=>$this->plan->total_price,
            'duration'=>$this->plan->duration_value . ' ' . $this->plan->duration,
        ];
    }
}
