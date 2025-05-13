<?php

namespace App\Http\Resources\Mobile\Detail;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Entertainment\Models\Entertainment;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Mobile\Genral\GenresResource;
use App\Http\Resources\Mobile\Home\ItemListResource;
use App\Http\Resources\Mobile\Genral\CastCrewListResource;
use App\Http\Resources\Mobile\Genral\DownloadLinkResource;
use Modules\Entertainment\Models\EntertainmentGenerMapping;
use Modules\Entertainment\Models\Like;
use Modules\Entertainment\Models\Watchlist;

class MovieDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //genres
        $genres_data = [];
        foreach($this->entertainmentGenerMappings as $genres)
        {
            $genres_data[]= $genres->genre;
        }

        //casts_crews
        $castCrew_data = [];
        foreach ($this->entertainmentTalentMappings as $mapping) {
                $castCrew_data[] = $mapping->talentprofile;
        }

        //genearte more items
        $genres = $this->entertainmentGenerMappings;
        $genre_ids = $genres->pluck('genre_id')->toArray();
        $entertainment_ids = EntertainmentGenerMapping::whereIn('genre_id', $genre_ids)
        ->pluck('entertainment_id')
        ->toArray();
        $more_items = Entertainment::whereIn('id', $entertainment_ids)
            ->where('type', 'movie')
            ->where('status', 1)
            ->limit(7)
            ->get()
            ->except($this->id);


        //download_links
        $download_links = $this->entertainmentStreamContentMappings;

        //like
        $like = Like::where('entertainment_id', $this->id)->where('user_id', auth('sanctum')->id())
        ->where('is_like',1)->exists();

        //is watchlist
        $is_watchlist = Watchlist::where('entertainment_id', $this->id)->where('user_id', auth('sanctum')->id())->exists();

        //review
        $reviews = $this->entertainmentReviews;

        $review_count = $reviews->count();

        $review_data = [];
        foreach($reviews as $review)
        {
            $review_data[] = [
                'id'=>$review->id,
                'rating'=>$review->rating,
                'review'=>$review->review,
                'user_name'=>$review->user->username,
                'user_image'=>setBaseUrlWithFileName($review->user->image),
                'created_at'=>$review->created_at->diffForHumans(),
            ];
        }

        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'description'=>$this->description,
            'thumbnail_url'=>setBaseUrlWithFileName($this->thumbnail_url),
            'poster_url'=>setBaseUrlWithFileName($this->poster_url),
            'movie_access'=>$this->movie_access,
            'plan_id'=>$this->plan_id,
            'language'=>$this->language,
            'IMDb_rating'=>$this->IMDb_rating,
            'TMDb_rating'=>$this->TMDb_rating,
            'duration'=>$this->duration,
            'release_year'=>Carbon::parse($this->release_date)->year,
            'genres'=>GenresResource::collection($genres_data),
            'casts_crews' => CastCrewListResource::collection($castCrew_data),
            'more_items' =>ItemListResource::collection($more_items),
            'like' => $like,
            'watchlist' => $is_watchlist,
            'review_count'  => $review_count,
            'review_data' => $review_data,
            'video_url' => $this->video_upload_type=='Local' ? setBaseUrlWithFileName($this->video_url_input) : $this->video_url_input,
            'download_links' => DownloadLinkResource::collection($download_links),
        ];
    }
}
