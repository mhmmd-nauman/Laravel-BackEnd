@extends ('frontend._layouts.default')

@section('header')
    @include('frontend._partials.header_menu')
@stop

@section('secondary_header')
    @include('frontend._partials.secondary_header')
@stop

@section('content')
@if ( $highlights->count() )
<div class="container">
    <?php $i = 0; ?>
    @foreach($highlights as $highlight)
    <?php $i++; ?>
    <section class="highlight-section">
        <div class="row">
            @if ( $highlight->default_photo_id && $i%2 != 0 )
            <div class="col-sm-6">
                <img src="{{ URL::route('frontend.image.photo', array( 'size' => 'large', 'id' => $highlight->default_photo_id )) }}" alt="{{ $highlight->name }}" class="img-responsive">
            </div>
            @endif
            <div class="col-sm-6">
                <h2 class="highlight-title">{{ $highlight->name }}</h2>
                <p class="highlight-descr">{{ $highlight->description }}</p>
            </div>
            @if ( $highlight->default_photo_id && $i%2 == 0 )
            <div class="col-sm-6">
                <img src="{{ URL::route('frontend.image.photo', array( 'size' => 'large', 'id' => $highlight->default_photo_id )) }}" alt="{{ $highlight->name }}" class="img-responsive">
            </div>
            @endif
        </div>
    </section>
    @endforeach
</div>
@endif

@if ( $awards->count() )
<section class="highlight-cert">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h2 class="cert-title">{{ Lang::get('highlights.certificats_title') }}</h2>
            </div>
        </div>
    </div>

    <div class="cert-list">
        <div class="container">
            <div class="row">
                @foreach($awards as $award)
                <div class="col-sm-3 col-border-right">
                    <div class="cert-item">
                        @if ( $award->default_photo_id )
                            @if ( $award->link ) <a href="{{ $award->link }}">@endif
                            <img src="{{ URL::route('frontend.image.photo', array( 'size' => 'small', 'id' => $award->default_photo_id )) }}" alt="{{ $award->name }}" class="cert-item-image">
                            @if ( $award->link ) </a> @endif
                        @endif
                        <h4 class="cert-item-name">{{ $award->name }}</h4>
                        <p class="cert-item-descr">{{ $award->description }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

@if ( $highlights->count() && $highlights[0]->quote_text && $highlights[0]->default_quote_photo_id )
<section class="highlight-review">
    <div class="container">
        <div class="col-sm-12">
            <p class="review-text">{{ $highlights[0]->quote_text }}</p>
            <div class="review-sign">
                <span class="review-sign-name">{{ $highlights[0]->quote_author }}</span>
                <img src="{{ URL::route('frontend.image.photo', array( 'size' => 'small', 'id' => $highlights[0]->default_quote_photo_id )) }}" alt="" class="review-sign-photo">
            </div>
        </div>
    </div>
</section>
@endif

@stop

@section('footer')
@stop