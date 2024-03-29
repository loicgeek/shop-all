<?php

// app/controllers/bo/IndexController.class.php


class IndexController extends Controller {

    public function mainAction(){

        include CURR_VIEW_PATH . "main.html";

        // Load Captcha class

//        $this->loader->library("Captcha");
//
//        $captcha = new Captcha;
//
//        $captcha->hello();

        $userModel = new UserModel();

        $users = $userModel->getUsers();

    }

    public function indexAction(){

        $productModel = new ProductModel();
        $products = $productModel->getAll();
        $trash=array();

        if(!empty($_SESSION['user'])) {
            $trashModel = new TrashModel();
            $trash = $trashModel->getTrashByUserId($_SESSION['user']['id']);
            if($trash && $trash[0]==false){
                unset($trash[0]);
            }
        }

        // Load View template

        include  CURR_VIEW_PATH . "index.html";

    }

    public function loginAction(){

        include CURR_VIEW_PATH . "login.html";

    }


    public function registerAction(){

        include CURR_VIEW_PATH . "register.html";

    }


    public function draftAction(){

        include CURR_VIEW_PATH . "draft.html";

    }

    public function addToTrashAction($params) {
        if(!empty($params['product_id'])){
            if(!empty($_SESSION['user'])){
                $trashModel = new TrashModel();
                if(!$trashModel->exists($_SESSION['user']['id'],$params['product_id'])){
                    $trashModel->insert(['product_id'=>$params['product_id'],'user_id'=>$_SESSION['user']['id']]);
                }

                $this->redirect('?');
            } else{
                $this->redirect('?a=login');
            }
        }
    }

    public function rmFromTrashAction($params) {

        if(!empty($params['product_id'])){

            if(!empty($_SESSION['user'])){
                $trashModel = new TrashModel();
                $res =$trashModel->deleteFromTrash($_SESSION['user']['id'],$params['product_id']);

                $this->redirect('?');

            }
        }
    }

    public function logoutAction(){
        unset($_SESSION['user']);
        $this->redirect('?');
    }


}