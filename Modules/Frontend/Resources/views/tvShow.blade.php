@extends('frontend::layouts.master')

@section('content')
<style>
    #pagination-container {
    margin-top: 2rem;
    padding: 1rem 0;
    }

    .pagination .page-item.active .page-link {
        background-color: #59555a;
        border-color: #D4CCB3;
        color: white;
    }

    .pagination .page-link {
        color: #D4CCB3;
        margin: 0 2px;
    }

</style>
<div class="list-page">
    <div class="page-title" id="page_title">
        <h4 class="m-0 text-center">{{__('frontend.tvshows')}}</h4>
    </div>
    <div class="movie-lists section-spacing-bottom">
        <div class="container-fluid">
            <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6" id="entertainment-list">

            </div>
            <div class="card-style-slider shimmer-container">
                <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 mt-3">
                        @for ($i = 0; $i < 12; $i++)
                        <div class="shimmer-container col mb-3">
                            @include('components.card_shimmer_movieList')
                        </div>
                        @endfor
                </div>
            </div>
        </div>
        <div id="pagination-container" class="d-none">
            <!-- Pagination links will be inserted here -->
        </div>
    </div>
</div>

<script src="{{ asset('js/entertainment.min.js') }}" defer></script>

{{-- <script>

    const noDataImageSrc = '{{ asset('img/NoData.png') }}';
    const shimmerContainer = document.querySelector('.shimmer-container');
    const EntertainmentList = document.getElementById('entertainment-list');
    const pageTitle = document.getElementById('page_title');
    let currentPage = 1;
    let isLoading = false;
    let hasMore = true;
    let movie_id= null;
    let actor_id= null;
    let type=null;
    let per_page=12;

    const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');

    const apiUrl = `${baseUrl}/api/tvshow-list`;
    const csrf_token='{{ csrf_token() }}'
</script> --}}

<script>
    const noDataImageSrc = '{{ asset('img/NoData.png') }}';
    const envURL = document.querySelector('meta[name="baseUrl"]').getAttribute('content');
    const shimmerContainer = document.querySelector('.shimmer-container');
    const EntertainmentList = document.getElementById('entertainment-list');
    const pageTitle = document.getElementById('page_title');
    const paginationContainer = document.getElementById('pagination-container');
    let currentPage = 1;
    let isLoading = false;
    let hasMore = true;
    const per_page = 12;
    const csrf_token = '{{ csrf_token() }}';
    const language = "{{ $language ?? '' }}";
    const genreId = "{{ $genre_id ?? '' }}"; // Get genre_id from the Blade template
    const threshold = 31; // Threshold for switching to pagination

    // Initialize the API URL
    let apiUrl = `${envURL}/api/movie-list?page=${currentPage}&is_ajax=1&per_page=${per_page}`;

    // Add query parameters only if they exist
    if (language) {
        apiUrl += `&language=${language}`;
    }
    if (genreId) {
        apiUrl += `&genre_id=${genreId}`;
    }

    const showNoDataImage = () => {
        shimmerContainer.innerHTML = '';
        const noDataImage = document.createElement('img');
        noDataImage.src = noDataImageSrc;
        noDataImage.alt = 'No Data Found';
        noDataImage.style.display = 'block';
        noDataImage.style.margin = '0 auto';
        shimmerContainer.appendChild(noDataImage);
    };

    const loadData = async () => {
        if (!hasMore || isLoading) return;

        isLoading = true;
        shimmerContainer.style.display = '';
        try {
            const response = await fetch(`${apiUrl}&page=${currentPage}`);
            const data = await response.json();

            if (data?.html) {
                EntertainmentList.insertAdjacentHTML(currentPage === 1 ? 'afterbegin' : 'beforeend', data.html);
                hasMore = !!data.hasMore;
                if (hasMore) currentPage++;
                shimmerContainer.style.display = 'none';  // Hide shimmer container
                initializeWatchlistButtons();

                console.log(data.totalItems);
                // Switch to pagination if below threshold
                if (data.totalItems > threshold) {
                    hasMore = false;
                    renderPagination(data.totalItems);
                    window.removeEventListener('scroll', handleScroll);
                } else {
                    if (data.hasMore) currentPage++;
                }
            } else {
                showNoDataImage();
            }
        } catch (error) {
            console.error('Fetch error:', error);
            showNoDataImage();
        } finally {
            isLoading = false;
        }
    };

    // const renderPagination = (totalItems) => {
    //     const totalPages = Math.ceil(totalItems / per_page);
    //     paginationContainer.innerHTML = '';

    //     if (totalPages > 1) {
    //         let paginationHTML = `
    //             <nav aria-label="Page navigation">
    //                 <ul class="pagination justify-content-center">
    //                     <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
    //                         <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
    //                             <span aria-hidden="true">&laquo; Previous</span>
    //                         </a>
    //                     </li>`;

    //         for (let i = 1; i <= totalPages; i++) {
    //             paginationHTML += `
    //                 <li class="page-item ${i === currentPage ? 'active' : ''}">
    //                     <a class="page-link" href="#" data-page="${i}">${i}</a>
    //                 </li>`;
    //         }

    //         paginationHTML += `
    //                     <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
    //                         <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
    //                             <span aria-hidden="true">Next &raquo;</span>
    //                         </a>
    //                     </li>
    //                 </ul>
    //             </nav>`;
    //         paginationContainer.innerHTML = paginationHTML;
    //         paginationContainer.classList.remove('d-none');

    //         // Add pagination event listeners
    //         paginationContainer.querySelectorAll('.page-link').forEach(link => {
    //             link.addEventListener('click', (e) => {
    //                 e.preventDefault();
    //                 const page = parseInt(e.target.dataset.page);
    //                 if (page !== currentPage && page >= 1 && page <= totalPages) {
    //                     currentPage = page;
    //                     loadData();
    //                 }
    //             });
    //         });
    //     }
    // };

    const renderPagination = (totalItems) => {
        const totalPages = Math.ceil(totalItems / per_page);
        paginationContainer.innerHTML = '';

        if (totalPages > 1) {
            let paginationHTML = `
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                            <a class="page-link" id="prev-page-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
                                <span aria-hidden="true">&laquo; Previous</span>
                            </a>
                        </li>`;

            for (let i = 1; i <= totalPages; i++) {
                paginationHTML += `
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>`;
            }

            paginationHTML += `
                        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                            <a class="page-link" id="next-page-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
                                <span aria-hidden="true">Next &raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>`;
            paginationContainer.innerHTML = paginationHTML;
            paginationContainer.classList.remove('d-none');

            $('#prev-page-link').on('click',function(e){
                e.preventDefault();
                const page = parseInt(this.getAttribute('data-page'));

                if (page !== currentPage && page >= 1 && page <= totalPages) {
                    currentPage = page;
                    loadPageData(page);
                }
            });

            $('#next-page-link').on('click',function(e){
                e.preventDefault();
                const page = parseInt(this.getAttribute('data-page'));

                if (page !== currentPage && page >= 1 && page <= totalPages) {
                    currentPage = page;
                    loadPageData(page);
                }
            });

            // Add event listener for pagination clicks
            paginationContainer.querySelectorAll('.page-link').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const page = parseInt(e.target.dataset.page);

                    if (page !== currentPage && page >= 1 && page <= totalPages) {
                        currentPage = page;
                        loadPageData(page);
                    }
                });
            });
        }
    };

    // New function to load selected page data
    const loadPageData = async (page) => {
        isLoading = true;
        shimmerContainer.style.display = '';
        EntertainmentList.innerHTML = ''; // Clear old results

        try {
            const response = await fetch(`${envURL}/api/movie-list?page=${page}&is_ajax=1&per_page=${per_page}`);
            const data = await response.json();

            if (data?.html) {
                EntertainmentList.innerHTML = data.html;
                shimmerContainer.style.display = 'none';
                hasMore = !!data.hasMore;
                renderPagination(data.totalItems); // Re-render pagination with updated active page
                initializeWatchlistButtons();
            } else {
                showNoDataImage();
            }
        } catch (error) {
            console.error('Fetch error:', error);
            showNoDataImage();
        } finally {
            isLoading = false;
        }
    };

    const handleScroll = () => {
        if (hasMore && window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) {
            loadData();
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        loadData(); // Load the first page of movies
        if (hasMore) {
            window.addEventListener('scroll', handleScroll); // Attach scroll listener
        }
        initializeWatchlistButtons();
    });

    function initializeWatchlistButtons() {
        const watchList = typeof isWatchList !== 'undefined' ? !!emptyWatchList : null;
        const watchListPresent = typeof emptyWatchList !== 'undefined' ? !!emptyWatchList : null;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        $('.watch-list-btn').off('click').on('click', function () {
            var $this = $(this);
            var isInWatchlist = $this.data('in-watchlist');
            var entertainmentId = $this.data('entertainment-id');
            const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');
            var entertainmentType = $this.data('entertainment-type'); // Get the type
            let action = isInWatchlist == '1' ? 'delete' : 'save';
            var data = isInWatchlist
                ? { id: [entertainmentId], _token: csrf_token }
                : { entertainment_id: entertainmentId, type: entertainmentType, _token: csrfToken };

            // Perform the AJAX request
            $.ajax({
                url: action === 'save' ? `${baseUrl}/api/save-watchlist` : `${baseUrl}/api/delete-watchlist?is_ajax=1`,
                method: 'POST',
                data: data,
                success: function (response) {
                    window.successSnackbar(response.message)
                    $this.find('i').toggleClass('ph-check ph-plus');
                    $this.toggleClass('btn-primary btn-dark');
                    $this.data('in-watchlist', !isInWatchlist);
                    var newInWatchlist = !isInWatchlist ? 'true' : 'false';
                    var newTooltip = newInWatchlist === 'true' ? 'Remove Watchlist' : 'Add Watchlist';

                    // Destroy the current tooltip
                    $this.tooltip('dispose');

                    // Update the tooltip attribute
                    $this.attr('data-bs-title', newTooltip);

                    // Reinitialize the tooltip
                    $this.tooltip();
                    if (action !== 'save' && watchList) {
                        $this.closest('.iq-card').remove();
                        if (EntertainmentList.children.length === 0) {
                            if (watchListPresent) {
                                emptyWatchList.style.display = '';
                                const noDataImage = document.createElement('img');
                                noDataImage.src = noDataImageSrc;
                                noDataImage.alt = 'No Data Found';
                                noDataImage.style.display = 'block';
                                noDataImage.style.margin = '0 auto';
                                emptyWatchList.appendChild(noDataImage);
                            }
                        }
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 401) {
                        window.location.href = `${baseUrl}/login`;
                    } else {
                        console.error(xhr);
                    }
                }
            });
        });
    }
</script>
@endsection
