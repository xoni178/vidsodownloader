<?php

namespace App\HTTP\Controllers;

use App\Core\Response;
use App\Core\Http;

class HomeController
{

    //handles home page
    public function index()
    {
        Response::view("index");
    }

    public function search()
    {
        $url = null;

        if (isset($_SESSION['q'])) $url = $_SESSION['q'];

        unset($_SESSION['q']);

        if (isset($url)) {
            $data = null;

            Http::fetchBasicInfo($url);


            Response::view("videos-displayer", ["data" => ""]);
        }
    }
}
