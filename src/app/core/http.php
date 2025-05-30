<?php


namespace App\Core;

class Http
{

    public static string $urlType;
    /**
     * Validates the url and checks what type of url it is.
     * 
     * @param $url 
     */
    public static function validateUrl(string $url): bool
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {

            //For now only youtube types are supported
            $youtubePattern = '/^(https?:\/\/)?(www\.)?(youtube\.com\/(watch\?v=|shorts\/)|youtu\.be\/)[\w\-]{11}.*$/i';

            if (preg_match($youtubePattern, $url)) {
                self::$urlType = "youtube";
            } else {
                self::$urlType = "";
            }

            return true;
        } else {
            echo $url . " - is not a valid url";
            return false;
        }
    }

    public static function fetchData(string $url, array $params = []): string|null
    {
        if (!self::validateUrl($url)) return null;

        $session = curl_init($url);

        if (curl_errno($session)) {
            //TODO: Error Handeling
            echo curl_error($session);
            return null;
        }
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_COOKIE, false);
        curl_setopt($session, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
        curl_setopt($session, CURLOPT_HTTPHEADER, [
            'Accept-Language: en-US,en;q=0.9',
            'Accept: text/html'
        ]);

        $rawData = curl_exec($session);

        curl_close($session);

        return $rawData;
    }
}
