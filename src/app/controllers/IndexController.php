<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        // return "HI";
        // default action
        $this->response->redirect("product");
    }
}
