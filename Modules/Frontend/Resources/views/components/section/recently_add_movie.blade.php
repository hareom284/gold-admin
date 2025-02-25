@php

  use Illuminate\Support\Facades\Crypt;

@endphp

<div class="top-ten-block">
    <div class="d-flex align-items-center justify-content-between my-2">
    <h5 class="main-title text-capitalize fw-bold mb-2">{{__('frontend.recently_add')}}</h5>
    </div>
    <div class="card-style-slider {{ count($recently_add) <= 6 ? 'slide-data-less' : '' }}">
        <div class="slick-general slick-general-recently-moive-section  iq-top-ten-block-slider" data-items="6.5" data-items-desktop="5.5" data-items-laptop="4.5" data-items-tab="3.5" data-items-mobile-sm="3.5"
            data-items-mobile="2.5" data-speed="1000" data-autoplay="false" data-center="false" data-infinite="false"
            data-navigation="true" data-pagination="false" data-spacing="12">
            @foreach ( $recently_add as $index => $data)
            <div class="slick-item">
                <div class="iq-top-ten-block">
                    <div class="block-image position-relative">
                        <div class="img-box">
                            <a  class="overly-images" href="{{ $data['type'] == 'tvshow' ? route('tvshow-details', ['id' => Crypt::encrypt($data['id'])]) : route('movie-details', ['id' => Crypt::encrypt($data['id'])]) }}">
                                <img src="{{ $data['poster_image'] }}" alt="movie-card" class="img-fluid object-cover top-ten-img">
                                @if($data['movie_access']=='paid')
                                <button type="button" class="product-premium border-0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Premium"><i class="ph-fill ph-crown"></i></button>
                                @endif
                                @if ($data['imdb_rating'] != null)
                                  <span class=" imdb_rating_icon">
                                    <i class="ph-fill ph-star"></i> {{ $data['imdb_rating'] }}
                                  </span>
                                @endif
                            </a>
                            {{-- <span class="top-ten-numbers texture-text" style="background-image: url('{{ asset('img/web-img/texture.jpg') }}');">
                                {{ $index + 1 }}
                            </span> --}}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>

