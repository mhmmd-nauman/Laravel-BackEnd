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
            <div class="packages-header">
                <h2 class="packages-header-title">{{ Lang::get('packages.packages_title_h1') }}</h2>
                <h3 class="packages-header-subtitle">{{ Lang::get('packages.packages_subtitle') }}</h3>
            </div>

            {{ Form::open( array('url' => URL::route('frontend.hotel.packages', array('name' => $hotel->hotel_slug)) ,'method' => 'GET', 'class' => 'packages-filters js-package-filters-form') ) }}
                <div class="packages-filters-list">
                    <div class="packages-filters-item">
                        <span class="packages-filters-date">
                            <span id="date-from-calendar-txt">@if ( Input::get('date_from') ) {{ Input::get('date_from') }} @else {{ Lang::get('booking.date_from') }} @endif</span> <i class="date-icon" data-icon="&#xe023;"></i>
                            <input type="text" id="date-from" name="date_from" class="date-field" @if ( Input::get('date_from') && $filter_date_from ) value="{{ date('d/m - Y', strtotime($filter_date_from)) }}" @endif data-zdp_disabled_dates='false'>
                        </span>
                    </div>
                    <div class="packages-filters-item">
                        <span class="packages-filters-date" id="date-to-calendar">
                            <span id="date-to-calendar-txt">@if ( Input::get('date_to') && $filter_date_to ) {{ Input::get('date_to') }} @else {{ Lang::get('booking.date_to') }} @endif</span> <i class="date-icon" data-icon="&#xe023;"></i>
                            <input type="text" id="date-till" name="date_to" class="date-field" @if ( Input::get('date_to') && $filter_date_to ) value="{{ date('d/m - Y', strtotime($filter_date_to)) }}" @endif data-zdp_direction='{{ $calendar_date_str }}' data-zdp_disabled_dates='false'>
                        </span>
                    </div>
                    <div class="packages-filters-item">
                        {{ Form::select('persons', $packages_persons, Input::get('persons', 2), array('class' => 'packages-filters-nights js-filter-packages-persons')) }}
                    </div>
                </div>
                <p class="packages-filters-text">{{ $packages->count() }} {{ Lang::choice('packages.packages', $packages->count()) }} 
                @if ( Input::get('persons') || Input::get('date_from') || Input::get('date_to'))
                    - <a href="{{ URL::route('frontend.hotel.packages', array('name' => $hotel->hotel_slug, 'clear_filters' => 'true')) }}">{{Lang::get('packages.reset_filters')}}</a>
                @endif</p>
            {{ Form::close() }}

            <section class="packages-content">
                <div class="row">
                    @foreach($packages as $package)
                    <div class="col-lg-3 col-md-4 col-sm-6 js-package-id{{ $package->package_id }}">
                        <div class="box @if(in_array($package->package_id, $disabled_packages)) is-disabled @endif" style="background-image: url({{ URL::route('frontend.image.photo', array( 'size' => 'medium', 'id' => $package->package_photo_id )) }})">
                            <div class="box-info">
                                <h3 class="box-title">{{ $package->package_name }}</h3>
                                <div class="box-panel">
                                    <p class="box-note"><i>{{ $package->night_span }}. {{ $package->available_weekdays }}</i></p>
                                    <p class="box-descr">{{ $package->short_description }}</p>
                                    <h4 class="box-price">Fr. {{ $package->package_price }} SEK/natt</h4>
                                </div>
                            </div>
                            @if(in_array($package->package_id, $disabled_packages))
                            <span class="box-overlay"></span>
                            <span class="box-book btn btn-primary btn-sm">{{ Lang::get('booking.book') }}</span>
                            @else
                            <a href="{{ URL::route('frontend.hotel.package', array('name' => $package->hotel_slug, 'package_slug' => $package->package_slug)) }}" class="box-link"></a>
                            <a href="{{ URL::route('frontend.hotel.package', array('name' => $package->hotel_slug, 'package_slug' => $package->package_slug)) }}" class="box-book btn btn-primary btn-sm">{{ Lang::get('booking.book') }}</a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
</div>
@stop

@section('footer')
@stop