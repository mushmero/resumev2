<?php

namespace App\Helpers;
use App\Models\Visitors;
use Carbon\Carbon;

class Helper {
    public static function getActiveProfile()
    {
        $profile = '';
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
}