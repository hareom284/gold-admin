<?php

namespace App\Http\Resources\Mobile\Home;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContinueWatchingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $entertainment = null;

        if($this->entertainment_type == 'movie'){
            $entertainment = $this->entertainment;
        }
        else if($this->entertainment_type == 'tvshow'){
            $entertainment = $this->episode;
        }
        else if($this->entertainment_type == 'video'){
            $entertainment = $this->video;
        }

        return [

            'entertainment_id' => $this->entertainment_id,
            'entertainment_type' => $this->entertainment_type,
            'watched_time' => $this->watched_time ?? '00:00:01',
            'total_watched_time' => $this->total_watched_time ?? '00:00:01',
            'episode_id' => $this->episode_id ?? null,
            'poster_image' =>  setBaseUrlWithFileName($entertainment->poster_url ?? null ),
            'thumbnail_image' =>setBaseUrlWithFileName($entertainment->thumbnail_url ?? null),
            'status' => $entertainment->status ?? null,
            ];
        }
}
