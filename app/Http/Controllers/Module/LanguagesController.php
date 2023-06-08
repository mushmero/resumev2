<?php

namespace App\Http\Controllers\Module;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Languages;
use App\Models\ProficiencyLevel;
use Illuminate\Http\Request;

class LanguagesController extends Controller
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
        $this->title = 'Languages';
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
        $array = Languages::where(['user_id' => auth()->user()->id])->get();

        $heads = [
            'Name',
            'Proficiency',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->name,
                $arr->proficiency_id ? $arr->proficiency->proficiency : '',
                $arr->user->name,
                '<nobr><a href="'.url('languages/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('languages/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('languages/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null],
        ];

        if(Helper::getActiveProfile()->count() > 0){
            return view('modules.languages.list', [
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
        $proficiency = ProficiencyLevel::all();
        $configProficiency = [
            "placeholder" => "Select proficiency",
            "allowClear" => true,
        ];
        
        if(Helper::getActiveProfile()->count() > 0){
            return view('modules.languages.create', [
                'proficiency' => $proficiency,
                'configProficiency' => $configProficiency,
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

        $store = Languages::create([
            'name' => $data['name'],
            'proficiency_id' => $data['proficiency'],
            'user_id' => auth()->user()->id,
            'personal_id' => Helper::getActiveProfile()->id,
        ]);

        if($store){
            flash(
                'Language created successfully',
            )->success();
        }else{
            flash(
                'Unable to create Language',
            )->error();
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Languages::where('id', $id)->firstOrFail();

        $user = $data->user;
        
        if(Helper::getActiveProfile()->count() > 0){
            return view('modules.languages.show', [
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
        $data = Languages::where('id', $id)->firstOrFail();  
              
        $proficiency = ProficiencyLevel::all();
        $configProficiency = [
            "placeholder" => "Select proficiency",
            "allowClear" => true,
        ];
        
        if(Helper::getActiveProfile()->count() > 0){
            return view('modules.languages.edit', [
                'data' => $data,
                'proficiency' => $proficiency,
                'configProficiency' => $configProficiency,
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
        $data['proficiency_id'] = $request->input('proficiency');
        $data['user_id'] = auth()->user()->id;
        $data['personal_id'] = Helper::getActiveProfile()->id;

        $update = Languages::find($id)->update($data);

        if($update){
            flash(
                'Language updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update Language',
            )->error();
        }

         return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Languages::find($id)->delete();
        flash(
            'Language deleted successfully',
        )->success();
        return redirect()->route('languages');
    }
}
