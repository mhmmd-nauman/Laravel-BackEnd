<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="dns-prefetch" href="http://googleapis.com">
        <link href="/media/favicon.ico" rel="shortcut icon" type="image/x-icon" />

        <title>{{ $title or 'Spason' }}</title>
        @if(isset($meta_description))
        <meta name="description" content="{{$meta_description}}">
        @endif
        <link href="{{ URL::asset('styles/bootstrap.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('styles/vendor.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('styles/main.css') }}" rel="stylesheet">
        {{ $links or '' }}
    </head>
    <body>
        {{ $ga or '' }}
        <div class="l-wrap -no-footer">
            <span style="position: absolute;">{{ $errors or '' }}</span>

            <header class="l-header">
                @yield('header')

                <div class="l-header-secondary @if(Route::currentRouteName() == 'frontend.hotel.order') hidden @endif">
                    @yield('secondary_header')
                </div>
            </header>


            <div id="content" class="l-content">
                @yield('content')
            </div>
        </div>


        @yield('footer')


        <!-- sing up forms -->
        <div id="signup-popup" class="modal">
            <div class="popup-auth modal-dialog">
                <span class="popup-close" data-dismiss="modal">&#x4d;</span>

                <form action="{{ URL::route('frontend.client.register') }}" id="signup-form-first" class="popup-auth-form" method="POST">
                    <h2 class="popup-auth-title">Sign up*</h2>

                    <a href="{{ URL::route('frontend.client.facebook_login') }}" class="popup-auth-facebook btn btn-facebook btn-lg">
                        <i class="facebook-icon" data-icon="&#xe093;"></i> {{ Lang::get('popup.signup_facebook_button') }}
                    </a>

                    <span class="popup-auth-or">{{ Lang::get('popup.signup_or') }}</span>

                    <div class="form-group">
                        <div class="input-group input-group-lg">
                            <span class="popup-auth-email-icon input-group-addon" data-icon="&#xe010;"></span>
                            <input type="email" id="signup-email-first" name="email" class="form-control" placeholder="{{ Lang::get('popup.signup_email_placeholder') }}">
                        </div>
                    </div>
                    <a href="#signup-popup-next" id="signup-next-open" class="popup-auth-btn btn btn-success btn-lg">{{ Lang::get('popup.signup_button_title') }}</a><br>
                    <span>or <a href="#login-popup" id="login-open">{{ Lang::get('popup.login_link_title') }}</a></span>

                    <p class="popup-auth-license"><sup>*</sup>{{ Lang::get('popup.signup_bottom_text') }} <a href="{{ URL::to('/vilkor-integritet-och-cookies') }}">{{ Lang::get('popup.signup_license_link') }}</a></p>
                </form>
            </div>
        </div>

        <div id="signup-popup-next" class="modal">
            <div class="popup-auth modal-dialog">
                <span class="popup-close" data-dismiss="modal">&#x4d;</span>

                <form action="{{ URL::route('frontend.client.update') }}" id="signup-form-second" class="popup-auth-form" method="POST">
                    <h2 class="popup-auth-title">{{ Lang::get('popup.additional_info_title') }}</h2>

                    <div class="popup-auth-fields">
                        <div class="form-group">
                            <label for="signup-email">{{ Lang::get('popup.additional_info_email') }}</label>
                            <input type="email" id="signup-email-second" name="email" class="form-control input-lg" disabled>
                        </div>
                        <div class="form-group">
                            <label for="signup-name">{{ Lang::get('popup.additional_info_name') }}</label>
                            <input type="text" id="signup-name" name="name" class="form-control input-lg">
                        </div>
                        <div class="form-group">
                            <label for="signup-phone">{{ Lang::get('popup.additional_info_phone') }}</label>
                            <input type="tel" id="signup-phone" name="phone" class="form-control input-lg">
                        </div>
                        <div class="form-group">
                            <label for="signup-password">{{ Lang::get('popup.additional_info_password') }}</label>
                            <input type="password" id="signup-password" name="password" class="form-control input-lg">
                        </div>

                        <button type="submit" class="popup-auth-btn btn btn-success btn-lg">{{ Lang::get('popup.additional_info_save_button') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="login-popup" class="modal">
            <div class="popup-auth modal-dialog">
                <span class="popup-close" data-dismiss="modal">&#x4d;</span>

                <form action="{{ URL::route('frontend.client.login') }}" id="login-form" class="popup-auth-form" method="POST">
                    <h2 class="popup-auth-title">{{ Lang::get('popup.login_title') }}</h2>

                    <div class="popup-auth-fields">
                        <div class="form-group">
                            <input type="email" id="" name="email" class="form-control input-lg" placeholder="{{ Lang::get('popup.login_email_placeholder') }}">
                        </div>
                        <div class="form-group">
                            <input type="password" id="" name="password" class="form-control input-lg" placeholder="{{ Lang::get('popup.login_password_placeholder') }}">
                        </div>
                    </div>

                    <button type="submit" class="popup-auth-btn btn btn-success btn-lg">{{ Lang::get('popup.login_button') }}</button>
                </form>
            </div>
        </div>
        <!-- sing up forms -->

        <script>
            window.hotel_images = {{ $hotel_images or "'undefined'" }};
            window.jsTranslations = {{ $js_translations or "'undefined'" }};
        </script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="{{ URL::asset('scripts/bootstrap.js') }}"></script>
        <script src="{{ URL::asset('scripts/vendor.js') }}"></script>
        <script src="{{ URL::asset('scripts/main.js') }}"></script>
        {{ $scripts or '' }}
    </body>
</html>
