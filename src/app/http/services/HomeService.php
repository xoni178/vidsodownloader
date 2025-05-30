<?php

namespace App\HTTP\Services;

use App\Core\Http;

class HomeService
{

    public static function fetchBasicInfo(string $url): array|null
    {
        //TODO: structure fetch data correctly on $_SESSION

        $rawData = Http::fetchData($url);

        if ($rawData) {

            if (Http::$urlType === "youtube") {
                $videoId = null;
                //check if youtube link is from youtube shorts or not
                if (preg_match('#^https?://(www\.)?youtube\.com/shorts/[a-zA-Z0-9_-]+#', $url)) {
                    //its shorts
                    $temp = parse_url($url, PHP_URL_PATH);
                    $temp2 = explode('/', $temp);
                    $videoId = array_pop($temp2);
                } else {
                    //isnt short
                    $queries = parse_url($url, PHP_URL_QUERY);
                    $temp = explode('&', $queries)[0];
                    $videoId = substr($temp, 2);
                }

                if (self::checkIfVideoExistsInSession($videoId)) {
                    return null;
                }
                return self::youtubeTypeFetchData($rawData);
            }
        }
        return null;
    }

    private static function youtubeTypeFetchData(string $rawData): array|null
    {
        if (preg_match("/ytInitialPlayerResponse\s*=\s*({.+?});/", $rawData, $matches)) {
            $json = $matches[1];
            $data = json_decode($json, true);

            if ($data) {
                $videoAndSoundData = $data["streamingData"]["formats"][0];
                $videoDetails = $data["videoDetails"];


                return array(
                    "videoId" => $videoDetails["videoId"],
                    "title" => $videoDetails["title"],
                    "length" => $videoDetails["lengthSeconds"],
                    "thumbnail" => $videoDetails["thumbnail"]["thumbnails"][1]["url"],
                    "download_url" => $videoAndSoundData["url"],
                    "mimeType" => $videoAndSoundData["mimeType"]

                );
            }
        }
        return null;
    }
    private static function checkIfVideoExistsInSession(string $videoId)
    {
        if (isset($_SESSION["content"])) {
            foreach ($_SESSION["content"] as $video) {
                if ($videoId === $video["videoId"]) {
                    return true;
                    break;
                }
            }
            return false;
        }
    }
}
