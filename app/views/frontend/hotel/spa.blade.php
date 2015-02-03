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
        <section class="spa-section">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="spa-title">{{ $hotel->spa_name }}</h1>
                    <div class="spa-descr">
                        <p>{{ $hotel->spa_description }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    @if ( $spa_services->count() )
                    <div class="hotel-checklist">
                        <h4 class="checklist-title">{{ Lang::get('spa.services_title') }}</h4>
                        <ul class="checklist-items">
                            @foreach($spa_services as $service)
                                <li><strong>{{ $service->name }}</strong></li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>

<section class="treatments-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="treatments-title">{{ Lang::get('spa.treatments_title') }}</h2>
            </div>
        </div>

        <div class="row hidden">
            <div class="col-md-12 text-center">
                <nav class="treatments-nav">
                    <a href="#" class="treatments-nav-item is-current">
                        <span>Rekommenderade</span>
                    </a>
                    <a href="#" class="treatments-nav-item">
                        <span>För honom</span>
                    </a>
                    <a href="#" class="treatments-nav-item">
                        <span>För henne</span>
                    </a>
                    <a href="#" class="treatments-nav-item">
                        <span>Massage</span>
                    </a>
                    <a href="#" class="treatments-nav-item">
                        <span>Exotiskt</span>
                    </a>
                    <a href="#" class="treatments-nav-item">
                        <span>Händer &amp; fötter</span>
                    </a>
                </nav>
            </div>
        </div>
    </div>

    @if ( $spa_treatments->count() )
    <div class="treatments-list">
        <div class="container">
            <?php $i = 0; ?>
            @foreach($spa_treatments as $treatment)
            <?php $i++; ?>
            @if ( $i == 1 )
            <div class="treatments-row row">
            @endif
                <div class="col-md-4 col-border-right">
                    <div class="treatments-list-item">
                        <h3 class="treatments-list-title">{{ $treatment->name }}</h3>
                        <p class="treatments-list-info"><b>{{ $treatment->persons }} person {{ $treatment->duration }} min: {{ $treatment->price }} SEK</b></p>
                        <p class="treatments-list-descr">{{ $treatment->description }}</p>
                    </div>
                </div>
            @if ( $i > 2 )
            <?php $i = 0; ?>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @endif
</section>
@stop

@section('footer')
@stop