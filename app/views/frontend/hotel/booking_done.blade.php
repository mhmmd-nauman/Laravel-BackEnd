@extends ('frontend._layouts.search')

@section('header')
    @include('frontend._partials.search.header_menu')
@stop

@section('secondary_header')
    @include('frontend._partials.search.secondary_header')
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="done-box">
                <h1>{{ Lang::get('booking_done.done_title_h1') }}</h1>
                <p>{{ Lang::get('booking_done.done_content_text') }}</p>
                <button class="btn btn-primary medium hidden">{{ Lang::get('booking_done.show_booking') }}</button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="extra-info col-md-10 col-md-offset-1">
            <h1>{{ Lang::get('booking_done.what_next') }}</h1>
            <div class="row">
                <div class="col-md-4">
                    <a href="https://www.facebook.com/myspason" style="text-decoration: none">
                        <div class="media-box adv-box facebook">
                            <div class="ask">
                                <p>{{ Lang::get('booking_done.give_us_some') }}</p>
                                <h2 class="elegant-icon"></h2>
                                <p>{{ Lang::get('booking_done.give_us_some_on') }}</p>
                                <h2 class="elegant-icon"></h2>
                            </div>
                            <p>{{ Lang::get('booking_done.or_follow') }}</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <div class="media-box adv-box feedback">
                        <div class="white-circle"><img src="/media/feedback-photo.jpg" alt="" width="138"></div>
                        <div class="bot-cont">
                            <h3>{{ Lang::get('booking_done.tell_us') }}</h3>
                            <a href="{{ URL::to('/kontakt') }}" class="btn btn-success medium">{{ Lang::get('booking_done.give_feedback') }}</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <a href="{{ URL::to('/magasin') }}" style="text-decoration: none">
                        <div class="media-box adv-box magazine">
                            <h2>{{ Lang::get('booking_done.pay_visit') }}</h2>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('footer')
@stop