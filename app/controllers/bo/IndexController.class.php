<?php

// app/controllers/bo/IndexController.class.php


class IndexController extends Controller {


    public function indexAction(){

        if(empty($_SESSION['user']))
            $this->redirect('?a=login&p=bo');
        if(!empty($_SESSION['user'])){
            if(!$_SESSION['user']['isAdmin']){
                $this->redirect('?a=login&p=bo');
            }
        }
        $productModel = new ProductModel();

        $products = $productModel->getAll();

        // Load View template

        include  CURR_VIEW_PATH . "index.html";

    }


    public function loginAction(){

        include CURR_VIEW_PATH . "login.html";

    }

    public function registerAction(){

        include CURR_VIEW_PATH . "register.html";

    }

    public function forgotPasswordAction(){

        include CURR_VIEW_PATH . "forgotPassword.html";

    }

    public function resetPasswordAction($params){

        $token = $params['token'];
        $userModel = new UserModel();
        $tokenData = $userModel->getToken($token);

        if(!empty($tokenData)) $tokenData= $tokenData[0];

        if($tokenData && !empty($tokenData)){
            include CURR_VIEW_PATH . "resetPassword.html";
        }else{
            $_SESSION['message']="Invalid/expired Token Provided";
            $this->redirect('?p=bo');
        }


    }


}