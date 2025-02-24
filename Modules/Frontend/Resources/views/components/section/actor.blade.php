
</style>
<div class="language-block">
    <div class="d-flex align-items-center justify-content-between my-2 me-2">
          <h5 class="main-title text-capitalize fw-bold mb-2">{{ $title }}</h5>
          @if(count($fetch_actor)>6)
          <a href="{{route('castcrewList')}}" class="view-all-button text-decoration-none flex-none"><span>{{__('frontend.view_all')}}</span> <i class="ph ph-caret-right"></i></a>
          @endif
    </div>
    <div class="card-style-slider slide-data-less" id="actor-slider">
       <div class="slick-general slick-general-actor"  data-items="6.5" data-items-laptop="5.5" data-items-tab="3.5" data-items-mobile-sm="3.5"
       data-items-mobile="2.5" data-speed="1000" data-autoplay="false" data-center="false" data-infinite="false"
       data-navigation="true" data-pagination="false" data-spacing="12">
       @foreach($fetch_actor as $data)
       <div class="d-flex align-items-center me-2 cursor-pointer">
         <div class="me-3" style="width: 200px; height: 4rem;">
           <img src="{{ setBaseUrlWithFileName($data->file_url) }}" alt="actor"
                class="img-fluid rounded"
                style="width: 100%; height: 100%; object-fit: cover;">
         </div>
         <div style="width: 300px">
           <span class="text-capitalize text-white">{{ $data->name }}</span>
         </div>
       </div>
     @endforeach
       </div>
    </div>
 </div>
