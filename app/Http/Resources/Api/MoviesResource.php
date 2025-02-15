<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Entertainment\Models\Watchlist;

class MoviesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $genre_data = [];
        $genres = $this->entertainmentGenerMappings;

        if(!empty($genres)){

            foreach($genres as $genre) {

                $genre_data[] = [
                    'id' => $genre->id,
                    'name' => $genre->genre->name ?? null,
                ];
            }


        }

        $tag_data = [];
        $tags = $this->entertainmentTagMappings;

        if(!empty($tags)){

            foreach($tags as $tag) {

                $tag_data[] = [
                    'id' => $tag->id,
                    'name' => $tag->tag->name ?? null,
                ];
            }


        }

        // $plans = [];
        // $plan = $this->plan;
        // if($plan){
        //     $plans = Plan::where('level', '<=', $plan->level)->get();
        // }
        $userId = auth()->id();

        return $userId;
        
        if($userId) {
            $isInWatchList = WatchList::where('entertainment_id',$this->id)
            ->where('user_id', $userId)
            ->exists();
        }else{
            $isInWatchList = $this->is_watch_list ?? false;
        }
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'description' => strip_tags($this->description),
            'trailer_url_type' => $this->trailer_url_type,
            'type' => $this->type,
            'trailer_url' => $this->trailer_url_type=='Local' ? setBaseUrlWithFileName($this->trailer_url) : $this->trailer_url,
            'movie_access' => $this->movie_access,
            'plan_id' => $this->plan_id,
            'plan_level' => $this->plan->level ?? 0,
            'language' => $this->language,
            'imdb_rating' => $this->IMDb_rating,
            'content_rating' => $this->content_rating,
            'duration' => $this->duration,
            'release_date' => $this->release_date,
            'is_restricted' => $this->is_restricted,
            'video_upload_type' => $this->video_upload_type,
            'video_url_input' => $this->video_upload_type=='Local' ? setBaseUrlWithFileName($this->video_url_input) : $this->video_url_input,
            'download_status' => $this->download_status,
            'enable_quality' => $this->enable_quality,
            'download_url' => $this->download_url,
            'poster_image' => setBaseUrlWithFileName($this->poster_url ?? null),
            'thumbnail_image' =>setBaseUrlWithFileName($this->thumbnail_url ?? null),
            'is_watch_list' => $isInWatchList ? true : false,
            'genres' => $genre_data,
            'tags'=> $tag_data,
            // 'plans' => PlanResource::collection($plans),
            // 'status' => $this->status,

        ];
    }
}
