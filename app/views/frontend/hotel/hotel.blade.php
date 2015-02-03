@extends ('frontend._layouts.default')

@section('header')
    @include('frontend._partials.header_menu')
@stop

@section('secondary_header')
    @include('frontend._partials.secondary_header')
@stop

@section('content')
<div class="container">
    <div class="content-bg">
    <div class="row">
        <div class="col-md-12">
            <div class="hotel-head">
                <h1 class="hotel-head-title">{{ Lang::get('hotel.hotel_title_h1') }}</h1>
            </div>
        </div>
    </div>

    <section id="hotel-general" class="hotel-section">
        <div class="row">
            <div class="col-md-3 col-border-right">
                <div class="hotel-section-name">
                    <h3 class="name-title">{{ Lang::get('hotel.section_general_title') }}</h3>
                </div>
            </div>

            <div class="col-md-5">
                <div class="hotel-section-info">
                    <p>{{ $hotel->hotel_description }}</p>

                    <div class="info-labels hidden">
                        <span class="info-label">vituperata</span>
                        <span class="info-label">porro </span>
                        <span class="info-label">omittantur</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-border-left">
                <div class="hidden">
                    <div class="hotel-section-row">
                        <div class="hotel-toplist">
                            <h4 class="toplist-title">Topp 5</h4>
                            <ol class="toplist-items">
                                <li>Ne sed purto singulis maiestatis, eu meis graecis pro, an solum facilisis has.</li>
                                <li>Eu meis graecis pro, an solum facilisis has.</li>
                                <li>Usu cu everti vulputate quaerendum</li>
                                <li>Vaniljglass</li>
                                <li>Dolore appellantur ea cum, eros quaerendum.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                @if( $hotel_services->count() )
                <div class="hotel-section-row">
                    <div class="hotel-checklist">
                        <h4 class="checklist-title">{{ Lang::get('hotel.section_services_title') }}</h4>
                        <ul class="checklist-items">
                            @foreach($hotel_services as $service)
                            <li><strong>{{ $service->name }}</strong></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    @if ( $hotel_rooms->count() )
    <section id="hotel-rooms" class="hotel-section">
        <div class="row">
            <div class="col-md-3 col-border-right">
                <div class="hotel-section-name">
                    <h3 class="name-title">{{ Lang::get('hotel.section_rooms_title') }}</h3>
                    <p class="name-descr hidden">Lorem ipsum dolor sit amet, te per assentior forensibus, argumentum disputando ei sed? Id discere convenire sententiae duo, sit suas simul luptatum at, principes dissentias dissentiunt et pro? Mea et aperiri aliquando, iisque splendide ex vis, ea cum verear habemus phaedrum.</p>
                </div>
            </div>

            <div class="col-md-9">
                @foreach($hotel_rooms as $room)
                <div class="hotel-section-row">
                    <div class="row">
                        @if($room->default_photo_id)
                        <div class="col-md-3">
                            <img src="{{ URL::route('frontend.image.photo', array( 'size' => 'small', 'id' => $room->default_photo_id )) }}" alt="{{ $room->name }}" class="img-responsive">
                        </div>
                        @endif
                        <div class="col-md-9">
                            <h4 class="row-title">{{ $room->name }}</h4>
                            <div class="row-descr">
                                <p>{{ $room->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if ( $hotel_restaurants->count() )
    <section id="hotel-restuarants" class="hotel-section">
        <div class="row">
            <div class="col-md-3 col-border-right">
                <div class="hotel-section-name">
                    <h3 class="name-title">{{ Lang::get('hotel.section_restaurants_title') }}</h3>
                </div>
            </div>

            <div class="col-md-9">
                <?php $i = 0; ?>
                @foreach($hotel_restaurants as $restaurant)
                <?php $i++; ?>
                <div class="hotel-section-row">
                    <div class="row">
                        @if ( $restaurant->default_photo_id && $i%2 != 0 )
                        <div class="col-md-3">
                            <img src="{{ URL::route('frontend.image.photo', array( 'size' => 'small', 'id' => $restaurant->default_photo_id )) }}" alt="{{ $restaurant->name }}" class="img-responsive">
                        </div>
                        @endif
                        <div class="col-md-9">
                            <h4 class="row-title">{{ $restaurant->name }}</h4>
                            <div class="row-descr">
                                <p>{{ $restaurant->description }}</p>
                            </div>
                        </div>
                        @if ( $restaurant->default_photo_id && $i%2 == 0 )
                        <div class="col-md-3">
                            <img src="{{ URL::route('frontend.image.photo', array( 'size' => 'small', 'id' => $restaurant->default_photo_id )) }}" alt="{{ $restaurant->name }}" class="img-responsive">
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if ( $hotel_activities->count() )
    <section id="hotel-facil-activities" class="hotel-section">
        <div class="row">
            <div class="col-md-3 col-border-right">
                <div class="hotel-section-name">
                    <h3 class="name-title">{{ Lang::get('hotel.section_activities_title') }}</h3>
                </div>
            </div>

            <div class="col-md-9">
                @foreach($hotel_activities as $activity)
                <div class="hotel-section-row">
                    <div class="row">
                        @if ( $activity->default_photo_id )
                        <div class="col-md-3">
                            <img src="{{ URL::route('frontend.image.photo', array( 'size' => 'small', 'id' => $activity->default_photo_id )) }}" alt="{{ $activity->name }}" class="img-responsive">
                        </div>
                        @endif
                        <div class="col-md-9">
                            <h4 class="row-title">{{ $activity->name }}</h4>
                            <div class="row-descr">
                                <p>{{ $activity->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section id="hotel-directions" class="hotel-section">
        <div class="row">
            <div class="col-md-3 col-border-right">
                <div class="hotel-section-name">
                    <h3 class="name-title">{{ Lang::get('hotel.section_directions_title') }}</h3>
                </div>
            </div>

            <div class="col-md-9">
                <div class="hotel-section-map">
                    <div class="row">
                        <div class="col-md-8">
                            <div id="js-location" class="map-box"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="map-descr">
                                <p class="map-address">{{ $hotel->address }}</p>
                                <p class="hidden">Id cum idque epicuri expetendis! Ex quando regione nec, has summo suavitate referrentur te, alienum commune adipiscing vel ne. Vim te assum comprehensam. Te vivendum accommodare comprehensam mei, usu cu everti vulputate quaerendum, vix menandri senserit ea. Agam solum deseruisse et mea, possit nostrum epicurei ut ius. Quaestio suscipiantur qui at, ea iudicabit reformidans duo.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
</div>
@stop

@section('footer')
<script type="text/javascript">window.gm_data = {{ $js_hotels }}</script>
@stop