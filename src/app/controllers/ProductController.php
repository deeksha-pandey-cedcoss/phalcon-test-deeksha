<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;
use Products;

class ProductController extends Controller
{
    public function indexAction()
    {
        $robots = $this->db->fetchAll(
            "SELECT * FROM products",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $this->view->data = $robots;
    }
    public function addAction()
    {
        //    form to add product
    }
    public function addformAction()
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


        $success = $product->save();

        if ($success) {
            echo "Added successfully";
            $this->response->redirect("product");
        } else {

            echo "not Added successfully";
            die;
        }
    }
}
