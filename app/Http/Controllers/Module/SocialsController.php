<?php

namespace App\Http\Controllers\Module;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Icons;
use App\Models\Socials;
use Illuminate\Http\Request;

class SocialsController extends Controller
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
        $this->title = 'Socials';
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
        $array = Socials::where(['user_id' => auth()->user()->id])->get();

        $heads = [
            'Name',
            'Link',
            'Icon',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $actions = '<nobr>' .
                '<a href="'.url('socials/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a>' .
                '<form action="'.url('socials/'.$arr->id).'" method="POST" style="display:inline;" onsubmit="return confirm(\'Are you sure you want to delete this social link?\');">' .
                    csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="border-0 bg-transparent p-0">'.$this->btnDelete.'</button>' .
                '</form>' .
                '<a href="'.url('socials/'.$arr->id.'/show').'">'.$this->btnDetails.'</a>' .
            '</nobr>';

            $data[] = array(
                e($arr->name),
                e($arr->link),
                $arr->icon_id && $arr->icons ? '<i class="fa-fw '.e($arr->icons->fullname).'"></i>&nbsp;'.e($arr->icons->name) : '',
                e($arr->user->name),
                $actions,
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null, null],
        ];

        if(!is_null(Helper::getActiveProfile())){
            return view('modules.socials.list', [
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
            return view('modules.socials.create', [
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
        ]);

        $data = $request->all();

        $store = Socials::create([
            'name' => $data['name'],
            'link' => $data['link'],
            'icon_id' => $data['icon'],
            'user_id' => auth()->user()->id,
            'profile_id' => Helper::getActiveProfile()->id,
        ]);

        if($store){
            flash(
                'Social created successfully',
            )->success();
        }else{
            flash(
                'Unable to create Social',
            )->error();
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Socials::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $user = $data->user;
        
        if(!is_null(Helper::getActiveProfile())){
            return view('modules.socials.show', [
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
        $data = Socials::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $icons = Icons::all();
        
        if(!is_null(Helper::getActiveProfile())){
            return view('modules.socials.edit', [
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
        $social = Socials::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $this->validate($request, [
            'name' => 'required',
        ]);

        $data['name'] = $request->input('name');
        $data['link'] = $request->input('link');
        $data['icon_id'] = $request->input('icon');
        $data['user_id'] = auth()->user()->id;
        $data['profile_id'] = Helper::getActiveProfile()->id;

        $update = $social->update($data);

        if($update){
            flash(
                'Social updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update Social',
            )->error();
        }

         return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $social = Socials::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $social->delete();
        flash(
            'Social deleted successfully',
        )->success();
        return redirect()->route('socials');
    }
}
