<?php

namespace App\Http\Resources\Mobile\Genral;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Subscriptions\Transformers\SubscriptionResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'user_name'=>$this->username,
            'email'=>$this->email,
            'phone'=>$this->mobile,
            'login_type'=>$this->login_type,
            'is_banned'=>$this->is_banned,
            'is_subscribe'=>$this->is_subscribe,
            'status'=>$this->status,
            'user_type'=>$this->user_type,
            'profile_image'=>$this->profile_image,
            'subscription_package'=>new PackageResoruce($this->subscriptionPackage)
        ];
    }
}
