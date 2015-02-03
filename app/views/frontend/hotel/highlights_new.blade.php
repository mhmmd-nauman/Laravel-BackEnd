@extends ('frontend._layouts.default')

@section('header')
    @include('frontend._partials.header_menu')
@stop

@section('secondary_header')
    @include('frontend._partials.secondary_header')
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <hgroup class="overview-title">
                <h1 class="overview-name">{{ $hotel->hotel_name }}</h1>
                <h3 class="overview-place">
                    <a href="{{ URL::route('frontend.hotel.map', array('name' => $hotel->hotel_slug)) }}" id="overview-map-open" class="place-link"><i class="place-mark" data-icon="&#xe081;"></i> {{ $hotel->address }}</a>
                </h3>
            </hgroup>
        </div>
    </div>

    <section class="overview-summary">
        <div class="row">
            <div class="col-md-8">
                <div class="overview-box">
                    <ul class="box-tabs" role="tablist">
                        <li class="active"><a href="#overview-images" role="tab" data-toggle="tab">{{ Lang::get('highlights.images') }}</a></li>
                        <li><a href="#overview-map" role="tab" data-toggle="tab">{{ Lang::get('highlights.map') }}</a></li>
                    </ul>
                    <div class="box-content -ratio-3x2 tab-content">
                        @if ( $gallery && !empty($gallery['big']) )
                        <div id="overview-images" class="box-item tab-pane active">
                            <div id="overview-carousel" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="item active">
                                        <img src="{{ $gallery['slider'][0]['src'] }}" alt="">
                                    </div>
                                    <?php unset($gallery['slider'][0]) ?>
                                    @foreach($gallery['slider'] as $photo)
                                    <div class="item">
                                        <img src="{{ $photo['src'] }}" alt="">
                                    </div>
                                    @endforeach
                                </div>

                                <a class="left carousel-control" href="#overview-carousel" role="button" data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left"></span>
                                </a>
                                <a class="right carousel-control" href="#overview-carousel" role="button" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right"></span>
                                </a>
                            </div>
                        </div>
                        @endif
                        <div id="overview-map" class="box-item tab-pane">
                            <div id="js-location" class="box-map"></div>
                        </div>
                    </div>
                </div>

                <div class="overview-box">
                    <ul class="box-tabs" role="tablist">
                        <li class="active"><a href="#overview-hotel" role="tab" data-toggle="tab">Hotel</a></li>
                        <li><a href="#overview-spa" role="tab" data-toggle="tab">Spa</a></li>
                    </ul>

                    <div class="box-content tab-content">
                        <div id="overview-hotel" class="box-item tab-pane active">
                            <div class="row">
                                <div class="col-sm-8">
                                    <p class="box-descr">{{ mb_substr(strip_tags($hotel->hotel_description), 0, 200) . '...' }}</p>
                                </div>
                                @if( $hotel_services->count() )
                                <div class="col-sm-4">
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
                            <div class="row">
                                <div class="col-sm-12">
                                    <a href="{{ URL::route('frontend.hotel.hotel', array('name' => $hotel->hotel_slug)) }}" class="box-more btn btn-default btn-lg">{{ Lang::get('highlights.hotel_read_more_btn') }}</a>
                                </div>
                            </div>
                        </div>
                        <div id="overview-spa" class="box-item tab-pane">
                            <p class="box-descr">{{ $hotel->spa_description }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="overview-dates-wrap">
                    <div id="overview-dates" class="overview-dates overview-box">
                        <div class="overview-price">
                            <h4 class="overview-price-label">Pris från</h4>
                            <h3 class="overview-price-value">{{ $hotel->min_package_price }} SEK/pers</h3>
                        </div>

                        {{ Form::open(array('url' => URL::route('frontend.hotel.packages', array('name' => $hotel->hotel_slug)) , 'method' => 'GET', 'class' => 'overview-dates-form', 'id' => 'packages-check-form')) }}
                            <div class="overview-dates-fields">
                                <div class="form-group">
                                    <label for="overview-date-from">Från</label>
                                    <div class="overview-dates-field">
                                        <input type="text" name="date_from" id="overview-date-from" class="form-control input-lg">
                                        <i class="field-date-icon" data-icon="&#xe023;"></i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="overview-date-till">Till</label>
                                    <div class="overview-dates-field">
                                        <input type="text" name="date_to" id="overview-date-till" class="form-control input-lg">
                                        <i class="field-date-icon" data-icon="&#xe023;"></i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="overview-guests">Gäster</label>
                                    <select id="overview-guests">
                                        <option value="2">2</option>
                                        <option value="4">4</option>
                                        <option value="6">6</option>
                                        <option value="8">8</option>
                                    </select>
                                </div>
                                <input type="hidden" value="{{ $hotel->hotel_id }}" name="hotel_id" id="overview-hotel-id"/>
                            </div>
                            <div class="text-center">
                                <button class="overview-dates-submit btn btn-primary btn-lg">{{ Lang::get('highlights.show_availability_btn') }}</button>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>

                @if ( $highlights->count() && $highlights[0]->quote_text && $highlights[0]->default_quote_photo_id )
                    <div id="overview-review" class="overview-review">
                        <img src="{{ URL::route('frontend.image.photo', array( 'size' => 'small', 'id' => $highlights[0]->default_quote_photo_id )) }}" alt="" class="review-photo">
                        <div class="review-descr">
                            <p class="review-text">{{ $highlights[0]->quote_text }}</p>
                            <span class="review-sign">{{ $highlights[0]->quote_author }}</span>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>
</div>

<section class="overview-tour">
    <h2 class="overview-tour-title"><span class="tour-title-text">En Rundtur</span></h2>

    <div class="container">
        <?php $i = 0; ?>
        @foreach($highlights as $highlight)
        <?php $i++; ?>
        <div class="overview-tour-block">
            <div class="row">
                @if ( $highlight->default_photo_id && $i%2 != 0 )
                <div class="col-sm-6">
                    <img src="{{ URL::route('frontend.image.photo', array( 'size' => 'large', 'id' => $highlight->default_photo_id )) }}" alt="{{ $highlight->name }}" class="img-responsive">
                </div>
                @endif
                <div class="col-sm-6 @if( $i%2 == 0 ) text-right @endif">
                    <h3 class="tour-block-title">{{ $highlight->name }}</h3>
                    <h4 class="tour-block-subtitle hidden"></h4>
                    <p class="tour-block-descr">{{ $highlight->description }}</p>
                </div>
                @if ( $highlight->default_photo_id && $i%2 == 0 )
                <div class="col-sm-6">
                    <img src="{{ URL::route('frontend.image.photo', array( 'size' => 'large', 'id' => $highlight->default_photo_id )) }}" alt="{{ $highlight->name }}" class="img-responsive">
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</section>


@stop

@section('footer')
<script type="text/javascript">window.gm_data = {{ $js_hotels }}</script>
@stop