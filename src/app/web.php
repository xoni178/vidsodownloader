<?php

use App\Core\Router;
use App\HTTP\Controllers\HomeController;


Router::get("/", HomeController::class, "index");

Router::post("/", HomeController::class, "search");

Router::post("/reset", HomeController::class, "reset");

Router::get("/download", HomeController::class, "download");

Router::notfound();
