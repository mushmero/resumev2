<?php

namespace App\Http\Controllers\Module;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Profiles;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{

    protected $btnEdit, $btnDelete, $btnDetails, $btnRestore, $title;

    /**
     * Declare default template for button edit, button delete & button view
     */
    public function __construct()
    {
        $this->middleware('autologout');
        $this->btnEdit = config('lapdash.btn-edit');
        $this->btnDelete = config('lapdash.btn-delete');
        $this->btnDetails = config('lapdash.btn-view');
        $this->btnRestore = config('lapdash.btn-restore');
        $this->title = 'Profiles';
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array();
        $array = Profiles::withTrashed()->where(['user_id' => auth()->user()->id])->get();

        $heads = [
            'Profile',
            'Status',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            
            $data[] = array(
                '<div class="row"><div class="col-1"><img src="'.url($arr->attachments->path.'/'.$arr->attachments->filename).'" alt="'.$arr->attachments->altname.'" width="50" height="50" class="img-center"></div><div class="col-11">'.$arr->fullname.'&nbsp;'.(!empty($arr->tagline) ? '('.$arr->tagline.')' : '').(!empty($arr->phone) ? '<br><i class="fa-fw fas fa-mobile-alt"></i>&nbsp;'.$arr->phone : '').(!empty($arr->email) ? '&nbsp;<i class="fa-fw fas fa-envelope"></i>&nbsp;'.$arr->email : '').(!empty($arr->website) ? '<br><i class="fa-fw fas fa-globe"></i>&nbsp;'.$arr->website : '').'</div></div>',
                '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="activeSwitch'.$arr->id.'" name="activeSwitch'.$arr->id.'" onchange="switchAjax('.$arr->id.')" '.($arr->status == 1 ? "checked" : "").' '.(auth()->user()->id != $arr->user_id ? 'disabled' : '').' /><label class="active_switch custom-control-label" for="activeSwitch'.$arr->id.'">'.($arr->status == 1 ? "Active" : "Inactive").'</label></div>',
                $arr->user->name,
                '<nobr><a class="'.(!$arr->trashed() ? 'disabled' : '').'" href="'.url('profiles/'.$arr->id.'/restore').'">'.$this->btnRestore.'</a><a class="'.($arr->trashed() ? 'disabled' : '').'" href="'.url('profiles/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a class="'.($arr->trashed() ? 'disabled' : '').'" href="'.url('profiles/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a class="'.($arr->trashed() ? 'disabled' : '').'" href="'.url('profiles/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null],
        ];

        return view('modules.profiles.list', [
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
        return view('modules.profiles.create', [
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
            'fullname' => 'required',
            'profilephoto' => 'image|mimes:png,jpg|max:5120',
        ]);

        $data = $request->all();

        // process upload file
        $attachmentID = '';
        if($request->file('profilephoto')){
            $uploadedFile = Helper::storeFile($request, 'profilephoto', 'profilephoto', 'profilephoto');
            if($uploadedFile){
                $attachmentID = $uploadedFile->id;
            }else{
                $attachmentID = '';
            }
        }

        $store = Profiles::create([
            'fullname' => $data['fullname'],
            'tagline' => $data['tagline'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'website' => $data['website'],
            'attachment_id' => $attachmentID,
            'user_id' => auth()->user()->id,
        ]);

        if($store){
            flash(
                'Profile created successfully',
            )->success();
        }else{
            flash(
                'Unable to create Profile',
            )->error();
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Profiles::where('id', $id)->firstOrFail();

        $user = $data->user;

        return view('modules.profiles.show', [
            'data' => $data,
            'user' => $user,
            'title' => $this->title,
            'table_title' => 'Detail '.$this->title,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Profiles::where('id', $id)->firstOrFail();

        return view('modules.profiles.edit', [
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
            'fullname' => 'required',       
            'profilephoto' => 'image|mimes:png,jpg|max:5120',
        ]);

        if($request->file('profilephoto')){
            $uploadedFile = Helper::storeFile($request, 'profilephoto', 'profilephoto', 'profilephoto');
            if($uploadedFile){
                $attachmentID = $uploadedFile->id;
                $data['attachment_id'] = $attachmentID;
            }
        }

        $data['fullname'] = $request->input('fullname');
        $data['tagline'] = $request->input('tagline');
        $data['phone'] = $request->input('phone');
        $data['email'] = $request->input('email');
        $data['website'] = $request->input('website');
        $data['user_id'] = auth()->user()->id;

        $update = Profiles::find($id)->update($data);

        if($update){
            flash(
                'Profile updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update Profile',
            )->error();
        }

         return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = [
            'deleted_at' => Carbon::now(),
            'deleted_by' => auth()->user()->id,
        ];
        Profiles::where('id', $id)->update($delete);

        flash(
            'Profile deleted successfully',
        )->success();
        return redirect()->route('profiles');
    }

    public function restore($id)
    {
        Profiles::withTrashed()->where('id',$id)->update(['deleted_by' => '']);
        Profiles::withTrashed()->where('id',$id)->restore();

        flash(
            'Profile restore successfully',
        )->success();
        return redirect()->route('profiles');
    }

    public function countstatus()
    {
        $count = Profiles::where(['status' => 1, 'user_id' => auth()->user()->id])->get()->count();;
        return $count;
    }

    public function checkStatusById($id)
    {
        $result = Profiles::where(['id' => $id, 'status' => 1, 'user_id' => auth()->user()->id])->get();
        if($result->count() > 0){
            return response()->json(['exist' => 1, 'status' => 1]);
        }else{
            return response()->json(['exist' => 0, 'status' => 1]);
        }
    }

    public function updateStatusById(Request $request)
    {
        $id = $request->input('id');
        $data['status'] = $request->input('status');
        Profiles::find($id)->update($data);
        return true;
    }
}
