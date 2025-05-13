<div class="row">
    <div class="container">
        <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($ads_banner as $slider )
                <div class="carousel-item {{$loop->first ? 'active' : ''}}">
                    <a href="{{$slider['link']}}">
                        <img src="{{setBaseUrlWithFileName($slider['file_url'])}}" class="d-block w-100" alt="...">
                    </a>
                  </div>
                @endforeach
            </div>
          </div>
    </div>
</div>

