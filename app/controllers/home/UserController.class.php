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

    public function sendResetMail($email,$token){

            if($_SERVER['HTTP_HOST'] == "localhost") {
            $server = $_SERVER['HTTP_HOST'].'/minette';
        }else {
            $server = $_SERVER['HTTP_HOST'];
        }

        $message = "You request to reset your password, click on the link below to complete operation \n";
        $message .= "\n \t<a href='$server/?a=resetPassword&token=$token'>Link</a>";
        $subject = 'Reset Password';
        try {
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: ' .$email . "\r\n".
                'X-Mailer: PHP/' . phpversion();

            $result=mail($email, $subject, $message, $headers);
            return $result;
        }catch (Exception $e) {
            var_dump($e->getTraceAsString());
            die();
        }
    }

    public function forgotPasswordAction($params){
        $userModel = new UserModel();
        $user = $userModel->findByEmail($params['email']);
        if($user) {
            $tokenData = $userModel->addTokenforUser($user['id']);
            try{
                if($this->sendResetMail($params['email'],$tokenData['token'])){
                    $_SESSION['message'] = "Check your mail to activate your account";
                    $this->redirect('?a=forgotPassword');
                } else {
                    $_SESSION['message'] = "An Error Occured";
                    $this->redirect('?a=forgotPassword');
                }
            }catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $this->redirectBack();
            }
        } else {
            $_SESSION['message'] = "User Not Found In our Database";
            $this->redirect('?a=forgotPassword');
        }

    }

    public function resetPasswordAction($params){
        $userId = $params['userId'];
        $newPassword = $params['password'];
        $userModel = new UserModel();

        try{
            $response = $userModel->update(['id'=>$userId,'password'=>md5($newPassword)]);
            $user = $userModel->selectByPk($userId);
            $userModel->removeTokenForUser($userId);
//            $_SESSION['message']="Welcome Back $user[name]";
            $_SESSION['user']=$user;
            $this->redirect('?');
        }catch (Exception $e){
            echo $e->getMessage();
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