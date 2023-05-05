<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;
use Orders;
use Users;
use Products;

class AdminController extends Controller
{
    public function indexAction()
    {
        $products = $this->db->fetchAll(
            "SELECT * FROM products",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $this->view->d = $products;

        $orders = $this->db->fetchAll(
            "SELECT * FROM orders",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $this->view->data = $orders;

        $users = $this->db->fetchAll(
            "SELECT * FROM users",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $this->view->dat = $users;
    }
    public function removeproductAction()
    {

        $id = $_GET['id'];
        $products = $this->db->fetchAll(
            "DELETE FROM `products` WHERE `id`=$id",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $this->view->d = $products;
    }
    public function removeuserAction()
    {
        $id = $_GET['id'];
        $users = $this->db->fetchAll(
            "DELETE FROM `users` WHERE `id`=$id",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );

        $this->response->redirect("admin/index");
    }
    public function changestatusAction()
    {
        print_r($_GET);
        // die;


        $id = $_GET['id'];

        $order = $this->db->fetchAll(
            "SELECT * FROM orders WHERE `id`=$id",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $this->view->data = $order;
    }

    public function editproductAction()
    {
        $id = $_GET['id'];
        $products = $this->db->fetchAll(
            "SELECT * FROM products WHERE `id`=$id",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        print_r($products);

        $this->view->d = $products;
    }
    public function updateAction()
    {
        $product = new Products();
        $product->assign(
            $input = array(

                "id" => $this->escaper->escapeHtml($this->request->getPost('id')),
                "name" => $this->escaper->escapeHtml($this->request->getPost('name')),
                "image" => $this->escaper->escapeHtml($this->request->getPost('image')),
                "price" => $this->escaper->escapeHtml($this->request->getPost('price')),
                "stock" => $this->escaper->escapeHtml($this->request->getPost('quantity')),

            )
        );
        $data = $this->db->execute(
            "UPDATE `products`
            SET `id`=\"$input[id]\",`name`=\"$input[name]\",`price`=\"$input[price]\",`stock`=\"$input[stock]\"
            WHERE `id`=$input[id]"
        );
        $this->response->redirect("admin/index");
    }

    public function edituserAction()
    {
        $id = $_GET['id'];

        $user = $this->db->fetchAll(
            "SELECT * FROM users WHERE `id`=$id",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $this->view->data = $user;
    }
    public function updateuserAction()
    {
        $users = new Users();
        $users->assign(
            $input = array(

                "id" => $this->escaper->escapeHtml($this->request->getPost('id')),
                "name" => $this->escaper->escapeHtml($this->request->getPost('name')),
                "role" => $this->escaper->escapeHtml($this->request->getPost('status')),
                "email" => $this->escaper->escapeHtml($this->request->getPost('email')),
                "pass" => $this->escaper->escapeHtml($this->request->getPost('pass')),

            )
        );
        $data = $this->db->execute(

            "UPDATE `users`
           SET `id`=\"$input[id]\",`name`=\"$input[name]\",`email`=\"$input[email]\",
           `password`=\"$input[pass]\",`role`=\"$input[role]\"
           WHERE `id`=$input[id]"

        );
        $this->response->redirect("admin/index");
    }
    public function updatesorderAction()
    {
        $order = new Orders();
        $order->assign(
            $input = array(

                "id" => $this->escaper->escapeHtml($this->request->getPost('idn')),
                "product_id" => $this->escaper->escapeHtml($this->request->getPost('id')),
                "user_id" => $this->escaper->escapeHtml($this->request->getPost('name')),
                "quantity" => $this->escaper->escapeHtml($this->request->getPost('quantity')),
                "status" => $this->escaper->escapeHtml($this->request->getPost('st')),

            )
        );
        $data = $this->db->execute(

            " UPDATE `orders`
           SET `user_id`=\"$input[user_id]\",`product_id`=\"$input[product_id]\",
           `quantity`=\"$input[quantity]\",`status`=\"$input[status]\"
           WHERE `id`=$input[id]"

        );
        $this->response->redirect("admin/index");
    }
}
