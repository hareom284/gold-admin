@extends('frontend::layouts.master')

@section('content')
    <div class="container-fluid bg-red">
        <div id="adsbanner-section" class="px-0 section-spacing-bottom">
            @php
                $ads_banner = Modules\AdBanner\Models\AdBanner::where('status',1)->get();
            @endphp
            @include('frontend::components.section.adsbanner', ['data' => $ads_banner ?? []])
        </div>
        <div id="adsbanner-section" class="px-0 section-spacing-bottom">
            @php
                $ads_banner = Modules\AdBanner\Models\AdBanner::where('status',1)->get();
            @endphp
            @include('frontend::components.section.adsbanner', ['data' => $ads_banner ?? []])
        </div>
        <div class="row  text-center mb-5">
            <div class="col">
                <a id="download_url" target="_blank"  href="{{route('download-video',$url)}}" class="btn btn-primary disabled">Please wait 10</a>
            </div>
        </div>
        <div id="adsbanner-section" class="px-0 section-spacing-bottom">
            @php
                $ads_banner = Modules\AdBanner\Models\AdBanner::where('status',1)->get();
            @endphp
            @include('frontend::components.section.adsbanner', ['data' => $ads_banner ?? []])
        </div>
        <div id="adsbanner-section" class="px-0 section-spacing-bottom">
            @php
                $ads_banner = Modules\AdBanner\Models\AdBanner::where('status',1)->get();
            @endphp
            @include('frontend::components.section.adsbanner', ['data' => $ads_banner ?? []])
        </div>
    </div>
@endsection
<script>
    window.addEventListener('load',()=>{
        let count = 10;
        let downloadUrl = $('#download_url');
        const countDown = setInterval(()=>{
            if(count > 0 ){
                count--;
                downloadUrl.text("Please wait "+count);
            }else{
                downloadUrl.text("Download").removeClass('disabled');
                clearInterval(countDown);
            }
        },100)
    })
</script>
