<div class="accordion" id="accordionExample{{$index}}">
    <div class="accordion-item">
        <h2 class="accordion-header" id="heading{{$index}}">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$index}}" aria-expanded="false" aria-controls="collapse{{$index}}">
            <img src="{{ $data['poster_image'] }}" alt="movie image" class="object-fit-cover img-fluid   rounded " style="width
            : 150px; height: 150px;">

            <div class="ms-5">
                <h5 class="mb-2">{{  $data['name'] }}</h5>
                {{ $data['duration'] ? formatDuration($data['duration']) : '--' }}
            </div>

          </button>
        </h2>
        <div id="collapse{{$index}}" class="accordion-collapse collapse" aria-labelledby="heading{{$index}}" data-bs-parent="#accordionExample">
          <div class="accordion-body">
            <a href="#" class="btn btn-custom-button-one mb-3">
                <span class="d-flex align-items-center justify-content-center gap-2">
                    <span><i class="ph-fill ph-crown"></i></span>
                    <span class="text-nowrap">{{__('frontend.enjoy_subscription')}}</span>
                </span>
            </a>
            @include('frontend::components.section.download_link_section')
          </div>
        </div>
      </div>
</div>
