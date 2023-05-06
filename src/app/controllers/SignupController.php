<?php

namespace MyApp\Controllers;

use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;

use Phalcon\Mvc\Controller;
use Users;

class SignupController extends Controller
{
    public function indexAction()
    {
        //    default action
    }

    public function registerAction()
    {
        $user = new Users();

        $user->assign(
            $input = array(

                "name" => $this->escaper->escapeHtml($this->request->getPost('name')),
                "email" => $this->escaper->escapeHtml($this->request->getPost('email')),
                "password" => $this->escaper->escapeHtml($this->request->getPost('password')),
                "role" => $this->escaper->escapeHtml($this->request->getPost('role')),


            )
        );



        $success = $user->save();
        $signer  = new Hmac();
        $builder = new Builder($signer);
        $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';

        $builder
            ->setSubject($input['role'])
            ->setPassphrase($passphrase);
        $tokenObject = $builder->getToken();

        $role = $tokenObject->getToken();

        if ($success) {
            $this->response->redirect("login/index?role=" . $role);
        } else {
            echo "Not registered successfully";
            die;
        }
    }
}
