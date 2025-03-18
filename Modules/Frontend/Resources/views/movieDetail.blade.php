@extends('frontend::layouts.master')

@section('content')
@push('after-styles')
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
@endpush


<div id="thumbnail-section">
    @include('frontend::components.section.custom-video-detail',  ['data' => $data])
</div>

{{-- <div id="detail-section">
    @include('frontend::components.section.data_detail',  ['data' => $data])
</div> --}}

<div class="short-menu my-5">
    <div class="container-fluid">
        <div class="py-2 px-md-5 px-3 movie-detail-menu rounded">
            <div class="d-flex align-items-center gap-2">
                  <ul class="nav " role="tablist" id="detail-tab">
                    <li class="nav-item underline-white" role="presentation">
                      <a class="active text-white movie-related-links" style="font-weight: 600;font-size:18px;line-height:39px;" id="simple-tab-0" data-bs-toggle="tab" href="#simple-tabpanel-0" role="tab" aria-controls="simple-tabpanel-0" aria-selected="true">Cast & Crew</a>
                    </li>
                    <li class="nav-item underline-white movie-related-links" role="presentation">
                      <a class="text-white" style="font-weight: 600;font-size:18px;line-height:39px;" id="simple-tab-1" data-bs-toggle="tab" href="#simple-tabpanel-1" role="tab" aria-controls="simple-tabpanel-1" aria-selected="false">Download</a>
                    </li>
                  </ul>
            </div>
        </div>
    </div>
</div>

@if (!auth()->user()?->is_subscribe && $data['movie_access'] == 'paid')
    <div class="container-fluid mt-4">
        <a href="{{route('accountSetting')}}" class="btn btn-custom-button-one">
            <span class="d-flex align-items-center justify-content-center gap-2">
                <span><i class="ph-fill ph-crown"></i></span>
                <span class="text-nowrap">{{__('frontend.enjoy_subscription')}}</span>
            </span>
        </a>
    </div>
@endif

<div class="container-fluid mt-4">
    <div class="tab-content " id="tab-content">

        <div class="tab-pane active" id="simple-tabpanel-0" role="tabpanel" aria-labelledby="simple-tab-0">
            <div class=" padding-right-0">
                <div class="overflow-hidden">
                    @if(count( $data['casts']) >0)
                    <div id="movie-cast" class="half-spacing">
                        @include('frontend::components.section.castcrew',  ['data' => $data['casts']->toArray(request()), 'title'=> __('frontend.casts'),'entertainment_id' =>$data['id'], 'type'=>'actor', 'slug'=>''])
                    </div>
                    @endif

                    @if(count( $data['directors']) >0)
                    <div id="favorite-personality">
                        @include('frontend::components.section.castcrew',  ['data' => $data['directors']->toArray(request()),'title'=> __('frontend.directors'),'entertainment_id' =>$data['id'],'type'=>'director', 'slug'=>''])
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="tab-pane mb-5" id="simple-tabpanel-1" role="tabpanel" aria-labelledby="simple-tab-1">
            @include('frontend::components.section.download_link_section', ['data' => $data['video_links']])
        </div>
      </div>
</div>

<div class="container-fluid padding-right-0">
    <div class="overflow-hidden">
        @if(count($data['more_items']) !=0 )
        <div id="more-like-this">
            @include('frontend::components.section.entertainment',  ['data' => $data['more_items'], 'title'=>__('frontend.more_like_this'), 'type'=>$data['type'], 'slug'=>''])
        </div>
        @endif
    </div>
</div>

<div class="modal fade" id="DeviceSupport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content position-relative">
            <div class="modal-body user-login-card m-0 p-4 position-relative">
                <button type="button" class="btn btn-primary custom-close-btn rounded-2" data-bs-dismiss="modal">
                    <i class="ph ph-x text-white fw-bold align-middle"></i>
                </button>

                <div class="modal-body">
                    {{__('frontend.device_not_support')}}
                  </div>

                <div class="d-flex align-items-center justify-content-center">
                    <a href="{{ Auth::check() ? route('subscriptionPlan') : route('login') }}" class="btn btn-primary mt-5">{{__('frontend.upgrade')}}</a>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
