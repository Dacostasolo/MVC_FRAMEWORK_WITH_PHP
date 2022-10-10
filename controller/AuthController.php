<?php

namespace app\controller;

use app\core\Controller;
use app\core\Request;
use app\models\RegisterModel;
use app\models\Users;

class AuthController extends Controller
{


    public function login(Request $request)
    {
        if ($request->isPost()) {
            return "Handing Login form";
        }

        $this->setLayout('auth');
        return $this->render('login');
    }

    public function register(Request $request)
    {
        $this->setLayout('auth');
        $user = new Users();

        if ($request->isPost()) {
            $user->loadData($request->getBody());

            if ($user->validate() && $user->register()) {
                return "validation success";
            }

            return $this->render('register', ['model' => $user, 'name' => "dacosta"]);
        }


        return $this->render('register', ['model' => $user, 'name' => "dacosta"]);
    }
}
