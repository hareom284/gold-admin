<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CastCrewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'bio' => $this->bio,
            'type' => 'castcrew',
            'place_of_birth' => $this->place_of_birth,
            'dob' => $this->dob,
            'designation' => $this->designation,
            'profile_image' => setBaseUrlWithFileName($this->file_url),
        ];
    }
}
