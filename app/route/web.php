<?php

use App\Controller\HomeController;
use Kdsplask\Routing\Route;

route::get('/', function (){
    (new HomeController())->index();
});

route::get('/about', function () {
    view('index');
});

route::get('/contact', function () {
    echo 'Contact Page';
});

route::get('/services', function () {
    echo 'Services Page';
});

