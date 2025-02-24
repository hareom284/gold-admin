<?php

namespace Modules\Frontend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Modules\Entertainment\Models\Entertainment;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Modules\Entertainment\Models\EntertainmentStreamContentMapping;

class DownloadController extends Controller
{
    public function predownload(Request $request)
    {
        $url = $request->url;
        return view('frontend::downloadLinkGenerate', compact('url'));
    }

    public function downloadVideo($url)
    {
        try{
            //decrypt id
            $url = Crypt::decryptString($url);
        }catch(\Exception $e){
            abort(404);
        }

        //clear and stop output buffering
        if (ob_get_level()) {
            ob_end_clean();
        }


        $fileUrl =  $url;
        // $fileUrl = 'https://www.w3schools.com/html/mov_bbb.mp4';

        //get file name from url
        $fileName = urldecode(pathinfo($fileUrl,PATHINFO_BASENAME));

        $headers = [
            // 'Content-Type' => 'video/mp4',
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'X-Accel-Buffering'=> 'no',
        ];

        return response()->stream(function () use ($fileUrl) {
            $stream = fopen($fileUrl, 'rb');
            $chunkSize = 1024 * 1024; // 1MB
            while (!feof($stream)) {
                echo fread($stream, $chunkSize);
                ob_flush();
                flush();
            }
            fclose($stream);
        }, 200, $headers);
    }
}
