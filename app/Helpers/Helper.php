<?php

namespace App\Helpers;
use App\Models\Attachments;
use App\Models\Profiles;
use App\Models\Visitors;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Str;

class Helper {
    public static function getActiveProfile()
    {
        $profile = Profiles::withTrashed()->where(['status' => 1])->first();
        return $profile;
    }
	
	public static function getVisitor($ip = NULL, $deepdetect = TRUE)
    {
		if(filter_var($ip,FILTER_VALIDATE_IP) === FALSE){
			$ip = $_SERVER['REMOTE_ADDR'];
			if($deepdetect){
				if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)){
                	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				}
	            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)){
	                $ip = $_SERVER['HTTP_CLIENT_IP'];
	            }
			}
		}
		if(isset($_SERVER['HTTP_REFERER'])){
			$data = array(
				'ip'	=>	$ip,
				'referrer'	=> $_SERVER['HTTP_REFERER'],
				'useragent'	=>	$_SERVER['HTTP_USER_AGENT'],
				'timestamp'	=> Carbon::now('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s e'),
			);
		}else{
			$data = array(
				'ip'	=>	$ip,
				'referrer'	=> '',
				'useragent'	=>	$_SERVER['HTTP_USER_AGENT'],
				'timestamp'	=> Carbon::now('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s e'),
			);
		}
		
		return $data;
	}

	public static function ipLocation($ip)
    {
		$geoip = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));
		$location = array(
			'userip' => $geoip['geoplugin_request'],
			'city' => $geoip['geoplugin_city'],
			'country' => $geoip['geoplugin_countryName'],
			'countryCode' => $geoip['geoplugin_countryCode'],
			'continent'	=> $geoip['geoplugin_continentName'],
			'continentCode' => $geoip['geoplugin_continentCode'],
			'timezone'	=> $geoip['geoplugin_timezone'],
			'latitude' => $geoip['geoplugin_latitude'],
			'longitude' => $geoip['geoplugin_longitude'],
		);
		return $location;
	}
	public static function visitor()
    {
		$visitor = Helper::getVisitor($_SERVER['REMOTE_ADDR']);
		$geoip = Helper::ipLocation($visitor['ip']);
		$data = array_merge($visitor,$geoip);
		return $data;
	}

    public static function saveVisitor()
    {
        $data = Helper::visitor();
        if(Visitors::create([
            'userip' => $data['ip'],
            'referrer' => $data['referrer'],
            'useragent' => $data['useragent'],
            'datetime' => $data['timestamp'],
            'city' => $data['city'],
            'country' => $data['country'],
            'countryCode' => $data['countryCode'],
            'continent' => $data['continent'],
            'continentCode' => $data['continentCode'],
            'timezone' => $data['timezone'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
        ])){
            return true;
        }else{
            return false;
        }
    }

    public static function convert_filesize($bytes, $decimals = 2){
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

	public static function createDirectory($dir)
	{
		if(!File::exists($dir)){
			mkdir($dir, 0755,true);
		}
		return;
	}
    public static function storeFile(Request $request, $fieldname, $dirname = 'uploads', $tag = 'default')
    {
        if(empty($request->file($fieldname))){
            return false;
        }else{
            $file = $request->file($fieldname);
            $fileExtension = $file->extension();
            $rawFileName = Carbon::Now()->timestamp.'_'.Str::random(10);
            $filename = $rawFileName.'.'.$fileExtension;

            // check folder exist & create if not
            Helper::createDirectory('storage/'.$dirname);
            // move the file
            $file->move('storage/'.$dirname, $filename);
			$sizeInByte = File::size(public_path('storage/'.$dirname.'/'.$filename));

            // create records in database
            $attachment = Attachments::create([
                'altname' => $rawFileName,
                'filename' => $filename,
                'path' => 'storage/'.$dirname,
                'type' => $fileExtension,
                'tag' => $tag,
				'filesize' => Helper::convert_filesize($sizeInByte),
                'user_id' => auth()->user()->id,
            ]);

            return $attachment;
        }
    }
	public static function json_validate($string)
	{
		// decode the JSON data
		$result = json_decode($string);

		// switch and check possible JSON errors
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				$error = ''; // JSON is valid // No error has occurred
				break;
			case JSON_ERROR_DEPTH:
				$error = 'The maximum stack depth has been exceeded.';
				break;
			case JSON_ERROR_STATE_MISMATCH:
				$error = 'Invalid or malformed JSON.';
				break;
			case JSON_ERROR_CTRL_CHAR:
				$error = 'Control character error, possibly incorrectly encoded.';
				break;
			case JSON_ERROR_SYNTAX:
				$error = 'Syntax error, malformed JSON.';
				break;
			// PHP >= 5.3.3
			case JSON_ERROR_UTF8:
				$error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
				break;
			// PHP >= 5.5.0
			case JSON_ERROR_RECURSION:
				$error = 'One or more recursive references in the value to be encoded.';
				break;
			// PHP >= 5.5.0
			case JSON_ERROR_INF_OR_NAN:
				$error = 'One or more NAN or INF values in the value to be encoded.';
				break;
			case JSON_ERROR_UNSUPPORTED_TYPE:
				$error = 'A value of a type that cannot be encoded was given.';
				break;
			default:
				$error = 'Unknown JSON error occured.';
				break;
		}

		if ($error !== '') {
			// throw the Exception or exit // or whatever :)
			return $error;
		}

		// everything is OK
		return $result;
	}

	public static function transformArray($old_array)
	{
		if(empty($old_array)) return;
		$new_array = array();
		foreach($old_array as $key => $value){
			foreach($value as $k => $v){
				$new_array[$k] = $v;
			}
			unset($old_array[$key]);
		}
		return $new_array;
	}
}