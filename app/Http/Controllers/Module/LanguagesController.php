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
            $actions = '<nobr>' .
                '<a href="'.url('languages/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a>' .
                '<form action="'.url('languages/'.$arr->id).'" method="POST" style="display:inline;" onsubmit="return confirm(\'Are you sure you want to delete this language?\');">' .
                    csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="border-0 bg-transparent p-0">'.$this->btnDelete.'</button>' .
                '</form>' .
                '<a href="'.url('languages/'.$arr->id.'/show').'">'.$this->btnDetails.'</a>' .
            '</nobr>';

            $data[] = array(
                e($arr->name),
                $arr->proficiency_id && $arr->proficiency ? e($arr->proficiency->proficiency) : '',
                e($arr->user->name),
                $actions,
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null],
        ];

        if(!is_null(Helper::getActiveProfile())){
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
        
        if(!is_null(Helper::getActiveProfile())){
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
            'profile_id' => Helper::getActiveProfile()->id,
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
        $data = Languages::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $user = $data->user;
        
        if(!is_null(Helper::getActiveProfile())){
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
        $data = Languages::where('id', $id)->where('user_id', auth()->id())->firstOrFail();  
              
        $proficiency = ProficiencyLevel::all();
        $configProficiency = [
            "placeholder" => "Select proficiency",
            "allowClear" => true,
        ];
        
        if(!is_null(Helper::getActiveProfile())){
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
        $lang = Languages::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $this->validate($request, [
            'name' => 'required',
        ]);

        $data['name'] = $request->input('name');
        $data['proficiency_id'] = $request->input('proficiency');
        $data['user_id'] = auth()->user()->id;
        $data['profile_id'] = Helper::getActiveProfile()->id;

        $update = $lang->update($data);

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
        $lang = Languages::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $lang->delete();
        flash(
            'Language deleted successfully',
        )->success();
        return redirect()->route('languages');
    }
}
