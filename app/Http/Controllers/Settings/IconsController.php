<?php

namespace App\Http\Controllers\Settings;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Attachments;
use App\Models\Icons;
use File;
use Illuminate\Http\Request;
use Log;

class IconsController extends Controller
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
        $this->title = 'Icons';
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array();
        $socials = Icons::all();

        $heads = [
            'Icon Name',
            'Type',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($socials as $social){
            $data[] = array(
                '<i class="fa-fw '.$social->fullname.'"></i>&nbsp;'.$social->name,
                $social->type,
                '<nobr><a href="'.url('icons/'.$social->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('icons/'.$social->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('icons/'.$social->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null],
        ];

        return view('settings.icons.list', [
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
        return view('settings.icons.create', [            
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
            'fullname' => 'required|unique:icons',
            'name' => 'required|unique:icons',
            'type' => 'required',
        ]);

        $data = $request->all();

        $store = Icons::create([
            'fullname' => $data['fullname'],
            'name' => $data['name'],
            'type' => $data['type'],
        ]);

        if($store){
            flash(
                'Icon created successfully',
            )->success();
        }else{
            flash(
                'Unable to create Icon',
            )->error();
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Icons::where('id', $id)->firstOrFail();

        return view('settings.icons.show', [
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
        $data = Icons::where('id', $id)->firstOrFail();

        return view('settings.icons.edit', [
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
            'fullname' => 'required|unique:icons',
            'name' => 'required|unique:icons',
            'type' => 'required',
        ]);
        
        $data['fullname'] = $request->input('fullname');
        $data['name'] = $request->input('name');
        $data['type'] = $request->input('type');

        $update = Icons::find($id)->update($data);

        if($update){
            flash(
                'Icon updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update icon',
            )->error();
        }

         return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Icons::find($id)->delete();
        flash(
            'Icon deleted successfully',
        )->success();
        return redirect()->route('icons');
    }

    public function import(Request $request)
    {
        $uploaded_file = $request->file('iconfile');
        if($uploaded_file && !is_null($uploaded_file->extension())){            
            $uploadedFile = Helper::storeFile($request, 'iconfile', 'icons', 'icons');
            if($uploadedFile){
                $process = $this->processImportFile($uploadedFile);
                return $process;
            }else{
                return response()->json(['error' => 'Error invalid file']);
            }
        }
    }

    public function processImportFile($file)
    {
        if(is_null($file)) return response()->json(['error' => 'Error! Uploaded file invalid']);
        $filepath = public_path($file->path.'/'.$file->filename);

        if(File::exists($filepath)){
            $recordCount = 0;
            $successCount = 0;
            $failedCount = 0;
            $icons = Helper::json_validate(File::get($filepath));
            if(is_object($icons) && count((array)$icons) != 0){
                foreach($icons as $icon){
                    $recordCount++;
                    $iconExist = Icons::where('name', $icon->name)->first();
    
                    if(is_null($iconExist)){
                        Icons::create([
                            'fullname' => $icon->type.' fa-'.$icon->name,
                            'name' => $icon->name, 
                            'type' => $icon->type,
                            'user_id' => auth()->user()->id,
                        ]);
                        $successCount++;
                    }else{
                        $failedCount++;
                        Log::info('Icon exist: '.$icon->name);
                    }
                }
            }else{
                $errMsg = !empty((array)$icons) ? $icons : 'Empty';
                return response()->json(['error' => 'Invalid JSON. Error message: '.$errMsg]);
            }
            return response()->json([
                'success' => 'Icon import finish',
                'recordCount' => $recordCount,
                'successCount' => $successCount,
                'failedCount' => $failedCount,
            ]);
        }else{
            return response()->json(['error' => 'File not exist']);
        }
    }
}
