<?php

namespace App\HTTP\Services;

use App\Core\Http;

class HomeService
{

    public static function fetchBasicInfo(string $url)
    {
        $rawData = Http::fetchData($url);

        if ($rawData) {

            if (Http::$urlType === "youtube") {
                if (preg_match("/ytInitialPlayerResponse\s*=\s*({.+?});/", $rawData, $matches)) {
                    $json = $matches[1];
                    $data = json_decode($json, true);

                    if ($data) {
                        $videoAndSoundData = $data["streamingData"]["formats"][0];
                        print_r($data["videoDetails"]);

                        //Thumbnail
                        //Title
                        //length
                        //video url to download
                        //video resolutions 480p 720p 1080p
                        //sound url to download
                    }
                }
            }
        }
    }
}
