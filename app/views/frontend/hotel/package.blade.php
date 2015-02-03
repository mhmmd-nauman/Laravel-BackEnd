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
        <div class="col-md-2">
            <nav class="package-list">
                <a href="{{ URL::route('frontend.hotel.packages', array('name' => $hotel->hotel_slug)) }}" class="package-list-item">
                    <span><i class="list-item-back" data-icon="&#xe045;"></i> {{ Lang::get('package.all_packages') }}</span>
                </a>

                @foreach($hotel_packages as $package)
                <a href="{{ URL::route('frontend.hotel.package', array('name' => $package->hotel_slug, 'package_slug' => $package->package_slug)) }}" class="package-list-item @if ( $package->package_id == $hotel->package_id ) is-current @endif">
                    <span>{{ $package->package_name }}</span>
                </a>
                @endforeach
            </nav>
        </div>

        <div class="col-md-7 package-content-bg">
            <div class="package-content">
                <div class="package-header">
                    <img src="{{ URL::route('frontend.image.photo', array( 'size' => 'large_cropped', 'id' => $hotel->package_photo_id )) }}" alt="{{ $hotel->package_name }}" class="package-header-photo img-responsive">
                    <h1 class="package-header-title">{{ $hotel->package_name }}</h1>
                </div>

                <div class="package-description">
                    <p>{{ $hotel->package_description }}</p>
                </div>

                @if ( $package_includes )
                <div class="package-includes">
                    <h3 class="includes-title">{{ Lang::get('package.title_package_includes') }}</h3>
                    <ul class="includes-list">
                    @foreach($package_includes as $package_include)
                        @if ( $package_include )
                        <li>{{ $package_include }}</li>
                        @endif
                    @endforeach
                    </ul>
                </div>
                @endif
                <div class="package-additions">
                    @include('frontend._partials.value_propositions')
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div id="package-book" class="package-book">
                <h2 class="package-book-title">{{ Lang::get('package.book_title') }}</h2>

                {{ Form::open(array('url' => URL::route('frontend.hotel.booking', array('name' => $package->hotel_slug, 'package_slug' => $package->package_slug)) , 'method' => 'POST', 'id' => 'package-form', 'class' => 'package-book-form')) }}
                    <div class="package-book-fields">
                        <div class="form-group">
                            <label for="date-from">{{ Lang::get('package.book_from') }}</label>
                            <div class="package-book-field">
                                @if($available_weekdays)
                                <input type="text" id="package-date-from" class="form-control input-lg" @if ( $package->days_in_advance ) data-zdp_direction="{{ $package->days_in_advance }}" @endif data-available-days="{{ $hotel->overnights_min }}:{{ $hotel->overnights_max }}" data-zdp_disabled_dates='["* * * {{ implode(',', $available_weekdays) }}"]' @if ( $date_from ) value="{{ $date_from }}" @endif >
                                @else
                                <input type="text" id="package-date-from" class="form-control input-lg" @if ( $package->days_in_advance ) data-zdp_direction="{{ $package->days_in_advance }}" @endif data-available-days="{{ $hotel->overnights_min }}:{{ $hotel->overnights_max }}" @if ( $date_from ) value="{{ $date_from }}" @endif>
                                @endif
                                <i class="book-date-icon" data-icon="&#xe023;"></i>
                            </div>

                            {{ Form::hidden('from', $formatted_date_from, array('id' => 'package-date-from-hidden') ) }}
                        </div>
                        <div class="form-group">
                            <label for="date-till">{{ Lang::get('package.book_till') }}</label>
                            <div class="package-book-field">
                                <input type="text" id="package-date-till" class="form-control input-lg" value="{{ Str::limit($date_to, 5, '') }}">
                                <i class="book-date-icon" data-icon="&#xe023;"></i>
                            </div>
                            {{ Form::hidden('to', $formatted_date_to, array('id' => 'package-date-till-hidden') ) }}
                        </div>
                        <div class="form-group">
                            <label for="package-persons">{{ Lang::get('package.book_guests') }}</label>
                            {{ Form::select('persons', $package_persons, NULL, array('id' => 'package-persons')) }}
                        </div>

                    </div>
                    <span id="package-book-err" class="package-book-err">{{ Lang::get('package.book_err_msg') }}</span>

                    <div class="package-book-price">
                        <h4 class="price-label">{{ Lang::get('package.total_price') }}</h4>
                        <h3 class="price-value"><span id="package-price">{{ ($package_price * reset($package_persons)) * $nights_count }}</span> SEK</h3>
                        <input type="hidden" id="package-base-price" value="{{ $package_price }}">
                        <p class="price-notes">Allt inkluderat. <br> Inga extra avgifter tillkommer.</p>
                    </div>

                    <div class="package-book-submit">
                        <button type="submit" id="package-form-submit" class="btn btn-primary btn-lg">{{ Lang::get('package.book_button') }}</button>
                    </div>
                    {{ Form::hidden('package_id', $hotel->package_id) }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@stop

@section('footer')
<script> var calendarData = {{ $js_calendar_data or "'undefined'" }} </script>
@stop