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
                'experiences' => $profile->experiences->sortByDesc(function ($exp, $key){ return Carbon::createFromFormat('m/Y',$exp['start']); }),
                'skills' => $profile->skills->shuffle(),
                'email' => $profile->email,
                'phone' => $profile->phone,
                'website' => $profile->website,
                'educations' => $profile->educations,
                'languages' => $profile->languages,
                'socials' => $profile->socials->shuffle(),
            ];
            if($profile->projects->count() > 0){
                $projects = $profile->projects->shuffle()->toArray();
                $interests = $profile->interests->shuffle()->toArray();
    
                foreach($projects as $project){
                    $interests_projects[] = array(
                        'name' => $project['name'],
                        'description' => $project['description'],
                    );
                }
    
                foreach($interests as $interest){
                    $interests_projects[] = array(
                        'name' => $interest['name'],
                    );
                }
                $data['projects'] = $interests_projects; 
            }else{
                $data['projects'] = array();
            } 
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
