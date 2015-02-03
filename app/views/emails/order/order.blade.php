<html>

    <head>
    </head>

    <body>
        <div>Hotel: <a href="{{ URL::route('frontend.hotel.hotel', array('name' => $package->hotel->slug)) }}">{{ $package->hotel->name }}</a></div>
        <div>Package: <a href="{{ URL::route('frontend.hotel.package', array('name' => $package->hotel->slug, 'package_name' => $package->slug)) }}">{{ $package->name }}</a></div>
        <div>Date from: {{ $date_from }}</div>
        <div>Date to: {{ $date_to }}</div>
        <div>Number of persons: {{ $persons }}</div>
        <div>------------------</div>
        <div>Original price: {{ $order_price }}  SEK ({{ $package_price }} package price * {{ $persons }} persons * {{ $nights_count }} nights)</div>
        @if( isset($discount) && !empty($discount) )
        <div>Discount: {{ $discount_name }}</div>
        <div>Total discount: {{ $discount_price }}</div>
        <div>New price: {{ $price_with_discount }}</div>
        @endif
        <div></div>
        <div>------------------</div>
        <div>Name: {{ $user_name }}</div>
        <div>Email: {{ $user_email }}</div>
        <div>Phone: {{ $user_phone }}</div>
        <div>------------------</div>
        <div>Message:</div>
        <div>{{ $user_message }}</div>
    </body>

<html>