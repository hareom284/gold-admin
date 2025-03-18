<div id="season-card-wrapper" class="section-spacing-bottom px-0">
    <div class="d-flex my-4 justify-content-between align-items-center">
        <div>
           <h5>Episodes</h5>
        </div>
        <div>
            <div class="dropdown episode-dropdown season-tab">
                <button class="btn btn-dark dropdown-toggle" type="button" id="seasondropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" >
                  Select Season
                </button>
                <ul class="dropdown-menu" style="background: #171717;padding:0px;"  aria-labelledby="seasondropdownMenuButton"  id="season-tab" role="tablist">
                    @foreach ($data as $index => $item)
                        <li class="dropdown-item" onmouseover="this.style.backgroundColor='#353535';"
                        onmouseout="this.style.backgroundColor='#171717';" style="border-radius: 0px"
                       role="presentation">
                            <a  class="text-white {{ $index == 0 ? 'active' : '' }} cursor-pointer season-tab-link"
                                    id="season-{{ $index + 1 }}"
                                    name="Season {{ (int)$index + 1 }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#season-{{ $index + 1 }}-pane"

                                    aria-controls="season-{{ $index + 1 }}-pane"
                                    aria-selected="{{ $index == 0 ? 'true' : 'false' }}">
                                Season {{ $index + 1 }}
                            </a>
                        </li>
                    @endforeach
                </ul>
              </div>
        </div>
    </div>

    <div class="seasons-tabs-wrapper position-relative">
        <div class="tab-content" id="season-tab-content">
            @foreach($data as $index => $value)
                <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"

                        id="season-{{ (int)$index + 1 }}-pane"

                        aria-labelledby="season-{{ (int)$index + 1 }}"
                        tabindex="0">

                    <ul id="episode-list-{{ $value['season_id'] }}" class="list-inline m-0 p-0 d-flex flex-column gap-4 episode-list">
                        <div class="accordion" id="accordionExample">
                        @foreach($value['episodes']->toArray(request()) as $episodeIndex => $episode)
                        <div class="accordion" id="accordionExample">

                                {{-- @include('frontend::components.card.card_episode', ['data' => $episode, 'index' => $episodeIndex]) --}}
                                @include('frontend::components.card.card_accrodion', [ 'data' => $episode, 'index' => $episodeIndex])
                        </div>
                        @endforeach
                        </div>
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let initialSeason = document.querySelectorAll('.season-tab-link')[0]?.getAttribute('name');
    document.getElementById('seasondropdownMenuButton').innerText = initialSeason;
    document.querySelectorAll('.season-tab-link').forEach(function(link) {
    link.addEventListener('click', function(event) {
       document.getElementById('seasondropdownMenuButton').innerText = event.target.name;
    });
})
});
</script>

