<?php

use App\Core\Router;
use App\HTTP\Controllers\HomeController;


Router::get("/", HomeController::class, "index");

Router::get("/", HomeController::class, "search");

Router::notfound();
