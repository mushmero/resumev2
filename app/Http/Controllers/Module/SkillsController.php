<?php

namespace App\Http\Controllers\Module;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Icons;
use App\Models\Skills;
use Illuminate\Http\Request;

class SkillsController extends Controller
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
        $this->title = 'Skills';
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
        $array = Skills::where(['user_id' => auth()->user()->id])->get();

        $heads = [
            'Name',
            'Percentage',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->name.'&nbsp;&nbsp;&nbsp; <i class="fa-fw '.$arr->icons->fullname.'"></i>',
                '<div class="progress"><div role="progressbar" class="progress-bar progress-bar-striped progress-bar-animated" aria-valuenow="'.$arr->percentage.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$arr->percentage.'%; background-color: rgb(52, 125, 241);">'.$arr->percentage.'</div></div>',
                $arr->user->name,
                '<nobr><a href="'.url('skills/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('skills/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('skills/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null],
        ];

        if(!is_null(Helper::getActiveProfile())){
            return view('modules.skills.list', [
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
        $icons = Icons::all();  
        if(!is_null(Helper::getActiveProfile())){
            return view('modules.skills.create', [
                'icons' => $icons,    
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
            'percentage' => 'required|min:1|max:100',
        ]);

        $data = $request->all();

        $store = Skills::create([
            'name' => $data['name'],
            'percentage' => $data['percentage'],
            'user_id' => auth()->user()->id,
            'icon_id' => $data['icon'],
            'profile_id' => Helper::getActiveProfile()->id,
        ]);

        if($store){
            flash(
                'Skill created successfully',
            )->success();
        }else{
            flash(
                'Unable to create Skill',
            )->error();
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Skills::where('id', $id)->firstOrFail();

        $user = $data->user;
        
        if(!is_null(Helper::getActiveProfile())){
            return view('modules.skills.show', [
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
        $data = Skills::where('id', $id)->firstOrFail();

        $icons = Icons::all();
        
        if(!is_null(Helper::getActiveProfile())){
            return view('modules.skills.show', [
                'data' => $data,
                'icons' => $icons,
                'title' => $this->title,
                'table_title' => 'Edit '.$this->title,
            ]);
        }else{
            return view('no-access');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'percentage' => 'required|min:1|max:100',
        ]);

        $data['name'] = $request->input('name');
        $data['percentage'] = $request->input('percentage');
        $data['user_id'] = auth()->user()->id;
        $data['icon_id'] = $request->input('icon');
        $data['profile_id'] = Helper::getActiveProfile()->id;

        $update = Skills::find($id)->update($data);

        if($update){
            flash(
                'Skill updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update Skill',
            )->error();
        }

         return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Skills::find($id)->delete();
        flash(
            'Skill deleted successfully',
        )->success();
        return redirect()->route('skills');
    }
}
