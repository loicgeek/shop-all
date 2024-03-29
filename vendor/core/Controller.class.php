<?php

// Base Controller

class Controller{

    // Base Controller has a property called $loader, it is an instance of Loader class(introduced later)

    protected $loader;


    public function __construct(){

        $this->loader = new Loader();

    }


    public function redirect($url,$message=null,$wait = 0){

        if ($wait == 0){

            header("Location:$url");

        } else {

            include CURR_VIEW_PATH . "message.html";

        }

        exit;

    }

    public function redirectBack($wait = 0){

        if ($wait == 0){

            header("Location:".$_SERVER['HTTP_REFERER']);

        } else {

            include CURR_VIEW_PATH . "message.html";

        }

        exit;

    }

}

