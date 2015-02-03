<?php namespace App\Controllers\Backend;

use View, URL, Datatables, Notification, Redirect, Input, Sentry, Response;
use \User, \Spas, \Treatment;


class TreatmentsController extends BackendBaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return View::make('backend.treatments.index');
    }

    /**
     * Method to return the list of items for the dataTables. It handles pagination, sorting, filtering, etc.
     *
     * @return Response
     */
    public function dataTables()
    {
        $treatments = Treatment::select(array(
            'treatments.id',
            'treatments.name',
            'treatments.description',
            'spas.name AS spa_name',
            'treatments.price',
            'treatments.status',
            'treatments.created_at',
        ))
            ->leftJoin('spas', 'spas.id', '=', 'treatments.spa_id');

        return Datatables::of($treatments)
            ->edit_column('status','@if($status == 1) Active @else Inactive @endif')
            ->add_column('actions', '
            <a href="{{ URL::action(\'App\Controllers\Backend\TreatmentsController@edit\', array($id) ) }}" class="btn btn-info btn-sm" title="Edit"><i class="fa fa-fw fa-edit"></i></a>
            <a href="javascript:void(0);" onclick="deleteItem(\'{{$id}}\');" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-fw fa-times"></i></a>')
            ->edit_column ('id', '<span id="row-{{$id}}" class="checkbox-column"><input type="checkbox" value="{{$id}}" /></span>')
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $scripts  = '<script type="text/javascript" src="'.URL::asset('js/plugins/typeahead.bundle.min.js').'"></script>';
        return View::make('backend.treatments.create', array('scripts' => $scripts));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Redirect
     */
    public function store()
    {
        $v = new \App\Services\Validators\Treatment;
        if($v->passes()) {
            try {
                //Save the treatment
                $data = array();
                $data['spa_id']          = Input::get('spa_id');
                $data['name']            = Input::get('name');
                $data['description']     = Input::get('description');
                $data['status']          = Input::get('status');
                $data['persons']         = Input::get('persons');
                $data['price']           = Input::get('price');
                $data['duration']        = Input::get('duration');
                $treatment = Treatment::create($data);
            }
            catch(\Exception $e) {
                var_dump ($e->getMessage());
                die();
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::action('backend.treatments.create')->withInput();
            }
            Notification::container('backendFeedback')->success('The new treatment has been created');
            return Redirect::action('backend.treatments');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.treatments.create')->withInput();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $treatment = Treatment::with('spa')->find($id);

        $scripts  = '<script type="text/javascript" src="'.URL::asset('js/plugins/typeahead.bundle.min.js').'"></script>';
        return View::make('backend.treatments.edit', array('treatment' => $treatment, 'scripts' => $scripts) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Redirect
     */
    public function update($id)
    {
        $v = new \App\Services\Validators\Treatment;
        if($v->passes('update')) {
            try {
                $treatment = Treatment::find($id);
                $treatment->name        = Input::get('name');
                $treatment->description = Input::get('description');
                $treatment->status      = Input::get('status');
                $treatment->persons     = Input::get('persons');
                $treatment->price       = Input::get('price');
                $treatment->duration    = Input::get('duration');
                $treatment->spa_id      = Input::get('spa_id');
                $treatment->save();
            }
            catch(\Exception $e) {
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::route('backend.treatments.edit', array('id' => $id) )->withInput();
            }
            Notification::container('backendFeedback')->success('The treatment has been updated');
            return Redirect::action('backend.treatments');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.treatments.edit', array('id' => $id) )->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $treatment = Treatment::find($id);
        $treatment->delete();
        return Response::json( array('result' => true, 'content' => 'The treatment has been deleted') );
    }

    function activate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $treatment = Treatment::find($id);
            if ($treatment) {
                $treatment->status = 1;
                $treatment->save();
            }
        }
        return Redirect::route('backend.treatment');
    }

    function deactivate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $treatment = Treatmen::find($id);
            if ($treatment) {
                $treatment->status = 0;
                $treatment->save();
            }
        }
        return Redirect::route('backend.treatments');
    }

    /**
     * Autocomplete hotels for spas
     *
     * @return Response
     */
    public function autocomplete()
    {

        $spa_name = Input::get('name');

        $spas = Spas::select(array('id', 'name'))
            ->where('name', 'like', "%$spa_name%")
            ->where('status', '=', 1)
            ->get();

        $spas_array = array();

        foreach($spas as $spa) {
            $spas_array[] = $spa->toArray();
        }

        return Response::json( $spas_array );
    }

}