<?php

// app/controllers/bo/IndexController.class.php


class ProductController extends Controller {

    public function addAction(){

        include CURR_VIEW_PATH . "addproduct.html";

        // Load Captcha class

//        $this->loader->library("Captcha");
//
//        $captcha = new Captcha;
//
//        $captcha->hello();

        $userModel = new UserModel("users");

        $users = $userModel->getUsers();

    }

    public function updateAction($params) {
        if(empty($params['name']) || empty($params['price']) || empty($params['description'])) {
            $_SESSION['message'] = 'Field(s) is(are) missing';
            $this->redirectBack();
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // get details of the uploaded file
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileType = $_FILES['image']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $allowedfileExtensions = array('jpg', 'gif', 'png');
            if (in_array($fileExtension, $allowedfileExtensions)) {

                $dest_path = UPLOAD_PATH . $newFileName;

                if(move_uploaded_file($fileTmpPath, $dest_path))
                {
                    $productModel = new ProductModel();
                    $productModel->update(['image'=>'public/uploads/'.$newFileName,'name'=>addslashes($params['name']),'price'=>$params['price'],'description'=>addslashes($params['description'])]);
                    $this->redirect('?p=bo',null);
                }else {
                    $_SESSION['message'] = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
                    $this->redirectBack();
                }
            }else {
                $_SESSION['message'] = 'File type not allowed';
                $this->redirectBack();
            }
        } else {
            $productModel = new ProductModel();
            $productModel->update(['id'=>$params['product_id'],'name'=>addslashes($params['name']),'price'=>$params['price'],'description'=>addslashes($params['description'])]);
            $this->redirect('?p=bo',null);
        }
    }

    public function editAction($params){
        $productModel = new ProductModel();
        $product = $productModel->selectByPk($params['product_id']);
        include CURR_VIEW_PATH . "editproduct.html";
    }

    public function saveAction($params) {

        if(empty($params['name']) || empty($params['price']) || empty($params['description'])) {
            $_SESSION['message'] = 'Field(s) is(are) missing';
           $this->redirectBack();
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // get details of the uploaded file
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileType = $_FILES['image']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $allowedfileExtensions = array('jpg', 'gif', 'png');
            if (in_array($fileExtension, $allowedfileExtensions)) {

                $dest_path = UPLOAD_PATH . $newFileName;

                if(move_uploaded_file($fileTmpPath, $dest_path))
                {
                    $productModel = new ProductModel();
                    $productModel->insert(['image'=>'public/uploads/'.$newFileName,'name'=>addslashes($params['name']),'price'=>$params['price'],'description'=>addslashes($params['description'])]);
                    $this->redirect('?p=bo',null);
                }else {
                    $_SESSION['message'] = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
                    $this->redirectBack();
                }
            }else {
                $_SESSION['message'] = 'File type not allowed';
                $this->redirectBack();
            }
        }

    }

    public function deleteAction($params) {
        $productModel = new ProductModel();
        if(!empty($params['product_id'])){
            $productModel->delete(['id'=>$params['product_id']]);
            $_SESSION['message'] = 'Product Deleted';
            $this->redirect('?p=bo',null);
        } else {
            $this->redirect('?p=bo',null);
        }
    }

}