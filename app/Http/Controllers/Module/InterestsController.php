<?php

namespace App\Http\Controllers\Module;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Interests;
use Illuminate\Http\Request;

class InterestsController extends Controller
{
    protected $btnEdit, $btnDelete, $btnDetails, $title, $error_msg;

    /**
     * Declare default template for button edit, button delete & button view
     */
    public function __construct()
    {
        $this->middleware('autologout');
        $this->btnEdit = config('lapdash.btn-edit');
        $this->btnDelete = config('lapdash.btn-delete');
        $this->btnDetails = config('lapdash.btn-view');
        $this->title = 'Interests';
        $this->error_msg = [
            'inactive_profile' => 'No active profile. Please activate first',
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array();
        $array = Interests::where(['user_id' => auth()->user()->id])->get();

        $heads = [
            'Name',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $actions = '<nobr>' .
                '<a href="'.url('interests/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a>' .
                '<form action="'.url('interests/'.$arr->id).'" method="POST" style="display:inline;" onsubmit="return confirm(\'Are you sure you want to delete this interest?\');">' .
                    csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="border-0 bg-transparent p-0">'.$this->btnDelete.'</button>' .
                '</form>' .
                '<a href="'.url('interests/'.$arr->id.'/show').'">'.$this->btnDetails.'</a>' .
            '</nobr>';

            $data[] = array(
                e($arr->name),
                e($arr->user->name),
                $actions,
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null],
        ];

        if(!is_null(Helper::getActiveProfile())){
            return view('modules.interests.list', [
                'heads' => $heads,
                'config' => $config,
                'title' => $this->title,
                'table_title' => $this->title.' List',
            ]);
        }else{
            return view('no-access',[
                'error_msg' => $this->error_msg['inactive_profile'],
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {        
        if(!is_null(Helper::getActiveProfile())){
            return view('modules.interests.create', [
                'title' => $this->title,
                'table_title' => 'Create '.$this->title,
            ]);
        }else{
            return view('no-access',[
                'error_msg' => $this->error_msg['inactive_profile'],
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $data = $request->all();

        $store = Interests::create([
            'name' => $data['name'],
            'user_id' => auth()->user()->id,
            'profile_id' => Helper::getActiveProfile()->id,
        ]);

        if($store){
            flash(
                'Interest created successfully',
            )->success();
        }else{
            flash(
                'Interest to create Skill',
            )->error();
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Interests::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $user = $data->user;
        
        if(!is_null(Helper::getActiveProfile())){
            return view('modules.interests.show', [
                'data' => $data,
                'user' => $user,
                'title' => $this->title,
                'table_title' => 'Detail '.$this->title,
            ]);
        }else{
            return view('no-access',[
                'error_msg' => $this->error_msg['inactive_profile'],
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Interests::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        
        if(!is_null(Helper::getActiveProfile())){
            return view('modules.interests.edit', [
                'data' => $data,
                'title' => $this->title,
                'table_title' => 'Edit '.$this->title,
            ]);
        }else{
            return view('no-access',[
                'error_msg' => $this->error_msg['inactive_profile'],
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $interest = Interests::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $this->validate($request, [
            'name' => 'required',
        ]);

        $data['name'] = $request->input('name');
        $data['user_id'] = auth()->user()->id;
        $data['profile_id'] = Helper::getActiveProfile()->id;

        $update = $interest->update($data);

        if($update){
            flash(
                'Interest updated successfully',
            )->success();
        }else{
            flash(
                'error',
                'Unable to update Interest',
            )->error();
        }

         return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $interest = Interests::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $interest->delete();
        flash(
            'Interest deleted successfully',
        )->success();
        return redirect()->route('interests');
    }
}
