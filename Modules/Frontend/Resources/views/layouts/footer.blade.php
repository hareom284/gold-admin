@php
$footerData = getFooterData();
@endphp

<footer class="footer">
  <div class="footer-top">
    <div class="container-fluid py-lg-5 py-3">
      <div class="row justify-content-center">
        {{-- Logo Section --}}
        <div class="col-12 d-flex flex-column flex-lg-row justify-content-center align-items-center mb-lg-5 mb-4">
          <div class="d-flex align-items-center mb-3 mb-lg-0">
            <a class="navbar-brand" href="{{route('home')}}">
              <img class="img-fluid logo" src="{{ asset(setting('dark_logo')) }}"
                   alt="Gold Channel" style="max-height: 50px;">
            </a>
          </div>
        </div>

            <div class="col-12 col-lg-8 text-center mb-lg-5 mb-4">
              <h2 class="fw-bold fs-6 fs-md-5 fs-lg-4 mb-3">Download Our App</h2>
              <p class="text-gray-300 fs-6 fs-lg-5 lh-lg mb-4">
                Enjoy instant access to the best movies and TV shows right on your mobile screen.
              </p>

              <div class="mt-3 mb-1 d-flex justify-content-center gap-md-3 gap-1">
                  <button class="btn btn-outline-light btn-store">
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
                    <span class="btn-store-text ms-2">Get on Google Play</span>
                  </button>

                  <button class="btn btn-outline-light btn-store d-flex align-items-center">
                    <i class="ph-fill ph-apple-logo fs-5"></i>
                    <span class="btn-store-text ms-2">Get on App Store</span>
                  </button>
              </div>
          </div>
  </div>
  <div class="footer-bottom mt-5 border-top  mx-5">
    <div class="container-fluid">
      <div class="text-center fs-6" style="font-weight: 400;line-height:29px;">
        © {{ now()->year }} <span >Gold Channel</span>. {{__('frontend.all_rights_reserved')}}.
      </div>
    </div>
  </div>
</footer>

<style>
  .footer {
    background: #1a1a1a;
    color: #ffffff;
  }

  .text-gray-300 {
    color: #BEBEBE;
  }

  .btn-outline-light {
    border-color: rgba(255,255,255,0.3);
    transition: all 0.3s ease;
  }

  .btn-outline-light:hover {
    background: rgba(255,255,255,0.1);
    border-color: white;
  }

  @media (max-width: 473px) {
    .logo {
      max-height: 40px !important;
    }

    .fs-lg-2 {
      font-size: 1.4rem !important;
    }

    .btn {
      width: 100%;
      justify-content: center;
    }

    .btn-store-text {
      display: none !important;
    }

    .btn-store {
      padding: 8px 12px !important;
    }

    .btn-store svg,
    .btn-store i {
      width: 24px;
      height: 24px;
      margin: 0 !important;
    }

    .btn-store {
      padding: 10px 24px;
    }
  }
</style>

<!-- Sticky Footer -->
@include('frontend::components.partials.footer-sticky-menu')
