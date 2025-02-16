<?php

namespace Modules\Frontend\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Modules\Entertainment\Models\Entertainment;
use Modules\Entertainment\Models\EntertainmentStreamContentMapping;

class DownloadController extends Controller
{
    public function predownload(Request $request)
    {
        $id = $request->id;
        return view('frontend::downloadLinkGenerate', compact('id'));
    }

    public function downloadVideo($id)
    {
        try{
            //decrypt id
            $id = Crypt::decryptString($id);
        }catch(\Exception $e){
            abort(404);
        }

        $StreamContent = EntertainmentStreamContentMapping::where('id', $id)->first();

        $fileUrl =  $StreamContent->url;
        // $fileUrl = "https://video6-moviescdn.b-cdn.net/Chinese-Series/19th%20Floor(2024)/1%2019th%20FLOOR%20FHD.mp4";
        $fileName = urldecode(pathinfo($fileUrl,PATHINFO_BASENAME));
        // $contentType =get_headers($fileUrl,1)['Content-Type'];
        // return $contentType;

        $headers = [
            'Content-Type' => 'video/mp4',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ];

        return response()->stream(function () use ($fileUrl) {
            $stream = fopen($fileUrl, 'rb');
            while (!feof($stream)) {
                echo fread($stream, 1024 * 8);
                ob_flush();
                flush();
            }
            fclose($stream);
        }, 200, $headers);
    }
}
