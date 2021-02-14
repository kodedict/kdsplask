<?php
use Kdsplask\Support\View\Template as ViewFactory;

if (! function_exists('view')) {

    function view($view, array $data = [])
    {
         $factory = new ViewFactory();
         return $factory->view($view, $data);
    }
}

if(! function_exists('env')) {
    function env($env, $default = ''): string
    {
       return $_ENV[$env] ?? $default;
    }
}
