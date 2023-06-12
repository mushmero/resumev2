<?php

namespace App\Http\Controllers\Module;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Experiences;
use Illuminate\Http\Request;

class ExperiencesController extends Controller
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
        $array = Experiences::where(['user_id' => auth()->user()->id])->get();

        $heads = [
            'Position',
            'Exp Length',
            'Employer',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->position,
                $arr->start .' - '. $arr->end,
                $arr->employer,
                $arr->user->name,
                '<nobr><a href="'.url('experiences/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('experiences/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('experiences/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null, null],
        ];

        if(!is_null(Helper::getActiveProfile())){
            return view('modules.experiences.list', [
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

        $startDate = [
            'format' => 'MM/YYYY',
            'useCurrent' => true,
            'maxDate' => "js:moment()",
        ];

        $endDate = [
            'format' => 'MM/YYYY',
            'useCurrent' => true,
            'maxDate' => "js:moment()",
        ];

        
        if(!is_null(Helper::getActiveProfile())){
            return view('modules.experiences.create', [
                'configTE' => $configTE,
                'startDate' => $startDate,
                'endDate' => $endDate,
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
            'position' => 'required',
        ]);

        $data = $request->all();

        $store = Experiences::create([
            'position' => $data['position'],
            'start' => $data['startDate'],
            'end' => !empty($data['current']) ? $data['current'] : $data['endDate'],
            'employer' => $data['employer'],
            'user_id' => auth()->user()->id,
            'description' => $data['description'],
            'profile_id' => Helper::getActiveProfile()->id,
        ]);

        if($store){
            flash(
                'Experience created successfully',
            )->success();
        }else{
            flash(
                'Unable to create Experience',
            )->error();
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Experiences::where('id', $id)->firstOrFail();

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
            return view('modules.experiences.show', [
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
        $data = Experiences::where('id', $id)->firstOrFail();

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

        $startDate = [
            'format' => 'MM/YYYY',
            'useCurrent' => true,
            'maxDate' => "js:moment()",
        ];

        $endDate = [
            'format' => 'MM/YYYY',
            'useCurrent' => true,
            'maxDate' => "js:moment()",
        ];
        
        if(!is_null(Helper::getActiveProfile())){
            return view('modules.experiences.edit', [
                'data' => $data,
                'configTE' => $configTE,
                'startDate' => $startDate,
                'endDate' => $endDate,
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
            'position' => 'required',
        ]);

        $data['position'] = $request->input('position');
        $data['employer'] = $request->input('employer');
        $data['start'] = $request->input('startDate');
        $data['end'] = !empty($request->input('current')) ? $request->input('current') : $request->input('endDate');
        $data['description'] = $request->input('description');
        $data['user_id'] = auth()->user()->id;
        $data['profile_id'] = Helper::getActiveProfile()->id;

        $update = Experiences::find($id)->update($data);

        if($update){
            flash(
                'Experience updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update Experience',
            )->error();
        }

         return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Experiences::find($id)->delete();
        flash(
            'Experience deleted successfully',
        )->success();
        return redirect()->route('experiences');
    }
}
