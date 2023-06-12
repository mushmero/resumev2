<?php

namespace App\Http\Controllers\Module;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\EducationLevel;
use App\Models\Educations;
use Illuminate\Http\Request;

class EducationsController extends Controller
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
        $this->title = 'Educations';
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
        $array = Educations::where(['user_id' => auth()->user()->id])->get();

        $heads = [
            'Name',
            'Institution',
            'Year',
            'Level',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->name,
                $arr->institution,
                $arr->start .' - '. $arr->end,
                $arr->level_id ? $arr->edulevels->name .' (' .$arr->edulevels->level.')' : '',
                $arr->user->name,
                '<nobr><a href="'.url('educations/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('educations/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('educations/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null, null, null],
        ];

        if(!is_null(Helper::getActiveProfile())){
            return view('modules.educations.list', [
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
        $educations = EducationLevel::all();
        $configEducations = [
            "placeholder" => "Select level",
            "allowClear" => true,
        ];

        $startDate = [
            'format' => 'YYYY',
            'useCurrent' => false,
        ];

        $endDate = [
            'format' => 'YYYY',
            'useCurrent' => false,
        ];
        
        if(!is_null(Helper::getActiveProfile())){
            return view('modules.educations.create', [
                'educations' => $educations,
                'configEducations' => $configEducations,
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
            'name' => 'required',
        ]);

        $data = $request->all();

        $store = Educations::create([
            'name' => $data['name'],
            'institution' => $data['institution'],
            'start' => $data['startDate'],
            'end' => $data['endDate'],
            'level_id' => $data['level'],
            'user_id' => auth()->user()->id,
            'profile_id' => Helper::getActiveProfile()->id,
        ]);

        if($store){
            flash(
                'Education created successfully',
            )->success();
        }else{
            flash(
                'Unable to create Education',
            )->error();
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Educations::where('id', $id)->firstOrFail();

        $user = $data->user;
        
        if(!is_null(Helper::getActiveProfile())){
            return view('modules.educations.show', [
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
        $data = Educations::where('id', $id)->firstOrFail();

        $educations = EducationLevel::all();
        $configEducations = [
            "placeholder" => "Select level",
            "allowClear" => true,
        ];

        $startDate = [
            'format' => 'YYYY',
            'useCurrent' => false,
        ];

        $endDate = [
            'format' => 'YYYY',
            'useCurrent' => false,
        ];
        
        if(!is_null(Helper::getActiveProfile())){
            return view('modules.educations.edit', [
                'data' => $data,
                'educations' => $educations,
                'configEducations' => $configEducations,
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
            'name' => 'required',
        ]);

        $data['name'] = $request->input('name');
        $data['institution'] = $request->input('institution');
        $data['start'] = $request->input('startDate');
        $data['end'] = $request->input('endDate');
        $data['level_id'] = $request->input('level');
        $data['user_id'] = auth()->user()->id;
        $data['profile_id'] = Helper::getActiveProfile()->id;

        $update = Educations::find($id)->update($data);

        if($update){
            flash(
                'Education updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update Education',
            )->error();
        }

         return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Educations::find($id)->delete();
        flash(
            'Education deleted successfully',
        )->success();
        return redirect()->route('educations');
    }
}
