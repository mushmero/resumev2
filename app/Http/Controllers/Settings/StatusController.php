<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    protected $btnEdit, $btnDelete, $btnDetails, $title;

    /**
     * Declare default template for button edit, button delete & button view
     */
    public function __construct()
    {
        $this->middleware('autologout');
        $this->btnEdit = config('lapdash.btn-edit');
        $this->btnDelete = config('lapdash.btn-delete');
        $this->btnDetails = config('lapdash.btn-view');
        $this->title = 'Status';
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array();
        $array = Status::all();

        $heads = [
            'Status',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->status,
                '<nobr><a href="'.url('statuslist/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('statuslist/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('statuslist/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null],
        ];

        return view('settings.status.list', [
            'heads' => $heads,
            'config' => $config,
            'title' => $this->title,
            'table_title' => $this->title.' List',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.status.create', [            
            'title' => $this->title,
            'table_title' => 'Create '.$this->title,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'status' => 'required|unique:statuses',
        ]);

        $data = $request->all();

        $store = Status::create([
            'status' => $data['status'],            
            'user_id' => auth()->user()->id,
        ]);

        if($store){
            flash(
                'Status created successfully',
            )->success();
        }else{
            flash(
                'Unable to create Status',
            )->error();
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Status::where('id', $id)->firstOrFail();

        return view('settings.status.show', [
            'data' => $data,
            'title' => $this->title,
            'table_title' => 'Detail '.$this->title,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Status::where('id', $id)->firstOrFail();

        return view('settings.status.edit', [
            'data' => $data,
            'title' => $this->title,
            'table_title' => 'Edit '.$this->title,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'status' => 'required|unique:statuses',
        ]);
        
        $data['status'] = $request->input('status');
        $data['user_id'] = auth()->user()->id;

        $update = Status::find($id)->update($data);

        if($update){
            flash(
                'Status updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update status',
            )->error();
        }

         return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Status::find($id)->delete();
        flash(
            'Status deleted successfully',
        )->success();
        return redirect()->route('statuslist');
    }
}
