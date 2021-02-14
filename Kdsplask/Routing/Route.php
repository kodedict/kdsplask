<?php

namespace Kdsplask\Routing;

use Kdsplask\HTTPCode\StatusCode;
use Kdsplask\Routing\lib\Request;
use Kdsplask\Routing\lib\Response;

class Route
{
    private static $route = Array();

    public static  function get($route, $callback)
    {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') !== 0) {
            header("HTTP/1.0 405 Method Not Allowed");
            return;
        }

        array(array_push(self::$route,[
            'route'  => $route,
            'method' => 'GET',
        ]));
         self::on($route, $callback);
    }

    public static function post($route, $callback)
    {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
            header("HTTP/1.0 405 Method Not Allowed");
            return;
        }
        array(array_push(self::$route,[
            'route'  => $route,
            'method' => 'POST',
        ]));
        self::on($route, $callback);
    }


    public static function on($regex, $cb)
    {
        $params  = $_SERVER['REQUEST_URI'];
        $mainUrl = $_SERVER['REQUEST_URI'];
        $params  = (stripos($params, "/") !== 0) ? "/" . $params : $params;
        $regex   = str_replace('/', '\/', $regex);

        if(count(self::$route) == $_ENV['PAGE_COUNT']){

            $pageNotFound = true;
            foreach (self::$route as $route){
                for($x = 0; $x< count(self::$route); $x++){
                    if($route['route'] == $mainUrl){
                        $pageNotFound = false;
                        break;
                    }
                }
            }
            /*
             * Redirects to 404
             */
            if($pageNotFound){
                StatusCode::NOTFOUND();
            }
        }

        $is_match = preg_match('/^' . ($regex) . '$/', $params, $matches, PREG_OFFSET_CAPTURE);

        if ($is_match) {
            // first value is normally the route, lets remove it
            array_shift($matches);
            // Get the matches as parameters
            $params = array_map(function ($param) {
                return $param[0];
            }, $matches);

            $cb(new Request($params), new Response());
        }

    }

}
