<html>

    <head>
    </head>

    <body>
        <p>Hej {{ $user_name }}</p>
        <p>Tack för din bokning! (Kul med spa)</p>
        <strong>Vi konfirmerar allt i din bokning inom 48h</strong>
        <p>Nedan kan du se din bokning igen. Vi konfirmerar allt med spahotellet och du kommer att få ytterligare ett bekräftelsemail inom 48 timmar med en slutgiltig bekräftelse på att allt du ville ha finns tillgängligt.</p>
        <strong>Betalning av din vistelse</strong>
        <p>All betalning gör du på plats på spahotellet i samband med vistelsen.</p>
        <strong>Avbokningsregler</strong>
        <p>Avbokning kan ske fritt fram till 7 dagar innan din vistelse. Vid senare avbokning kan du debiteras delar eller hela beloppet för besöket.</p>
        <p>Kontakt och frågor
        Om du har några frågor till oss angående din bokning så finns vi här!
        <a href="mail:caroline@spason.se">caroline@spason.se</a>
        08 - 519 70 155</p>
        <strong>Här kommer bokningen</strong>

        <p><a href="{{ URL::route('frontend.hotel.hotel', array('name' => $package->hotel->slug)) }}">{{ $package->hotel->name }}</a></p>
        <p>{{ Lang::get('booking.package') }}: <a href="{{ URL::route('frontend.hotel.package', array('name' => $package->hotel->slug, 'package_name' => $package->slug)) }}">{{ $package->name }}</a></p>
        <p>{{ Lang::get('booking.date_from') }}: {{ $date_from }}</p>
        <p>{{ Lang::get('booking.date_to') }}: {{ $date_to }}</p>
        <p>{{ Lang::choice('booking.guests',2) }}: {{ $persons }}</p>
        <p>{{ Lang::choice('booking.nights',2) }}: {{ $nights_count }}</p>
        <p>{{Lang::get('booking.total_price')}}: {{ $order_price }} SEK</p>
        @if( isset($discount) && !empty($discount) )
        <p>{{ Lang::get('booking.discount_code') }}: {{ $discount_name }}</p>
        <p>{{Lang::get('booking.discount_total_price')}}: {{ $price_with_discount }} SEK</p>
        @endif
    </body>

<html>