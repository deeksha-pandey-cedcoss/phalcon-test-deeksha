<?php

namespace MyApp\Handlers;

use Phalcon\Mvc\Application;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di\Injectable;
use Symfony\Contracts\EventDispatcher\Event;

class Listner extends Injectable
{
    public function beforeHandleRequest(Event $event, Application $app, Dispatcher $dis)
    {


        $acl = new Memory();


        $acl->addRole('manager');
        $acl->addRole('user');
        $acl->addRole('accountant');

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
        $role = "guest";
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
        // print_r($controller);die;
        if (!empty($dis->getActionName())) {
            $action = $dis->getActionName();
        }
        if (!empty($app->get('role'))) {
            $role = $app->get('role');
        }



        if (true === $acl->isAllowed($controller, $action, $role)) {
            echo 'Access granted!';
        } else {
            echo 'Access denied :(';
            echo die;
        }
    }
}
