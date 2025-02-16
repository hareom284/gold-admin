
@php
$footerData = getFooterData();
@endphp

<footer class="footer">
  <div class="footer-top">
    <div class="container-fluid " style="padding-block: 50px;">
        <div class="row text-center">
            <div class="footer-logo mb-4 d-flex justify-content-center align-items-center section-spacing-bottom">
                <!--Logo -->
                 <div>
                    <div class="logo-default">
                      <a class="navbar-brand text-primary" href="{{route('home')}}">
                          <img class="img-fluid logo" src="{{ asset(setting('logo')) }}" alt="Gold Channel">
                      </a>
                    </div>
                 </div>
                 <div style="font-size:24px;font-weight:800;color:white;" class="ms-3">Gold Channel</div>
            </div>

            <div class="section-spacing-bottom">
                <h5 style="font-weight: 800;font-size:20px;line-height:32px;">Download our app</h5>
                <h6 style="font-weight: 400;font-size:20px;line-height:32px;color:#BEBEBE">Enjoy instant access to the best movies and TV shows right on <br>your mobile screen.</h6>
            </div>

            <div class="mt-3 mb-5 d-flex justify-content-center gap-3 ">
                <button class="btn btn-outline-light">
                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_221_429)">
                        <path d="M12.98 12.0198L1.37001 23.6298C0.815015 23.1048 0.515015 22.3848 0.515015 21.6048V3.39484C0.515015 2.59984 0.830015 1.87984 1.40001 1.33984L12.98 12.0198Z" fill="#2196F3"/>
                        <path d="M23.015 12.5001C23.015 13.5501 22.445 14.4801 21.5 15.0051L18.2 16.8351L14.105 13.0551L12.98 12.0201L17.315 7.68506L21.5 9.99506C22.445 10.5201 23.015 11.4501 23.015 12.5001Z" fill="#FFC107"/>
                        <path d="M12.98 12.0201L1.40002 1.34015C1.55002 1.19015 1.74502 1.05515 1.94002 0.935148C2.88502 0.365148 4.02502 0.350148 5.00002 0.890148L17.315 7.68515L12.98 12.0201Z" fill="#4CAF50"/>
                        <path d="M18.2 16.835L5 24.11C4.535 24.38 4.01 24.5 3.5 24.5C2.96 24.5 2.42 24.365 1.94 24.065C1.7317 23.9457 1.54004 23.7994 1.37 23.63L12.98 12.02L14.105 13.055L18.2 16.835Z" fill="#F44336"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_221_429">
                        <rect width="24" height="24" fill="white" transform="translate(0.5 0.5)"/>
                        </clipPath>
                        </defs>
                    </svg>
                    Get on Google Play</button>
                <button class="btn  btn-outline-light">
                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M16.1181 5.84928C16.5193 5.3918 16.8252 4.86042 17.0182 4.28565C17.2112 3.71089 17.2876 3.10405 17.243 2.5C16.0113 2.59836 14.868 3.17149 14.0594 4.09589C13.6722 4.53894 13.3787 5.05435 13.1965 5.61162C13.0142 6.16889 12.9468 6.75667 12.9982 7.34018C13.599 7.34513 14.1929 7.21324 14.7339 6.95471C15.2749 6.69617 15.7485 6.31794 16.1181 5.84928ZM18.7923 13.6608C18.7994 12.8562 19.0136 12.0668 19.4146 11.3669C19.8157 10.667 20.3904 10.0797 21.0844 9.66052C20.6464 9.03607 20.0673 8.52117 19.3932 8.15668C18.719 7.79219 17.9683 7.58812 17.2005 7.56066C15.5451 7.39267 14.017 8.5161 13.1362 8.5161C12.2554 8.5161 11.0138 7.58166 9.63428 7.60266C8.73241 7.63205 7.85364 7.89221 7.08367 8.35776C6.3137 8.8233 5.67883 9.47834 5.24098 10.259C3.3733 13.4718 4.76345 18.249 6.63113 20.8423C7.48008 22.1127 8.54126 23.5511 9.94202 23.4986C11.3428 23.4461 11.7991 22.6377 13.4227 22.6377C15.0463 22.6377 15.5451 23.4986 16.9246 23.4671C18.3041 23.4356 19.2804 22.1652 20.1718 20.8948C20.8033 19.9724 21.2965 18.9646 21.6363 17.9025C20.7952 17.5476 20.0777 16.9568 19.5723 16.203C19.0669 15.4491 18.7957 14.5654 18.7923 13.6608Z" fill="#FAFAFA"/>
                    </svg>
                    Get on App Store</button>
            </div>

        </div>
      {{-- <div class="row">
        <div class="col-xxl-2 col-xl-2 col-sm-6">
          <div class="footer-logo mb-4">
              <!--Logo -->
               @include('frontend::components.partials.logo')
          </div>
          <span class="font-size-14">
            {{__('frontend.footer_content')}}
          </span>
          <div class="mt-5">
            <p class="mb-2 font-size-14">{{__('frontend.email_us')}}: <a href="mailto:customer@goldchannel.com" class="link-body-emphasis">customer@goldchannel.com</a></p>
            <p class="m-0 font-size-14">{{__('frontend.helpline_number')}}: <a href="tel:+480-555-0103" class="link-body-emphasis fw-medium">+ (480) 555-0103</a></p>
          </div>
        </div>
        @if(isenablemodule('tvshow')==1)
        <div class="col-xxl-2 col-xl-2 col-sm-6 mt-sm-0 mt-5">
            <h4 class="footer-title font-size-18 mb-5">{{__('frontend.premium_show')}}</h4>
            <ul class="list-unstyled footer-menu">
                @foreach($footerData['premiumShows'] as $show)
                <li class="mb-3">
                    <a href="{{ route('tvshow-details', $show->id) }}">{{ $show->name }}</a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
        @if(isenablemodule('movie')==1)
        <div class="col-xxl-2 col-xl-2 col-sm-6 mt-xl-0 mt-5">
          <h4 class="footer-title font-size-18 mb-5">{{__('frontend.top_movie_to_watch')}}</h4>
          <ul class="list-unstyled footer-menu">
            @foreach($footerData['topMovies'] as $movie)
            <li class="mb-3">
              @if($movie->type=='movie')
              <a href="{{ route('movie-details', $movie->id) }}">{{ $movie->name }}</a>
              @else
              <a href="{{ route('tvshow-details', $movie->id) }}">{{ $movie->name }}</a>
              @endif
            </li>
            @endforeach
          </ul>
        </div>
        @endif
        <div class="col-xxl-3 col-xl-3 col-sm-6 mt-xl-0 mt-5">
          <h4 class="footer-title font-size-18 mb-5">{{__('frontend.usefull_links')}}</h4>
          <ul class="list-unstyled footer-menu column-count-2">
            @foreach($footerData['pages'] as $page)

            <li class="mb-3">
            <a href="{{ route('page.show', ['slug' => $page->slug]) }}">{{ $page->name }}</a>
            </li>
            @endforeach
            <li class="mb-3">
              <a href="{{route('faq')}}">{{__('frontend.faq')}}</a>
            </li>

          </ul>
        </div>
        <div class="col-xxl-3 col-xl-3 col-sm-6 mt-xl-0 mt-5">
          <h4 class="footer-title font-size-18 mb-5">{{__('frontend.download_app')}}</h4>
          <p class="mb-5">{{__('frontend.download_app_reason')}}</p>

          <ul class="app-icon list-inline m-0 p-0 d-flex align-items-center gap-3">
            <li>
              <a href="#" class="btn btn-link p-0">
              <img src="{{ asset('img/web-img/play_store.png') }}" alt="play store" class="img-fluid">
              </a>
            </li>
            <li>
            <a href="#" class="btn btn-link p-0">
              <img src="{{ asset('img/web-img/app_store.png') }}" alt="app store" class="img-fluid">
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div> --}}
  </div>
  <div class="footer-bottom mt-5 border-top  mx-5">
    <div class="container-fluid">
      <div class="text-center" style="font-weight: 400;font-size:18px;line-height:29px;">
        © {{ now()->year }} <span >Gold Channel</span>. {{__('frontend.all_rights_reserved')}}.
      </div>
    </div>
  </div>
</footer>
<!-- sticky footer -->
  @include('frontend::components.partials.footer-sticky-menu')
