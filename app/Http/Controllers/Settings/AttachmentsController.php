<?php

namespace App\Http\Controllers\Settings;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Attachments;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttachmentsController extends Controller
{
    protected $btnEdit, $btnDelete, $btnDetails;

    /**
     * Declare default template for button edit, button delete & button view
     */
    public function __construct()
    {
        $this->middleware('autologout');
        $this->btnEdit = config('lapdash.btn-edit');
        $this->btnDelete = config('lapdash.btn-delete');
        $this->btnDetails = config('lapdash.btn-view');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Attachments';
        $data = array();
        $attachments = Attachments::all();

        $heads = [
            'Filename',
            'Type',
            'Filesize',
            'Created At'
        ];

        foreach($attachments as $single){
            $data[] = array(
                $single->filename,
                $single->type,
                Helper::convert_filesize($single->filesize),
                Carbon::parse($single->created_at)->format('d F Y'),
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null],
        ];

        return view('settings.attachments.list', [
            'heads' => $heads,
            'config' => $config,
            'title' => $title,
            'table_title' => $title.' List',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
