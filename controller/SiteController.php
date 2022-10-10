<?php

namespace app\controller;

use app\core\Controller;
use app\core\Request;

class SiteController extends Controller
{
    public  function home()
    {
        $params = [
            "name" => "Dacosta"
        ];

        return $this->render('home', $params);
    }
    public  function contact()
    {
        return $this->render('contact');
    }

    public  function handleContact(Request $request)
    {
        echo '<pre>'
            . var_dump($request->getBody())
            . '</pre>';
    }
}
