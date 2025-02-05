<div class="row">
    <div class="container">
        <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($ads_banner as $slider )
                <div class="carousel-item active">
                    <a href="{{$slider['link']}}">
                        <img src="{{setBaseUrlWithFileName($slider['file_url'])}}" class="d-block w-100" alt="...">
                    </a>
                  </div>
                @endforeach
            </div>
          </div>
    </div>
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
