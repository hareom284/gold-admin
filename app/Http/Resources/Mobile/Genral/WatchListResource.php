<?php

namespace App\Http\Resources\Mobile\Genral;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WatchListResource extends JsonResource
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
            'name'=> $this->entertainment->name,
            'type'=> $this->entertainment->type,
            'movie_access'=> $this->entertainment->movie_access,
            'IMDb_rating' => $this->entertainment->IMDb_rating,
            'realease_year'=>Carbon::parse($this->entertainment->release_date)->year,
            'poster_image' =>  setBaseUrlWithFileName($this->entertainment->poster_url ?? null ),
            'thumbnail_image' =>setBaseUrlWithFileName($this->entertainment->thumbnail_url ?? null),
        ];
    }
}
