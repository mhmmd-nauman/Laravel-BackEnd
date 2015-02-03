@extends ('frontend._layouts.search')

@section('header')
    @include('frontend._partials.search.header_menu')
@stop

@section('secondary_header')
    @include('frontend._partials.secondary_header')
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-bg">
                <div class="booking">
                    <h1 class="booking-title">{{ Lang::get('booking.booking') }}</h1>

                    <section class="booking-section">
                        <h2 class="booking-subtitle">{{ Lang::get('view_booking.summary') }}</h2>
                        <table class="booking-summary">
                            <thead>
                                <tr>
                                    <th colspan="2">{{ $hotel->hotel_name }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ Lang::get('booking.package') }}:</td>
                                    <td>{{ $package->name }} <a href="{{ URL::route('frontend.hotel.package', array('name' => $hotel->hotel_slug, 'package_name' => $package->slug)) }}">(ändra)</a></td>
                                </tr>
                                <!-- <tr>
                                    <td>{{Lang::choice('booking.rooms', 1)}}:</td>
                                    <td>Dubbelrum</td>
                                </tr> -->
                                <tr>
                                    <td>{{ Lang::get('booking.date_from') }}:</td>
                                    <td>{{ $date_from }}</td>
                                </tr>
                                <tr>
                                    <td>{{ Lang::get('booking.date_to') }}:</td>
                                    <td>{{ $date_to }}</td>
                                </tr>
                                <tr>
                                    <td>{{ Lang::choice('booking.nights',2) }}:</td>
                                    <td>{{ $nights_count }}</td>
                                </tr>
                                <tr>
                                    <td>{{ Lang::choice('booking.guests',2) }}:</td>
                                    <td><span id="discount-person">{{ $package_residents }}</span></td>
                                </tr>
                                <tr>
                                    <td>{{Lang::choice('booking.includes',2)}}</td>
                                    <td>{{ implode(', ', $package_includes) }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>{{ Lang::get('booking.discount_code') }}/{{ Lang::get('booking.giftcard') }}:</td>
                                    <td>
                                        <a href="#discount-popup" id="discount-add" data-toggle="modal">{{ Lang::get('view_booking.enter_discount_code') }}</a>
                                        <div id="discount-added" style="display: none;">
                                            <b id="discount-name"></b> <a href="#" id="discount-remove">{{ Lang::get('actions.remove') }}</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="discount-minus" class="summary-discount-minus">
                                    <td colspan="2" style="display: none;">-<span id="discount-minus-person"></span> SEK / person</td>
                                    <td colspan="2" style="display: none;">-<span id="discount-minus-package"></span> SEK</td>
                                </tr>
                                <tr>
                                    <td>{{Lang::get('booking.total_price')}}/{{strtolower(Lang::get('booking.guest'))}}: </td>
                                    <td><span id="discount-package-price">{{ $package_price }}</span> SEK</td>
                                </tr>
                                <tr id="discount-person-row" style="display: none;">
                                    <td>{{Lang::get('booking.discount_price')}}/{{strtolower(Lang::get('booking.guest'))}}:</td>
                                    <td><span id="discount-person-price"></span> SEK</td>
                                </tr>
                                <tr>
                                    <td>{{Lang::get('booking.total_price')}} ({{Lang::get('view_booking.all_fees_included')}}):</td>
                                    <td><span id="discount-total-price">{{ $full_price }}</span> SEK</td>
                                </tr>
                                <tr id="discount-package-row" style="display: none;">
                                    <td>{{Lang::get('booking.discount_total_price')}} ({{Lang::get('view_booking.all_fees_included')}}):</td>
                                    <td><span id="discount-package-price-min"></span> SEK</td>
                                </tr>
                            </tfoot>
                        </table>
                    </section>

                    <section class="booking-section">
                        <h2 class="booking-subtitle">{{ Lang::get('view_booking.section_contact_information_title') }}</h2>

                        {{ Form::open(array('url' => URL::route('frontend.hotel.order', array('name' => $hotel->hotel_slug, 'package_slug' => $hotel->package_slug)) , 'method' => 'POST', 'id' => 'booking-form', 'class' => 'booking-form')) }}
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="name">{{ Lang::get('user.name') }}</label>
                                        {{ Form::text('name', $user_name, array('class' => 'form-control')) }}
                                    </div>
                                    <div class="form-group">
                                        <label for="email">{{ Lang::get('user.email') }}</label>
                                        {{ Form::text('email', $user_email, array('class' => 'form-control')) }}
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">{{ Lang::get('user.phone') }}</label>
                                        {{ Form::text('phone', $user_phone, array('class' => 'form-control')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="message">{{ Lang::get('view_booking.section_contact_additional') }}</label>
                                        {{ Form::textarea('addition', '', array('class' => 'form-control', 'placeholder' => Lang::get('view_booking.section_contact_additional_placeholder'), 'rows' => 7)) }}
                                    </div>
                                    <div class="booking-form-submit">
                                        <button type="submit" class="btn btn-primary">{{ Lang::get('booking.book') }}</button>
                                        <p class="booking-form-tip">{{ Lang::get('view_booking.payment_instructions') }}</p>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="from" value="{{ $date_from }}">
                            <input type="hidden" name="to" value="{{ $date_to }}">
                            <input type="hidden" id="package-discount-hidden" name="discount">
                            <input type="hidden" id="package-id" name="package_id" value="{{ $package->id }}">
                        {{ Form::close() }}
                    </section>
                </div>

                <!--
                {{ Form::open(array('url' => URL::route('frontend.hotel.order', array('name' => $hotel->hotel_slug, 'package_slug' => $hotel->package_slug)) , 'method' => 'POST', 'class' => 'form-horizontal js-booking-form')) }}
                    <table class="table table-bordered booking-form">
                        <tr>
                            <th colspan="2">
                                <h1>{{Lang::get('view_booking.booking_title_h1') }}</h1>
                            </th>
                        </tr>
                        <tr>
                            <td><h2>{{Lang::get('view_booking.section_package_title') }}</h2></td>
                            <td>
                                <img src="{{ URL::route('frontend.image.photo', array( 'size' => 'small_booking', 'id' => $package->default_photo_id )) }}" alt="" class="descr-img">
                                    <h3>{{ $package->name }}</h3>
                                    {{ $package->short_description }}
                                    <p>{{Lang::get('view_booking.date_from') }}: {{ $date_from }}<br>{{Lang::get('view_booking.date_to') }}: {{ $date_to }}</p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><h2>{{Lang::get('view_booking.section_price_title') }}</h2></td>
                            <td class="price-wrap">
                                <table class="table summary">
                                    <tbody>
                                    <tr>
                                        <td class="price-value price">{{ $package_residents }} persons a {{ $package_price }} SEK/person = {{ $full_price }} SEK</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="form-inline">
                                    <div class="form-group col-md-7">
                                        <label style="display: block;">{{Lang::get('view_booking.section_discount') }}</label>
                                        {{ Form::text('discount', '', array('class' => 'form-control js-discount-code')) }}
                                        <a href="#" class="price-discount-btn btn btn-primary js-send-discount-code">{{Lang::get('view_booking.discount_send') }}</a>
                                        <div class="price-discount-res price js-discount-infobox"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><h2>{{Lang::get('view_booking.section_contact_information_title') }}</h2></td>
                            <td>
                                <div class="user-data-form">
                                        <fieldset>
                                            <div class="form-group col-md-7">
                                                <label>{{Lang::get('view_booking.section_contact_name') }}</label>
                                                {{ Form::text('name', $user_name, array('class' => 'form-control')) }}
                                            </div>
                                            <div class="form-group col-md-7">
                                                <label>{{Lang::get('view_booking.section_contact_email') }}</label>
                                                {{ Form::text('email', $user_email, array('class' => 'form-control')) }}
                                            </div>
                                            <div class="form-group col-md-7">
                                                <label>{{Lang::get('view_booking.section_contact_phone') }}</label>
                                                {{ Form::text('phone', $user_phone, array('class' => 'form-control')) }}
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>{{Lang::get('view_booking.section_contact_additional') }}</label>
                                                {{ Form::textarea('addition', '', array('class' => 'form-control', 'placeholder' => Lang::get('booking.section_contact_additional_placeholder'), 'rows' => 7)) }}
                                            </div>
                                            <div class="form-group col-md-12">
                                                <div class="pull-left">
                                                    <input type="submit" value="{{ Lang::get('package.book_button') }}" class="btn btn-primary medium">
                                                </div>
                                                <div class="pull-left col-sm-9 user-data-form-btndescr">
                                                    <p>{{Lang::get('view_booking.payment_instructions') }}</p>
                                                </div>
                                            </div>
                                        </fieldset>

                                        <input type="hidden" name="from" value="{{ $date_from }}"/>
                                        <input type="hidden" name="to" value="{{ $date_to }}"/>
                                        <input type="hidden" class="js-booking-package-id" name="package_id" value="{{ $package->id }}"/>

                                </div>
                            </td>
                        </tr>
                    </table>
                {{ Form::close() }}
                -->
            </div>
        </div>

        <div class="col-md-3 sidebar-rigth">
            <div class="contact-box">
                <div class="contact-text">
                    <h2>08-519 70 155</h2>
                    <p>{{Lang::get('view_booking.contact_text') }}</p>
                </div>
                <img src="/images/f7d528f4.booking-contact.jpg" alt="">
            </div>
        </div>
    </div>
</div>

<div id="discount-popup" class="modal">
    <div class="popup-discount modal-dialog">
        <span class="popup-close" data-dismiss="modal">&#x4d;</span>

        <h3 class="booking-discount-title">{{ Lang::get('booking.discount_code') }}/{{ Lang::get('booking.giftcard') }}</h3>
        <form action="" id="discount-form" class="booking-discount-form form-inline" method="POST">
            <div class="form-group">
                {{ Form::text('discount', '', array('id' => 'discount-code', 'class' => 'booking-discount-field form-control input-lg')) }}
            </div>
            <button type="submit" id="discount-send" class="btn btn-primary btn-lg">{{ Lang::get('view_booking.enter_discount_code') }}</button>
        </form>
        <p id="discount-error" class="booking-discount-error"></p>
    </div>
</div>
@stop

@section('footer')
@stop