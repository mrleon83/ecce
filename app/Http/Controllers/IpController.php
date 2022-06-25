<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Adrianorosa\GeoLocation\GeoLocation;

use DateTime;
use Carbon\Carbon;

use App\Models\IpAddresses;
use App\Models\WeatherData;

class IpController extends Controller
{
    /**
     * Return the view with data
     *
     * @param  string $customip
     * @param string $city
     * @return View
     */
    public function returnView(Request $request)
    {
        if( $request->customip !== NULL ) {
            $ip = $request->customip;
        } else {
           $ip = $request->ip();
        }

        $location_data = $this->getPlace($ip);
        if( $location_data !== null ) {
            $store_ip = $this->storeIp($ip, $location_data );
            $weather = $this->storeWeather($store_ip, $location_data);
        } else {
            $location_data['country'] = 'No data for this ip';
        }

        return $this->returnBlade($ip, $location_data, $weather, $store_ip );
    }



    /**
     * Return Blade template showing data
     */
    public function returnBlade($ip, $location_data, $weather,$store_ip ){
        return view('ippage', [  'ip' => $ip,
                                'location'      => $location_data['country'],
                                'weather'       => $weather,
                                'iprecord'      => $store_ip,
                            ] );
    }

    /**
     * Get Location data
     *
     * @param  string  $ip
     * @return Array
     */
    public function getPlace($ip){
       if( $details = GeoLocation::lookup($ip) ) {
        return [
            'city'      => $details->getCity(),
            'country'   => $details->getCountry(),
            'latitude'  => $details->getLatitude(),
            'longitude'  => $details->getLongitude(),
        ];
       } else {
        return 'No location data for this IP';
       }
    }

    /**
     * Get all weather data
     *
     * @param  string  $ip
     * @return Array
     */
    public function getAllDataForLocation($ip){
        $alldata = IpAddresses::with('weather_data')->where('ip_address', $ip)->get();
        return $alldata;
    }

    /**
     * Store IP data
     *
     * @param  string  $ip
     * @param  Array  $ip_data
     * @return Array
     */
    public function storeIp($ip, $ip_data){
        if( $ip_data['latitude'] !== null ) {
            $ipinput = IpAddresses::firstOrCreate(
                ['ip_address' => $ip],
                [   'latitude'  => $ip_data['latitude'],
                    'longitude' => $ip_data['longitude'],
                    'city'      => $ip_data['city'],
                    'country'   => $ip_data['country'],
                ]
            );
            return $ipinput->id;
        } else {
            return false;
        }

    }

    /**
     * Store weather data
     *
     * @param  string  $ip
     * @param  Array  $location_data
     * @return Array
     */
    public function storeWeather($ipid, $location_data) {
    $weatherdatatoday = WeatherData::where('ip_id', $ipid)->whereDate('created_at', Carbon::today())->first();
    if( $weatherdatatoday === null ) {
            $weatherdatatoday = $this->getWeather($location_data['latitude'], $location_data['longitude']);
            $weatherdata = new WeatherData;
            $weatherdata->ip_id = $ipid;
            $weatherdata->datefrom = Carbon::today();
            $weatherdata->weatherdata = json_encode( $weatherdatatoday );
            $weatherdata->save();
            return $weatherdata;
     } else {
            return $weatherdatatoday;
        }
    }

    /**
     * Weather Calls
     */


    /**
     * Get weather array
     *
     * @param  string  $url
     * @return Array
     */
     public function getDataFromUrl($url) {
        $jsonfile = file_get_contents($url);
        $jsondata = json_decode($jsonfile);
        $return_data = [];
        foreach( $jsondata->list as $data ) {
            $timestamp = strtotime($data->dt_txt);
            $day = date('l, F jS, Y', $timestamp);

            $return_data[] = [
                'day'          => $day ,
                'description'   => $data->weather[0]->description,
                'icon'          => $data->weather[0]->icon
            ];
        }

        return $return_data;
      }

    /**
     * Get weather data from API
     *
     * @param  string  $lat
     * @param  string  $long
     * @return Array
     */
     public function getWeather($lat, $lon) {
        $this->owapi = "9f512674c1a70aac97134228f03a630e";
        if( $lat && $lon ) {
            $url = "http://api.openweathermap.org/data/2.5/forecast?cnt=5&lat=". $lat ."&lon=". $lon ."&units=metric&appid=". $this->owapi;
            return  $this->getDataFromUrl($url);
        } else {
            return '';
        }
    }

    //Get icon code
    public function geticoncode($url) {
        $this->iconcode = $this->getDataFromUrl($url)->weather[0]->icon;
        return $this->iconcode;
    }
    // Get current temperature
    public function temperature($url) {
        $this->temp = $this->getDataFromUrl($url)->main->temp;
        return $this->temp;
    }


    // urgh not DRY
    public function returnApiData(Request $request)
    {
        if( $request->customip !== NULL ) {
            $ip = $request->customip;
        } else {
           $ip = $request->ip();
        }

        $location_data = $this->getPlace($ip);
        if( $location_data !== null ) {
            $store_ip = $this->storeIp($ip, $location_data );
            $weather = $this->storeWeather($store_ip, $location_data);
        } else {
            $location_data['country'] = 'No data for this ip';
        }

        return [ $ip, $location_data, $weather, $store_ip ];
    }

}



