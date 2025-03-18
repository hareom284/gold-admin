
@php
$footerData = getFooterData();
@endphp

<footer class="footer">
  <div class="footer-top">
    <div class="container-fluid " style="padding-block: 20px;">
        <div class="row text-center">
            <div class="footer-logo mb-5 d-flex justify-content-center align-items-center ">
                <!--Logo -->
                 <div>
                    <div class="logo-default">
                      <a class="navbar-brand text-primary" href="{{route('home')}}">
                          <img class="img-fluid logo" src="{{ asset(setting('dark_logo')) }}" alt="Gold Channel">
                      </a>
                    </div>
                 </div>
                 <div style="font-size:24px;font-weight:800;color:white;" class="ms-3">Gold Channel</div>
            </div>

            <div class="mb-5">
                <h5 style="font-weight: 800;font-size:20px;line-height:32px;">Download our app</h5>
                <h6 style="font-weight: 400;font-size:20px;line-height:32px;color:#BEBEBE">Enjoy instant access to the best movies and TV shows right on <br>your mobile screen.</h6>
            </div>

            <div class="mt-3 mb-1 d-flex justify-content-center gap-3 ">
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
                <button class="btn  btn-outline-light d-flex align-items-center gap-1">
                    <i class="ph-fill ph-apple-logo text-white fs-5"></i>
                    Get on App Store</button>
            </div>
        </div>
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
