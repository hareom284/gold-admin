<div class="slick-banner ads-banner" id="ads-banner" data-speed="1000" data-autoplay="true" data-center="false" data-infinite="false" data-navigation="true" data-pagination="true" data-spacing="0">

    @foreach($ads_banner as $slider)
      <div style="height: 100px;">
        <div class="slick-item" style="background-image: url({{ setBaseUrlWithFileName($slider['file_url']) }});">
            <div class="movie-content h-100">
              <div class="container-fluid h-100">

              </div>
            </div>
          </div>
      </div>
    @endforeach
  </div>
  @push('after-scripts')
      <script>
          document.addEventListener('DOMContentLoaded', function () {
      const playButtons = document.querySelectorAll('.play-now-btn');
      playButtons.forEach(button => {
          button.addEventListener('click', function (e) {
              e.preventDefault();
              const encryptedUrl = this.getAttribute('data-encrypted-url');

              if (encryptedUrl) {
                  fetch('{{ route('decrypt.url') }}', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': '{{ csrf_token() }}'
                      },
                      body: JSON.stringify({ encrypted_url: encryptedUrl })
                  })
                  .then(response => response.json())
                  .then(data => {
                      if (data.url) {
                          window.open(data.url, '_blank');
                      } else {
                          alert('Error: ' + data.error);
                      }
                  })
                  .catch(error => {
                      console.error('Error:', error);
                  });
              }
          });
      });
  });

      </script>
  @endpush
