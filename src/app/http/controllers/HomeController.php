<?php


namespace App\HTTP\Controllers;

use App\Core\Response;
use App\HTTP\Services\HomeService;

session_start();

class HomeController
{
    //handles home page
    public function index()
    {
        //unset($_SESSION['content']);
        Response::view("index");
        //print_r($_SESSION["content"]);
    }

    public function search()
    {
        $url = null;

        if (isset($_POST['q'])) $url = $_POST['q'];

        if (isset($url)) {
            $data = HomeService::fetchBasicInfo($url);

            if ($data) {
                if (!isset($_SESSION['content']) || !is_array($_SESSION['content'])) {
                    $_SESSION['content'] = [];
                }
                array_push($_SESSION["content"], $data);
            }
        }
        header("Location: /");
    }
}
