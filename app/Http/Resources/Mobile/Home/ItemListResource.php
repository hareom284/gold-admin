<?php

namespace App\Http\Resources\Mobile\Home;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemListResource extends JsonResource
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
            'name'=> $this->name,
            'type'=> $this->type,
            'movie_access'=> $this->movie_access,
            'IMDb_rating' => $this->IMDb_rating,
            'realease_year'=>Carbon::parse($this->release_date)->year,
            'poster_image' =>  setBaseUrlWithFileName($this->poster_url ?? null ),
            'thumbnail_image' =>setBaseUrlWithFileName($this->thumbnail_url ?? null),
        ];
    }
}
