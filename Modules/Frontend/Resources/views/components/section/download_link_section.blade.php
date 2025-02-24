@if(@isset($data['type']) && $data['type'] == 'episode')
    <div class="row" style="font-weight: 400;font-size:16px;line-height:39px;">
    @foreach ($data as $link)

        <div class="col-md-6 col-12 mt-2">
            <div class="d-flex justify-content-around border align-items-center  rounded p-2 me-5" >
                <div class="text-white"></div>
                <div class="text-white">1.2 GB</div>
                <div>Gdrive</div>
                <form action="{{route('predownload')}}" method="post">
                    @csrf
                    @php
                        $url = Crypt::encrypt($link->url);
                    @endphp
                    <input type="hidden" name="url" value="{{$url}}">
                    <button type="submit" class="btn btn-dark"><i class="ph ph-download-simple"></i></button>
                </form>

            </div>
        </div>
    @endforeach
    <hr class="my-5 border border-light">
    </div>
@else
    <div class="row" style="font-weight: 400;font-size:16px;line-height:39px;">
        @foreach ($data as $link)

            <div class="col-md-6 col-12 mt-2">
                <div class="d-flex justify-content-around border align-items-center  rounded p-2 me-5" >
                    <div class="text-white">{{$link->quality}}</div>
                    <div class="text-white">1.2 GB</div>
                    <div>{{$link->type}}</div>
                    <form action="{{route('predownload')}}" method="post">
                        @csrf
                        @php
                            $url = Crypt::encrypt($link->url);
                        @endphp
                        <input type="hidden" name="url" value="{{$url}}">

                        <button type="submit" class="btn btn-dark"><i class="ph ph-download-simple"></i></button>
                    </form>

                </div>
            </div>
        @endforeach
    <hr class="my-5 border border-light">
    </div>
@endif
    {{-- <hr class="my-5 border border-light"> --}}
</div>
