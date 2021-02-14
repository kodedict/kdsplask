<?php

namespace Kdsplask\HTTPCode;


class StatusCode
{

    public static function NOTFOUND()
    {
        http_response_code(404);
        if(!file_exists(__DIR__.'../../template/views/error/404.php')){
            return include(__DIR__.'/StatusPage/ClientErrorResponse/notFoundPage.php');
        }
        return include(__DIR__.'../../template/views/error/404.php');
    }
}
