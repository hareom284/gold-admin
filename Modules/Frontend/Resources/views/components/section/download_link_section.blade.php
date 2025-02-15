
<div class="row" style="font-weight: 400;font-size:16px;line-height:39px;">
    @foreach ($data as $link)
        <div class="col-md-6 col-12 ">
            <a href="https://video6-moviescdn.b-cdn.net/Chinese-Series/19th%20Floor(2024)/1%2019th%20FLOOR%20FHD.mp4" download="19th_FLOOR_FHD.mp4">
                Download Video
            </a>
            <div class="d-flex justify-content-around border align-items-center  rounded p-2 me-5" >
                <div class="text-white">{{$link->quality}}</div>
                <div class="text-white">1.2 GB</div>
                <div>Gdrive</div>
                <div><a href="" class="btn btn-dark"><i class="ph ph-download-simple"></i></a></div>
            </div>
        </div>
    @endforeach
    {{-- <div class="col-md-6 col-12 ">
        <div class="d-flex justify-content-around border align-items-center  rounded p-2 me-5" >
            <div class="text-white">720p</div>
            <div class="text-white">1.2 GB</div>
            <div>Gdrive</div>
            <div><a href="" class="btn btn-dark"><i class="ph ph-download-simple"></i></a></div>
        </div>
    </div>
    <div class="col-md-6 col-12" >
        <div class="d-flex justify-content-around border align-items-center  rounded p-2 me-5">
            <div class="text-white">720p</div>
            <div class="text-white">1.2 GB</div>
            <div>Gdrive</div>
            <div><a href="" class="btn btn-dark"><i class="ph ph-download-simple"></i></a></div>
        </div>
    </div> --}}
    <hr class="my-5 border border-light">
</div>
