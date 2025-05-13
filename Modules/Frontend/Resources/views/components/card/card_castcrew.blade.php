@php

    use Illuminate\Support\Facades\Crypt;

@endphp

@if(isset($is_search) && $is_search==1 )
    <a href="{{ route('castcrew-detail', ['id' => Crypt::encrypt($data['id']), 'is_search' => request()->has('search') ? 1 : null]) }}" class="text-center cast-card position-relative rounded overflow-hidden d-block">
        <img src="{{ $data['profile_image'] }}" alt="personality" class="img-fluid object-cover position-relative cast-image">
        <span class="h6 mb-0 cast-title"> {{  $data['name'] ?? '--' }}</span>
    </a>
@else
    <a href="{{ route('castcrew-detail', ['id' => Crypt::encrypt($data['id'])]) }}" class="text-center cast-card position-relative rounded overflow-hidden d-block">
        <img src="{{ $data['profile_image'] }}" alt="personality" class="img-fluid object-cover position-relative cast-image">
        <span class="h6 mb-0 cast-title"> {{  $data['name'] ?? '--' }}</span>
    </a>
@endif
