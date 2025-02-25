<?php

namespace App\Http\Resources\Mobile\Detail;

use App\Http\Resources\Mobile\Genral\DownloadLinkResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Mobile\Genral\PlanResource;
use Modules\Entertainment\Models\EntertainmentDownload;

class EpisodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    protected $userId;
    public function __construct($resource, $userId = null)
    {
        parent::__construct($resource);
        $this->userId = $userId;
    }

    public function toArray(Request $request): array
    {
        if($this->userId){
            $is_download = EntertainmentDownload::where('entertainment_id', $this->id)->where('user_id', $this->userId)->where('entertainment_type', 'episode')->where('is_download', 1)->exists();
        }

        $download_links = $this->EpisodeStreamContentMapping ?? null;

        return [

            'name' => $this->name,
            'poster_url' =>  setBaseUrlWithFileName($this->poster_url),
            'access' => $this->access,
            'plan_id' => $this->plan_id,
            'duration' => $this->duration,
            'video_url' => $this->video_upload_type=='Local' ? setBaseUrlWithFileName($this->video_url_input) : $this->video_url_input,
            'download_links' => DownloadLinkResource::collection($download_links),
            // 'plan' => new PlanResource($this->plan),
            // 'id' => $this->id,
             // 'entertainment_id' => $this->entertainment_id,
            // 'season_id' => $this->season_id,
            // 'type'=>'episode',
            // 'imdb_rating' => $this->IMDb_rating,
            // 'release_date' => $this->release_date,
            // 'video_upload_type' => $this->video_upload_type,
                // 'download_status' => $is_download ?? false,
            // 'enable_quality' => $this->enable_quality,
            // 'download_url' => $this->download_url,
        ];
    }
}
