<?php

namespace App\Http\Controllers\Module;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Projects;
use App\Models\Status;
use Illuminate\Http\Request;

class ProjectsController extends Controller
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
        $this->title = 'Experiences';
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
        $array = Projects::where(['user_id' => auth()->user()->id])->get();

        $heads = [
            'Name',
            'Status',
            'Description',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->name,
                $arr->status_id ? $arr->status->status : '',
                $arr->description,
                $arr->user->name,
                '<nobr><a href="'.url('projects/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('projects/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('projects/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null, null],
        ];

        if(!is_null(Helper::getActiveProfile())){
            return view('modules.projects.list', [
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
        $configTE = [
            "height" => "300",
            "toolbar" => [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                // ['font', ['strikethrough', 'superscript', 'subscript']],
                // ['fontsize', ['fontsize']],
                // ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                // ['height', ['height']],
                // ['table', ['table']],
                // ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
        ];
        
        $status = Status::all();
        $configStatus = [
            "placeholder" => "Select status",
            "allowClear" => true,
        ];

        
        if(!is_null(Helper::getActiveProfile())){
            return view('modules.projects.create', [
                'configTE' => $configTE,
                'status' => $status,
                'configStatus' => $configStatus,
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

        $store = Projects::create([
            'name' => $data['name'],
            'status_id' => $data['status'],
            'user_id' => auth()->user()->id,
            'description' => $data['description'],
            'profile_id' => Helper::getActiveProfile()->id,
        ]);

        if($store){
            flash(
                'Project created successfully',
            )->success();
        }else{
            flash(
                'Unable to create Project',
            )->error();
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Projects::where('id', $id)->firstOrFail();

        $user = $data->user;

        $configTE = [
            "height" => "300",
            "toolbar" => [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                // ['font', ['strikethrough', 'superscript', 'subscript']],
                // ['fontsize', ['fontsize']],
                // ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                // ['height', ['height']],
                // ['table', ['table']],
                // ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
        ];
        
        if(!is_null(Helper::getActiveProfile())){
            return view('modules.projects.show', [
                'data' => $data,
                'user' => $user,
                'configTE' => $configTE,
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
        $data = Projects::where('id', $id)->firstOrFail();

        $configTE = [
            "height" => "300",
            "toolbar" => [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                // ['font', ['strikethrough', 'superscript', 'subscript']],
                // ['fontsize', ['fontsize']],
                // ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                // ['height', ['height']],
                // ['table', ['table']],
                // ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
        ];
        
        $status = Status::all();
        $configStatus = [
            "placeholder" => "Select status",
            "allowClear" => true,
        ];
        
        if(!is_null(Helper::getActiveProfile())){
            return view('modules.projects.edit', [
                'data' => $data,
                'configTE' => $configTE,
                'status' => $status,
                'configStatus' => $configStatus,
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
        $this->validate($request, [
            'name' => 'required',
        ]);

        $data['name'] = $request->input('name');
        $data['status_id'] = $request->input('status');
        $data['description'] = $request->input('description');
        $data['user_id'] = auth()->user()->id;
        $data['profile_id'] = Helper::getActiveProfile()->id;

        $update = Projects::find($id)->update($data);

        if($update){
            flash(
                'Project updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update Project',
            )->error();
        }

         return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Projects::find($id)->delete();
        flash(
            'Project deleted successfully',
        )->success();
        return redirect()->route('projects');
    }
}
