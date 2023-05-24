<?php

namespace App\Http\Controllers\Welcome;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Helper::saveVisitor();
        $profile = Helper::getActiveProfile();
        if($profile != ''){
            $data = [
                'profile-image' => $profile->attachments->path.'/'.$profile->attachments->filename,
                'name' => $profile->fullname,
                'position' => $profile->tagline,
                'experiences' => $profile->experiences->sortByDesc(function ($exp, $key){ return Carbon::createFromFormat('d/m/Y',$exp['start']); }),
                'skills' => $profile->skills->shuffle(),
                'email' => $profile->email,
                'phone' => $profile->phone,
                'website' => $profile->website,
                'educations' => $profile->educations,
                'languages' => $profile->languages,
                'projects' => '',
                'socials' => $profile->socials->shuffle(),
            ];
            return view('welcome.content', compact('data'));
        }else{
            $data = [
                'profile-image' => 'assets/images/hello_v2.png',
                'name' => 'hello',
            ];
            return view('welcome.landing', compact('data'));
        }
    }
}
