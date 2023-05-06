<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;
use Orders;

class OrderController extends Controller
{
    public function indexAction()
    {

        // default action


    }
    public function buynowAction()
    {

        $id = $_GET['id'];
        $user_id = $this->session->get('user-id');

    
        $robots = $this->db->fetchAll(
            "SELECT * FROM products WHERE `id`='$id'",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $user = $this->db->fetchAll(
            "SELECT `id` FROM users WHERE `id`='$user_id'",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );

        $order = $this->db->fetchAll(
            "SELECT * FROM `orders` WHERE `product_id`='$id'",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );



        foreach ($order as $key => $value) {
            if ($value['product_id'] == $id) {
                $q = $value["quantity"] + 1;
                $this->db->execute(
                    "UPDATE `orders` SET `quantity`=$q WHERE `product_id`=$id"
                );
            }
        }
        $this->view->data = $robots;
    }
    public function showAction()
    {
        $robots = $this->db->fetchAll(
            "SELECT * FROM orders ",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $this->view->data = $robots;
    }
}
