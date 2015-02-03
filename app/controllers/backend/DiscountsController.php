<?php namespace App\Controllers\Backend;

use View, URL, Datatables, Notification, Redirect, Input, Sentry, Response;
use \Discount;
use Whoops\Example\Exception;

class DiscountsController extends BackendBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('backend.discounts.index');
	}
	
	/**
	 * Method to return the list of items for the dataTables. It handles pagination, sorting, filtering, etc.
	 *
	 * @return Response
	 */
	public function dataTables() 
	{
        $discounts = Discount::select(array (
            'discounts.id',
            'discounts.name',
            'discounts.code',
            'discounts.count',
            'discounts.created_at',
            'discounts.expire',
            'discounts.status'
        ));

		return Datatables::of($discounts)
            ->edit_column('status','@if($status == 1) Active @else Inactive @endif')
            ->add_column('actions', '
            <a href="{{ URL::action(\'App\Controllers\Backend\DiscountsController@edit\', array($id) ) }}" class="btn btn-info btn-sm" title="Edit"><i class="fa fa-fw fa-edit"></i></a>
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
        return View::make('backend.discounts.create', array('user' => Sentry::getUser(), 'scripts' => $scripts));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Redirect
     */
    public function store()
    {
        $v = new \App\Services\Validators\Discount;
        if($v->passes()) {
            try {
                //Save the Discount
                $data = array();
                $data['code']                = Input::get('code');
                $data['name']                = Input::get('name');
                $data['count']               = Input::get('count');
                $data['price_type']          = Input::get('price_type');
                $data['expire']              = Input::get('expire');
                $data['discount']            = Input::get('discount');
                $data['status']              = Input::get('status');
                $discount = Discount::create($data);
            }
            catch(\Exception $e) {
                var_dump ($e->getMessage());
                die();
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::action('backend.discounts.create')->withInput();
            }
            Notification::container('backendFeedback')->success('The new discount has been created');
            return Redirect::action('backend.discounts');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.discounts.create')->withInput();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        try {
            $discount = Discount::find($id);

        } catch(Exception $e) {
           App::abort(404);
        }

        return View::make('backend.discounts.edit', array(
            'scripts'     => '',
            'discount' => $discount
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Redirect
     */
    public function update($id)
    {
        $v = new \App\Services\Validators\Discount;

        if($v->passes('update')) {
            try {

                $discount = Discount::find($id);
                $discount->code       = Input::get('code');
                $discount->name       = Input::get('name');
                $discount->count      = Input::get('count');
                $discount->status     = Input::get('status');
                $discount->price_type = Input::get('price_type');
                $discount->expire     = Input::get('expire');
                $discount->discount   = Input::get('discount');

                $discount->save();

            }
            catch(\Exception $e) {
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::route('backend.discounts.edit', array('id' => $id) )->withInput();
            }
            Notification::container('backendFeedback')->success('The discount has been updated');
            return Redirect::action('backend.discounts');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.discounts.edit', array('id' => $id) )->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $discount = Discount::find($id);
        $discount->delete();
        return Response::json( array('result' => true, 'content' => 'The discount has been deleted') );
    }

    function activate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $discount = Discount::find($id);
            if ($discount) {
                $discount->status = 1;
                $discount->save();
            }
        }
        return Redirect::route('backend.discounts');
    }

    function deactivate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $discount = Discount::find($id);
            if ($discount) {
                $discount->status = 0;
                $discount->save();
            }
        }
        return Redirect::route('backend.discounts');
    }

}