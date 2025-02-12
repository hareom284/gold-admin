<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Modules\Entertainment\Models\Entertainment;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Entertainment\Models\Watchlist;

class BannerResource extends JsonResource
{
    public function __construct($resource, $userId = null)
    {
        parent::__construct($resource);
        $this->userId = $userId;
    }

    public function toArray($request): array
    {
        $entertainment = null;
        $data = null;

        $entertainment = Entertainment::find($this->type_id);
        if ($entertainment) {
            $entertainment['is_watch_list'] = WatchList::where('entertainment_id', $this->type_id)
                ->where('user_id', $this->userId)
                ->where('profile_id',$request->profile_id)
                ->exists();
            $data = $this->type === 'movie' ? new MoviesResource($entertainment) : new TvshowResource($entertainment);
        }

        return [
            'id' => $entertainment->id,
            'title' => $entertainment->name,
            'description' => $entertainment->description,
            'poster_url' => setBaseUrlWithFileName($this->poster_url),
            'file_url' => setBaseUrlWithFileName($this->file_url),
            'type' => $this->type,
            'plan'=>$entertainment->movie_access,
            // 'data' => $data,
        ];
    }
}
