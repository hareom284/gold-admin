<style>

.movie-related-links {
    color: #8F8F8F;
    text-decoration: none;
    transition: color 0.3s ease-in-out, font-weight 0.3s ease-in-out, transform 0.3s ease-in-out;
}

.movie-related-links:hover {
    color: #FFF;
    font-weight: bold;
    transform: scale(1.05);
    text-shadow: 0 0 10px rgba(255, 255, 255, 0.6);
}

</style>
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
                        {{count($data['tvShowLinks'])}} season{{count($data['tvShowLinks']) != 1 ? 's' : ''}}
                        @endif
                    </div>
                    <p class="p-0 my-4 text-white" style="font-weight: 400;font-size:16px;line-htight:32px;">{{$data['description']}}</p>
                    <div class="p-0 mt-3" style="font-weight: 400;font-size:16px;line-height:32px;">
                        Genres &nbsp;&nbsp;&nbsp;
                        <span class="text-white">
                            <div class="flex">
                            @foreach ($data['genres'] as $genre)
                                @isset($genre->name)  
                                <a class="nav-menu-drop-down cursor-pointer movie-related-links" href="{{route("movies.genre", Crypt::encrypt($genre->id))}}">{{$genre->name}}</a>
                                @if ($loop->last == false)
                                    ,
                                @endif
                                @else
                                    ''
                                @endisset
                                
                            @endforeach
                            </div>
                        </span> <br>

                        <h6 style="font-weight: 400;font-size:16px;line-height:32px;">Language</h6>
                        <small><a href="{{route('movies.language',$data['language'])}}" class="text-white movie-related-links">{{ucfirst($data['language'])}}</a></small>
                    </div>
                </div>
             </div>
    </div>
</div>

