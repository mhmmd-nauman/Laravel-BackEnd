@extends ('backend._layouts.default');

@section('header')
<link rel="stylesheet" href="{{ URL::asset('css/plugins/data-tables/dataTables.bootstrap.css') }}">
@stop

@section('content')
	<div id="page-wrapper">
		<div class="row">
			<div class="col-sm-12">
				<h1 class="page-header">
					Restaurants
					<div class="pull-right">
						<a href="{{ URL::route('backend.restaurants.create') }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Add</a>
					</div>
				</h1>
			</div>
        </div>
		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">
                    <table class="table table-striped table-bordered table-checkable" id="tableRestaurants">
                        <thead>
                        <tr>
                            <th>
                                <form name="formBulkActions" id="formBulkActions" method="post" class="form-inline"><input type="hidden" name="ids" /></form>
                                <a href="javascript:activateSelected();" class="btn btn-success btn-sm" title="Activate Selected"><i class="fa fa-fw fa-check-circle-o"></i></a>
                                <a href="javascript:deactivateSelected();" class="btn btn-danger btn-sm" title="Deactivate Selected"><i class="fa fa-fw fa-ban"></i></a>
                            </th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Created in</th>
                            <th>Hotel</th>
                            <th>Actions</th>
                        </tr>
                        <tr>
                            <td class="checkbox-column"><input type="checkbox" class="check-all"></td>
                            <td><input type="text" name="search_name" value="" class="form-control input-sm" placeholder="Search" autocomplete="off" /></td>
                            <td><input type="text" name="search_description" value="" class="form-control input-sm" placeholder="Search" autocomplete="off" /></td>
                            <td><select name="search_status" class="form-control input-sm" autocomplete="off"><option value="">Search</option><option value="0">Inactive</option><option value="1">Active</option></select></td>
                            <td><input type="text" name="search_created_at" value="" class="form-control input-sm" placeholder="Search" autocomplete="off" /></td>
                            <td><input type="text" name="search_hotel_name" value="" class="form-control input-sm" placeholder="Search" autocomplete="off" /></td>
                            <td></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="8" class="dataTables_empty">Loading data from server</td>
                        </tr>
                        </tbody>
                    </table>
				</div>
			</div>
		</div>
	</div>
@stop

@section('footer')
<script type="text/javascript" src="{{ URL::asset('js/plugins/data-tables/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/plugins/data-tables/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/plugins/data-tables/jquery.tableCheckable.js') }}"></script>
<script type="text/javascript">
    var table;
    $(document).ready(function() {
        table = $('#tableRestaurants').dataTable( {
            "sDom": "<'row dt-rt'<'col-sm-6'l>r>t<'row dt-rb'<'col-sm-6'i><'col-sm-6'p>>",
            "bProcessing": true,
            "bServerSide": true,
            "oLanguage": { "sSearch": "Search:" },
            "sAjaxSource": "{{ URL::route('backend.restaurants.datatables') }}",
            "bSortCellsTop": true,
            "iDisplayLength": 50,
            "aaSorting": [
                [6,'desc']
            ]
//            "aoColumns": [
//                { sWidth: '5%', bSearchable: false, bSortable: false },
//                { sWidth: '5%' },
//                { sWidth: '10%' },
//                { sWidth: '10%' },
//                { sWidth: '10%'  },
//                { sWidth: '5%' },
//                { sWidth: '5%', bSearchable: false, bSortable: false }
//            ]
        });
        $('.table-checkable').tableCheckable({refresh:true});
        $("thead input").keyup( function () {
            table.fnFilter( this.value, table.oApi._fnVisibleToColumnIndex(
                table.fnSettings(), $("thead td").index($(this).parent()) )
            );
        });

        $("thead select").change( function () {
            table.fnFilter( this.value, table.oApi._fnVisibleToColumnIndex(
                table.fnSettings(), $("thead td").index($(this).parent()) )
            );
        });

    });
    function deleteItem(id) {
        if (confirm('Are you sure you want to delete this restaurant?')) {
            $.ajax({
                url: "{{ URL::route('backend.restaurants.destroy', '') }}/" + id,
                type: "DELETE",
                success: function(response) {
                    table.fnDraw();
                    alertify.success(response.content);
                }
            });
        }
    }

    function activateSelected() {
        var ids = [];
        table.find('input[type="checkbox"]:not(.check-all):checked').each(function() {
            ids.push($(this).val());
        });
        if (ids.length > 0) {
            $('#formBulkActions').attr('action', "{{ URL::action('App\Controllers\Backend\RestaurantsController@activate') }}");
            $('#formBulkActions').find('input[type="hidden"]').val(ids);
            $('#formBulkActions').submit();
        }
    }

    function deactivateSelected() {
        var ids = [];
        table.find('input[type="checkbox"]:not(.check-all):checked').each(function() {
            ids.push($(this).val());
        });
        if (ids.length > 0) {
            $('#formBulkActions').attr('action', "{{ URL::action('App\Controllers\Backend\RestaurantsController@deactivate') }}");
            $('#formBulkActions').find('input[type="hidden"]').val(ids);
            $('#formBulkActions').submit();
        }
    }
</script>
@stop