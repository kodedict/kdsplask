<?php


namespace App\Controller;

class HomeController
{

    public function index()
    {
        $data = [
            'name' => 'olumayokun',
            'message' => 'how are you today'
        ];
        return view('index',$data);
    }
}
