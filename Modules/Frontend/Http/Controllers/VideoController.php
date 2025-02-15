<?php

namespace Modules\Frontend\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Modules\Video\Models\Video;
use App\Models\UserSearchHistory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Modules\Entertainment\Models\Like;
use Illuminate\Support\Facades\Response;
use Modules\Entertainment\Models\Watchlist;
use Modules\Video\Transformers\VideoResource;
use Modules\Entertainment\Models\ContinueWatch;
use Modules\Entertainment\Models\Entertainment;
use Modules\Video\Transformers\VideoDetailResource;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Modules\Entertainment\Models\EntertainmentDownload;
use Modules\Entertainment\Transformers\MovieDetailResource;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function downloadVideo(){
        $fileUrl = 'https://video6-moviescdn.b-cdn.net/Chinese-Series/19th%20Floor(2024)/1%2019th%20FLOOR%20FHD.mp4';
        $fileName = '19th_FLOOR_FHD.mp4';

        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ];

        return response()->stream(function () use ($fileUrl) {
            $stream = fopen($fileUrl, 'rb');
            while (!feof($stream)) {
                echo fread($stream, 1024 * 8); // Read in 8 KB chunks
                ob_flush();
                flush();
            }
            fclose($stream);
        }, 200, $headers);
     }

    public function videoList()
    {
      return view('frontend::video');
    }

    public function videoDetails(Request $request, $id)
    {
        $videoId = $id;
        $userId = auth()->id();
        $cacheKey = 'video_' . $videoId . '_' .$request->profile_id;

        $data = Cache::get($cacheKey);

        if (!$data) {
            $video = Video::with('VideoStreamContentMappings', 'plan')
                ->where('id', $videoId)
                ->firstOrFail();

            if ($userId) {
                $continueWatch = ContinueWatch::where('entertainment_id', $video->entertainment_id)
                    ->where('user_id', $userId)
                    ->where('entertainment_type', 'video')
                    ->first();

                $video->continue_watch = $continueWatch;


                if (!empty($video->trailer_url) &&  $video->trailer_url_type != 'Local') {

                    $video['trailer_url'] = Crypt::encryptString($video->trailer_url);
                }


                if (!empty($video->video_url_input) &&  $video->video_upload_type != 'Local') {
                    $video['video_url_input'] = Crypt::encryptString($video->video_url_input);
                }


                $video->is_watch_list = WatchList::where('entertainment_id', $video->entertainment_id)
                    ->where('user_id', $userId)
                    ->where('type', 'video') // Added type check
                    ->exists();

                $video->is_likes = Like::where('entertainment_id', $videoId)
                    ->where('user_id', $userId)
                    ->where('is_like', 1)
                    ->exists();

                $video->is_download = EntertainmentDownload::where('entertainment_id', $video->entertainment_id)
                    ->where('user_id', $userId)
                    ->where('entertainment_type', 'movie')
                    ->where('is_download', 1)
                    ->exists();
        }

        $data = new VideoDetailResource($video);


        Cache::put($cacheKey, $data);
    }

    $data = $data->toArray($request);



    // Define entertainment type
    $entertainmentType = 'video'; // Set the type as 'video' since this is a video detail

    // Handle search history
    if ($request->has('is_search') && $request->is_search == 1) {
        $user_id = auth()->user()->id ?? $request->user_id;

        if ($user_id) {
            $currentprofile = GetCurrentprofile($user_id, $request);

            if ($currentprofile) {
                $existingSearch = UserSearchHistory::where('user_id', $user_id)
                    ->where('profile_id', $currentprofile)
                    ->where('search_query', $data['name'])
                    ->first();

                if (!$existingSearch) {
                    UserSearchHistory::create([
                        'user_id' => $user_id,
                        'profile_id' => $currentprofile,
                        'search_query' => $data['name'],
                        'search_id'=> $data['id'],
                        'type'=>'video'
                    ]);
                }
            }
        }
    }

    // Ensure 'type' key is set in the data array
    $data['type'] = $entertainmentType;

    return view('frontend::video_detail', compact('data', 'entertainmentType'));
}



    public function comingSoonList()
    {
        return view('frontend::comingsoon');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('frontend::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('frontend::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('frontend::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
