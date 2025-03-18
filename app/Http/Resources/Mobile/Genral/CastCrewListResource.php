<?php

namespace App\Http\Resources\Mobile\Genral;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CastCrewListResource extends JsonResource
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
            'profile_image' => setBaseUrlWithFileName($this->file_url),

            // 'bio' => $this->bio,
            // 'place_of_birth' => $this->place_of_birth,
            // 'dob' => $this->dob,
            // 'designation' => $this->designation,

        ];
    }
}
