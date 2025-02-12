@extends('frontend::layouts.master')

@section('content')


<div id="thumbnail-section">
    {{-- @include('frontend::components.section.thumbnail',  ['data' => $data['trailer_url'] ,'type'=>$data['trailer_url_type'],'thumbnail_image'=>$data['thumbnail_image']]) --}}
    @include('frontend::components.section.custom-video-detail',  ['data' => $data])

</div>

{{-- <div id="detail-section"y
    <div id="tvshow-id">
        @include('frontend::components.section.data_detail',  ['data' => $data])
    </div>
</div> --}}
<div class="short-menu my-5">
    <div class="container-fluid">
        <div class=" movie-detail-menu rounded">
            <div class="d-flex align-items-center gap-2">
                  <ul class="nav " role="tablist" id="detail-tab">
                    <li class="nav-item px-3 underline-white" role="presentation">
                      <a class="active text-white" style="font-weight: 600;font-size:18px;line-height:39px;" id="simple-tab-0" data-bs-toggle="tab" href="#simple-tabpanel-0" role="tab" aria-controls="simple-tabpanel-0" aria-selected="true">Cast & Crew</a>
                    </li>
                    <li class="nav-item px-3 underline-white" role="presentation">
                      <a class="text-white" style="font-weight: 600;font-size:18px;line-height:39px;" id="simple-tab-1" data-bs-toggle="tab" href="#simple-tabpanel-1" role="tab" aria-controls="simple-tabpanel-1" aria-selected="false">Download</a>
                    </li>
                  </ul>
            </div>
        </div>
    </div
</div>



<div class="container-fluid mt-4">
    <div class="tab-content" id="tab-content">
        <div class="tab-pane active " id="simple-tabpanel-0" role="tabpanel" aria-labelledby="simple-tab-0">
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


        <div class="tab-pane  mb-5" id="simple-tabpanel-1" role="tabpanel" aria-labelledby="simple-tab-1">
            <div id="seasons">
                @include('frontend::components.section.seasons',  ['data' => $data['tvShowLinks']])
            </div>
        </div>
      </div>
</div>


{{-- <div class="container-fluid">
    <div id="seasons">
        @include('frontend::components.section.seasons',  ['data' => $data['tvShowLinks']])
    </div>
</div> --}}


{{-- <div class="container-fluid">
    <div id="add-review">
        @include('frontend::components.section.add_review',  ['addreview' => 'Add Review'])
    </div>

    @if($data['three_reviews'] != null)
        <div id="review-list">
            @include('frontend::components.section.review_list',  ['data' => $data['three_reviews']->toArray(request()), 'your_review'=> $data['your_review'], 'title'=> $data['name'], 'total_review'=>count($data['reviews'])])
        </div>
    @endif
</div> --}}

<div class="container-fluid padding-right-0">
    <div class="overflow-hidden">
        @if($data['more_items'] != null)
            <div id="more-like-this">
                @include('frontend::components.section.entertainment',  ['data' => $data['more_items'], 'title'=>__('frontend.more_like_this'),'type' => $data['type'],'slug'=>''])
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
                    <a href="{{ Auth::check() ? route('subscriptionPlan') : route('login') }}" class="btn btn-primary mt-5" >{{__('frontend.upgrade')}}</a>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
