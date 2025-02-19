<div class="container-fluid">
    <div class="row" >
            <div class="col-md-4 col-12">
                <img class="img-fluid object-cover rounded" style="width: -webkit-fill-available;max-height:530px"  src="{{$data['poster_image']}}" alt="">
             </div>
             <div class="col-md-8 col-12">
                <div class="row p-2">
                    <div class="p-0 d-flex" style="font-weight: 400;font-size:18px;line-height:32px;color:#8F8F8F">
                        <div class="me-5">
                            IMDb <span class="border text-white px-2 py-1 border-light rounded ms-3"><i class="ph-fill ph-star me-2"></i>6.0</span>
                        </div>
                         <div class="ms-5">
                            TMDb <span class="border border-light text-white px-2 py-1 rounded ms-3"><i class="ph-fill ph-star me-2"></i>4.0</span>
                         </div>
                    </div>
                    <h4 class="p-0 my-2" style="font-weight: 800;font-size:25px;line-height:58px;">{{$data['name']}}</h4>
                    <div class="p-0 " style="font-weight: 400;font-size:18px;line-height:18px">
                        {{$data['release_year']}} &nbsp;&nbsp;
                        @if ($data['type'] == 'movie')
                             {{formatDuration($data['duration'])}}
                        @else ()
                        {{count($data['tvShowLinks'])}} seasons
                        @endif
                    </div>
                    <p class="p-0 my-4 text-white" style="font-weight: 400;font-size:16px;line-htight:32px;">{{$data['description']}}</p>
                    <div class="p-0 mt-3" style="font-weight: 400;font-size:16px;line-height:32px;">
                        Genres &nbsp;&nbsp;&nbsp;
                        <span class="text-white">
                            @foreach ($data['genres'] as $genre)
                                {{isset($genre->name) ? $genre->name : ''}}
                                @if ($loop->last == false)
                                    ,
                                @endif
                            @endforeach
                        </span> <br>

                        Language &nbsp;&nbsp;&nbsp; <a href="{{route('movies.language',$data['language'])}}" class="text-white">{{ucfirst($data['language'])}}</a>
                    </div>
                </div>
             </div>
    </div>
</div>

