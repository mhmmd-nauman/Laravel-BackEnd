@extends ('backend._layouts.default');
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><a href="{{ URL::route('backend.hotel_awards') }}">Awards</a> » {{ $hotel_award->name }}</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                {{ Form::model($hotel_award, array('url' => URL::route('backend.hotel_awards.update', $hotel_award->id), 'method' => 'PUT')) }}
                <div class="row">

                    <div class="col-sm-9">

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('hotel_award', 'Award hotel') }}
                                    {{ Form::text('hotel_award', $hotel_award->hotel->name, array('class' => 'form-control')) }}
                                    {{ Form::hidden('hotel_id', $hotel_award->hotel_id, array('id' => 'hotel_id')) }}
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('name', 'Name') }}
                                    {{ Form::text('name', null, array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('description', 'Description') }}
                                    {{ Form::textarea('description', null, array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                {{ Form::submit('Update hotel award', array('class' => 'btn btn-lg btn-primary btn-block')) }}
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('status', 'Status') }}
                                    {{ Form::select('status', array('' => 'Select', '0' => 'Inactive', '1' => 'Active'), null, array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('link', 'Link') }}
                                    {{ Form::text('link', Input::old('link'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                {{ Form::submit('Update hotel award', array('class' => 'btn btn-lg btn-primary btn-block')) }}
                            </div>
                        </div>
                    </div>
                </div>
            {{ Form::close() }}
            </div>

            <div class="row">
                <div class="col-sm-9">
                    <div class="panel panel-default btn-">
                        <div class="panel-body">
                            @foreach ($photos as $photo)
                            <div class="col-md-2 js-photo-button js-make-default-photo-{{ $photo->id }}">
                                <img src="{{ URL::route('frontend.image.photo', array( 'size' => 'small', 'id' => $photo->id )) }}" class="img-responsive img-rounded" />
                                @if ( $photo->id == $hotel_award->default_photo_id )
                                    <a href="javascript:makeDefault({{ $hotel_award->id }}, {{ $photo->id }})" class="btn btn-block btn-success">Default</a>
                                @else
                                    <a href="javascript:makeDefault({{ $hotel_award->id }}, {{ $photo->id }})" class="btn btn-block btn-default">Make default</a>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="form-group">
                    <div class="col-md-8">
                        {{ Form::open(array('url' => URL::route('backend.photos.store') , 'class' => 'form-horizontal', 'files' => true)) }}
                        <div class="form-group">
                            {{ Form::label('file', 'Photo', array('class' => 'col-sm-3 control-label') ) }}
                            <div class="col-sm-4">
                                {{ Form::file('file') }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('status', 'Status', array('class' => 'col-sm-3 control-label')) }}
                            <div class="col-sm-9">
                                {{ Form::select('status', array('' => 'Select', '0' => 'Inactive', '1' => 'Active'), Input::old('status'), array('class' => 'form-control')) }}
                            </div>
                        </div>

                        <div class="clearfix">
                            <div class="pull-right">
                                {{ Form::hidden('content_type', 'awards') }}
                                {{ Form::hidden('content_id', $hotel_award->id) }}
                                {{ Form::submit('Add Photo', array('class' => 'btn btn-primary')) }}
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
<script type="text/javascript">
    function makeDefault(hotel_award_id, photo_id) {
        $.ajax({
            url: "{{ URL::route('backend.hotel_awards.make_default_photo', '') }}/" + hotel_award_id+"/"+photo_id,
            type: "PUT",
            success: function(response) {
                alertify.success(response.content);
                $('.js-photo-button a').text('Make default').removeClass('btn-success').addClass('btn-default');
                $('.js-make-default-photo-'+photo_id+' a').text('Default').removeClass('btn-default').addClass('btn-success');
            }
        });
    }

    $(document).ready(function() {
        $("#hotel_award").typeahead({
            hint: true,
            award: true,
            minLength: 1
        },{
            displayKey: 'name',
            source: function (query, process) {
                return $.get( '{{ URL::route("backend.packages.autocomplete") }}', {name: $("#hotel_award").val()} ).done(function(data){
                    var options = [];
                    $.each(data,function (i,val){
                        options.push({name: val.name, id: val.id});
                    });
                    process(options);
                });
            }
        }).on('typeahead:selected', function (e, suggestion, name) {
                $('#hotel_id').val(suggestion.id);
        });
    });

</script>
@stop