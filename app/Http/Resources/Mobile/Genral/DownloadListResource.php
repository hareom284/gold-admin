<?php

namespace App\Http\Resources\Mobile\Genral;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Entertainment\Models\Entertainment;
use Modules\Episode\Models\Episode;

class DownloadListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        switch($this->type){
            case "movie":
                $movie = Entertainment::where('id', $this->entertainment_id)->first();
                return[
                    'id'=> $this->id,
                    'name'=> $movie->name,
                    'duration'=>$movie->duration,
                    'thumbnail_url'=>setBaseUrlWithFileName($movie->thumbnail_url),
                    'download_url'=>$movie->download_url,
                ];
            case "episode":
                $episode = Episode::where('id', $this->entertainment_id)->first();
                return[
                    'id'=> $this->id,
                    'name'=> $episode->name,
                    'duration'=>$episode->duration,
                    'thumbnail_url'=>setBaseUrlWithFileName($episode->poster_url),
                    'download_url'=>$episode->download_url,
                ];
        }

    }
}
