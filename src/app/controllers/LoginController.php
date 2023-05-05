<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;
use Users;

class LoginController extends Controller
{
    public function indexAction()
    {

        // default action

    }
    public function loginAction()
    {

        // $user = new Users();

        if ($this->request->isPost()) {
            $user = Users::findFirst(array(
                'email = :email: and password = :password:', 'bind' =>
                array(
                    'email' => $this->request->getPost("email"), 'password' => $this->request->getPost("password")
                )
            ));

            if ($user) {
                $this->session->set('user-id', $user->id);
                $this->response->redirect("product");
            } else {
                echo "Wrong credentials";
                die;
            }
        }
    }

    public function logoutAction()
    {
        $this->session->destroy();
    }
}
