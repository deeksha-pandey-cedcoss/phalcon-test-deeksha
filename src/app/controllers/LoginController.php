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

       $email=$this->request->getPost('email');
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
                $this->logger->error("Wrong Credentials for $email ");
                echo "Wrong credentials";
                $this->response->redirect("login");
            }
        }
    }

    public function logoutAction()
    {
        $this->session->destroy();
        $this->session->remove('auth');
        $this->response->redirect('login');
    }
}
