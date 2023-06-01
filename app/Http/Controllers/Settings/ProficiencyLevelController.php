<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\ProficiencyLevel;
use Illuminate\Http\Request;

class ProficiencyLevelController extends Controller
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
        $this->title = 'Proficiency Level';
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array();
        $proficiencies = ProficiencyLevel::all();

        $heads = [
            'Proficiency',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($proficiencies as $proficiency){
            $data[] = array(
                $proficiency->proficiency,
                '<nobr><a href="'.url('settings_language/'.$proficiency->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('settings_language/'.$proficiency->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('settings_language/'.$proficiency->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null],
        ];

        return view('settings.proficiency.list', [
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
        return view('settings.proficiency.create', [            
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
            'proficiency' => 'required|unique:proficiency_levels',
        ]);

        $data = $request->all();

        $store = ProficiencyLevel::create([
            'proficiency' => $data['proficiency'],            
            'user_id' => auth()->user()->id,
        ]);

        if($store){
            flash(
                'Proficiency created successfully',
            )->success();
        }else{
            flash(
                'Unable to create proficiency',
            )->error();
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = ProficiencyLevel::where('id', $id)->firstOrFail();

        return view('settings.language.show', [
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
        $data = ProficiencyLevel::where('id', $id)->firstOrFail();

        return view('settings.language.edit', [
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
            'proficiency' => 'required|unique:settings_languages',
        ]);
        
        $data['proficiency'] = $request->input('proficiency');
        $data['user_id'] = auth()->user()->id;

        $update = ProficiencyLevel::find($id)->update($data);

        if($update){
            flash(
                'Proficiency updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update proficiency',
            )->error();
        }

         return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ProficiencyLevel::find($id)->delete();
        flash(
            'Proficiency deleted successfully',
        )->success();
        return redirect()->route('proficiencylevel');
    }
}
