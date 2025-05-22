<?php

namespace App\Core;

class Router
{
    protected static $handledURI = "";


    /**
     * @param string $URI - the URI to listen for get request
     * 
     * @param string $controller - controller class virtual pathname
     * 
     * @param string $method - controller method that will handle the request 
     */
    public static function get(string $URI, string $controller, string $method): void
    {
        $requestedURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        if ($_SERVER["REQUEST_METHOD"] === "GET" && $requestedURI === $URI) {
            if (class_exists($controller) && method_exists($controller, $method)) {
                self::$handledURI = $URI;
                call_user_func([new $controller, $method]);
            } else {
                trigger_error($controller . " controller or " . $method . " method dosent not exist!", E_USER_NOTICE);
            }
        }
    }

    /**
     * @param string $URI - the URI to listen for get request
     * 
     * @param string $controller - controller class virtual pathname
     * 
     * @param string $method - controller method that will handle the request 
     */
    public static function post(string $URI, string $controller, string $method): void
    {
        $requestedURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        if ($_SERVER["REQUEST_METHOD"] === "POST" && $requestedURI === $URI) {
            if (class_exists($controller) && method_exists($controller, $method)) {
                self::$handledURI = $URI;
                call_user_func([new $controller, $method]);
            } else {
                trigger_error($controller . " controller or " . $method . " method dosent not exist!", E_USER_NOTICE);
            }
        }
    }


    public static function notfound()
    {
        $requestedURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        if ($requestedURI === self::$handledURI) return;

        if (!count(headers_list())) header("Content-type:text/plain", true, 404);

        echo "not found";
    }
}
