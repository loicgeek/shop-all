<?php


class UserController extends Controller
{

    public function attemptLoginAction($params){
        $userModel = new UserModel();
        $user = $userModel->findByEmail($params['email']);
        if($user){
            if($user['password']==md5($params['password'])){
                $_SESSION['user'] = $user;
                $this->redirect('?',null);
            }else{
                $_SESSION['message'] = "Missmatch email/password";
                $this->redirectBack();
            }
        }else{
            $_SESSION['message'] = "Email doesnt exist";
            $this->redirectBack();
        }
    }

    public function registerAction($params){
        if(empty($params['name']) || empty($params['email']) || empty($params['password'])) {
            $_SESSION['message'] = 'Field(s) is(are) missing';
            $this->redirect('?a=register');
        } else {
            $userModel = new UserModel();
            $userModel->insert(['name'=>$params['name'],'email'=>$params['email'],'password'=>md5($params['password']),'isAdmin'=>0]);
            $user = $userModel->findByEmail($params['email']);
            $_SESSION['user'] = $user;
            $this->redirect('?');
        }

    }



}