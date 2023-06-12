<?php

namespace App\Http\Controllers;

use App\Models\Educations;
use App\Models\Experiences;
use App\Models\Interests;
use App\Models\Languages;
use App\Models\Profiles;
use App\Models\Projects;
use App\Models\Skills;
use App\Models\Socials;
use App\Models\Visitors;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use PragmaRX\Countries\Package\Countries;

class HomeController extends Controller
{
    protected $title, $error_msg;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('autologout');
        $this->title = 'Home';
        $this->error_msg = [
            'inactive_profile' => 'No active profile. Please activate first',
        ];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Helper::getActiveProfile()->count() > 0){
            return view('home', [
                'inspiration' => $this->getQuotes(),
                'profile' => $this->getProfile(),
                'experience' => $this->getExperience(),
                'skill' => $this->getSkills(),
                'language' => $this->getLanguages(),
                'education' => $this->getEducations(),
                'interest' => $this->getInterests(),
                'project' => $this->getProjects(),
                'social' => $this->getSocials(),
            ]);
        }else{
            return view('no-access',[
                'error_msg' => $this->error_msg['inactive_profile'],
            ]);
        }
    }

    public function getQuotes()
    {
        return Inspiring::quotes()->random();
    }

    public function getProfile()
    {
        $profile = Profiles::where(['user_id' => auth()->user()->id, 'status' => 1])->firstOrFail();
        $data = array(
            'fullname' => isset($profile->fullname) ? $profile->fullname : '',
            'tagline' => isset($profile->tagline) ? $profile->tagline : '',
            'phone' => isset($profile->phone) ? $profile->phone : '',
            'email' => isset($profile->email) ? $profile->email : '',
            'profile_image' => isset($profile->attachment_id) ? url($profile->attachments->path.'/'.$profile->attachments->filename) : '',
        );
        return $data;
    }

    public function getExperience()
    {
        $experiences = Experiences::where('user_id',auth()->user()->id)->get();
        $data = array();
        $data['length'] = 0;
        foreach($experiences as $experience){
            $data['position'][] = $experience->position;
            $data['employer'][] = $experience->employer;
            $data['length'] = Carbon::now()->format('Y') - ($experiences->min('start') ? Carbon::parse($experiences->min('start'))->format('Y') : Carbon::now()->format('Y'));
        }
        $data['count'] = isset($experiences) ? $experiences->count() : 0;
        return $data;
    }

    public function getSkills()
    {
        $skills = Skills::where('user_id',auth()->user()->id)->get();
        $data = array();
        foreach($skills as $skill){
            $data['name'][] = $skill->name;
            $data['percentage'][] = $skill->percentage;
        }
        $data['count'] = isset($skills) ? $skills->count() : 0;
        return $data;
    }

    public function getEducations()
    {
        $educations = Educations::where('user_id',auth()->user()->id)->get();
        $data = array();
        foreach($educations as $education){
            $data['name'][] = $education->name;
            $data['institution'][] = $education->institution;
            $data['level'][] = $education->edulevels->name;
        }
        $data['count'] = isset($educations) ? $educations->count() : 0;
        return $data;
    }

    public function getLanguages()
    {
        $languages = Languages::where('user_id',auth()->user()->id)->get();
        $data = array();
        foreach($languages as $language){
            $data['name'][] = $language->name;
            $data['proficiency'][] = $language->proficiency->proficiency;
        }
        $data['count'] = isset($languages) ? $languages->count() : 0;
        return $data;
    }

    public function getInterests()
    {
        $interests = Interests::where('user_id',auth()->user()->id)->get();
        $data = array();
        foreach($interests as $interest){
            $data['name'][] = $interest->name;
        }
        $data['count'] = isset($interest) ? $interests->count() : 0;
        return $data;
    }

    public function getProjects()
    {
        $projects = Projects::where(['user_id' => auth()->user()->id])->get();
        $data = array();
        foreach($projects as $project){
            $data['name'][] = $project->name;
            $data['status'][] = $project->status->status;
        }
        $data['count'] = isset($projects) ? $projects->count() : 0;
        return $data;
    }

    public function getSocials()
    {
        $socials = Socials::where(['user_id' => auth()->user()->id])->get();
        $data = array();
        foreach($socials as $social){
            $data['name'][] = $social->name;
            $data['status'][] = $social->link;
            $data['icon'][] = $social->icons->fullname;
        }
        $data['count'] = isset($socials) ? $socials->count() : 0;
        return $data;
    }

    function getCountryList()
    {
        $countries = Countries::all()
            ->map(function ($country) {
                $commonName = $country->name->common;
            
                $languages = $country->languages ?? collect();
            
                $language = $languages->keys()->first() ?? null;
            
                $nativeNames = $country->name->native ?? null;
            
                if (
                    filled($language) &&
                        filled($nativeNames) &&
                        filled($nativeNames[$language]) ?? null
                ) {
                    $native = $nativeNames[$language]['common'] ?? null;
                }
            
                if (blank($native ?? null) && filled($nativeNames)) {
                    $native = $nativeNames->first()['common'] ?? null;
                }
            
                $native = $native ?? $commonName;
            
                if ($native !== $commonName && filled($native)) {
                    $native = "$native ($commonName)";
                }
            
                return [$country->cca2 => $native];
            })
            ->values()->toArray();
            $countries = Helper::transformArray($countries);
        return Json::encode($countries);
    }
    
    function getVisitorByCountry()
    {
        $visitors = Visitors::selectRaw('count(*) as visitor, country, countryCode, MIN(latitude) as latitude, MIN(longitude) as longitude')
        ->whereNotNull('country')
        ->whereNotNull('countryCode')
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->groupBy('country', 'countryCode')
        ->get();

        if($visitors->count() == 0) return false;
    
        foreach($visitors as $key => $value){
            $visitor[] = (int)$value->visitor;
            $country[] = $value->country;
            $countryCode[] = $value->countryCode;
            $latitude[] = $value->latitude;
            $longitude[] = $value->longitude;
        }
        
        $data = array(
            'data' => Json::encode($visitors),
            'info' => Json::encode(
                array(
                    'visitor' => $visitor,
                    'country' => $country,
                    'countryCode' => $countryCode,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                )
            ),
        );
        return Json::encode($data);
    }
}
