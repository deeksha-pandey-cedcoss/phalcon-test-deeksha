<?php

namespace MyApp\Handlers;

use Phalcon\Mvc\Application;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di\Injectable;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;
use Phalcon\Events\Event;

class Listner extends Injectable
{
    public function beforeHandleRequest(Event $event, Application $app, Dispatcher $dis)
    {

        $acl = new Memory();

        $role = $_GET['role'];
        // echo $role;die;

        $acl->addRole('manager');
        $acl->addRole('user');
        $acl->addRole('accountant');
        $acl->addRole('admin');
        $acl->addRole('guest');

        $acl->addComponent(
            'index',
            [
                'index',

            ]
        );
        $acl->addComponent(
            'login',
            [
                'index',
                'login',
                'logout',
            ]
        );
        $acl->addComponent(
            'signup',
            [
                'index',
                'register',

            ]
        );
        $acl->addComponent(
            'product',
            [
                'index',
                'add',
                'addform',
            ]
        );
        $acl->addComponent(
            'order',
            [
                'index',
                'buynow',
                'show',
            ]
        );
        $acl->addComponent(
            'admin',
            [
                'index',
                'removeproduct',
                'removeuser',
                'changestatus',
                'editproduct',
                'update',
                'edituser',
                'updateuser',
                'updatesorder',
            ]
        );
        // $role = "guest";
        $controller = "index";
        $action = "index";

        $acl->allow('admin', '*', '*');
        $acl->allow('user', '*', '*');
        $acl->allow('manager', 'product', '*');
        $acl->allow('accountant', 'order', '*');
        $acl->allow('guest', 'product', 'index');

        if (!empty($dis->getControllerName())) {
            $controller = $dis->getControllerName();
        }

        if (!empty($dis->getActionName())) {
            $action = $dis->getActionName();
        }

        $tokenReceived = $role;
        $signer     = new Hmac();
        $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';


        $parser      = new Parser();
        $tokenObject = $parser->parse($tokenReceived);

        $validator = new Validator($tokenObject, 100);
        $validator
        
            
            ->validateSignature($signer, $passphrase);


            $rolejwt=$tokenObject->getClaims()->getPayload();
            echo $rolejwt;
        if (true === $acl->isAllowed($controller, $action, $role)) {
            echo 'Access granted!';
        } else {
            echo 'Access denied :(';
            echo die;
        }
    }
}
