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


        Response::view("index", ["query" => $query]);
    }
}
