<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\EducationLevel;
use Illuminate\Http\Request;

class EducationLevelController extends Controller
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
        $this->title = 'Education Level';
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array();
        $edulevels = EducationLevel::all();

        $heads = [
            'Name',
            'Level',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($edulevels as $edulevel){
            $data[] = array(
                $edulevel->name,
                $edulevel->level,
                '<nobr><a href="'.url('educationlevel/'.$edulevel->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('educationlevel/'.$edulevel->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('educationlevel/'.$edulevel->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null],
        ];

        return view('settings.education.list', [
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
        return view('settings.education.create', [            
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
            'name' => 'required|unique:education_levels',
            'level' => 'required',
        ]);

        $data = $request->all();

        $store = EducationLevel::create([
            'name' => $data['name'],
            'level' => $data['level'],
            'user_id' => auth()->user()->id,
        ]);

        if($store){
            flash(
                'Edulevel created successfully',
            )->success();
        }else{
            flash(
                'Unable to create edulevel',
            )->error();
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = EducationLevel::where('id', $id)->firstOrFail();

        return view('settings.education.show', [
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
        $data = EducationLevel::where('id', $id)->firstOrFail();

        return view('settings.education.edit', [
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
            'name' => 'required|unique:education_levels',
            'level' => 'required',
        ]);
        
        $data['name'] = $request->input('name');
        $data['level'] = $request->input('level');
        $data['user_id'] = auth()->user()->id;

        $update = EducationLevel::find($id)->update($data);

        if($update){
            flash(
                'Edulevel updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update edulevel',
            )->error();
        }

         return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        EducationLevel::find($id)->delete();
        flash(
            'Edulevel deleted successfully',
        )->success();
        return redirect()->route('educationlevel');
    }
}
