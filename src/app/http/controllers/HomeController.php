<?php

namespace App\HTTP\Controllers;

use App\Core\Response;

class HomeController
{

    //handles home page
    public function index()
    {
        Response::view("index");
    }

    public function search()
    {
        $query = $_GET["q"];
        $regex = '/^(https?:\/\/)?(www\.)?(youtube\.com)(\/[\w\-?=&%#.]*)?$/i';
        if (preg_match($regex, $query)) {
            $url = $query;
            $session = curl_init($url);

            if (curl_errno($session)) {
                print_r(curl_error($session));
            }
            global $data;

            $data = curl_exec($session);
            curl_close($session);
            Response::view("index", ["data" => "hiiiii"]);
        }
    }
}
