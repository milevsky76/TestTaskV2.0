<?php

    include_once 'User.php';
    include_once 'AjaxRequest.php';

    if (!empty($_COOKIE['sid'])) {
        session_id($_COOKIE['sid']);
    }
    
    session_start();

    class AuthorizationAjaxRequest extends AjaxRequest{
        public $actions = array(
            "login" => "login",
            "logout" => "logout",
            "register" => "register",
        );
        
        //action Вход
        public function Login(){
            if ($_SERVER["REQUEST_METHOD"] !== "POST") {
                http_response_code(405);
                header("Method Not Allowed: POST");
                $this->ErrorRequest("main", "Запрос запрещён");
                return;
            }
            
            setcookie("sid", "");
            
            $userlogin = $this->GetRequestParam("userlogin");
            $password = $this->GetRequestParam("password");
            
            if(empty($userlogin)) {
                $this->ErrorRequest("userlogin", "Введите логин");
                return;
            }
    
            if(empty($password)) {
                $this->ErrorRequest("password", "Введите пароль");
                return;
            }
    
            $user = new User();
            $authResult = $user->Authorize($userlogin, $password);
    
            if (!$authResult[0]){                
                if(!$authResult["password"]) {
                    $this->ErrorRequest("password", "Неверный логин или пароль");
                    return;
                }
            }
            
            $this->status = "ok";
            $this->SetResponse("redirect", "/");
            $this->message = "$authResult[1]";
            $_SESSION["nameUser"] = $this->message;
        }
        
        //action Выход
        public function Logout(){
            if ($_SERVER["REQUEST_METHOD"] !== "POST") {
                http_response_code(405);
                header("Allow: POST");
                $this->ErrorRequest("main", "Запрос запрещён");
                return;
            }
    
            setcookie("sid", "");
    
            $user = new User();
            $user->Logout();
    
            $this->SetResponse("redirect", ".");
            $this->status = "ok";
        }
        
        //action Регистрация
        public function Register(){
            if ($_SERVER["REQUEST_METHOD"] !== "POST"){
                http_response_code(405);
                header("Allow: POST");
                $this->ErrorRequest("main", "Запрос запрещён");
                return;
            }
    
            setcookie("sid", "");
    
            $userlogin = $this->getRequestParam("userlogin");
            $password = $this->getRequestParam("password");
            $password2 = $this->getRequestParam("password2");
            $useremail = $this->getRequestParam("useremail");
            $username = $this->getRequestParam("username");
            
            if (empty($userlogin)){
                $this->ErrorRequest("userlogin", "Введите логин");
                return;
            }
    
            if (empty($password)){
                $this->ErrorRequest("password", "Введите пароль");
                return;
            }
    
            if (empty($password2)){
                $this->ErrorRequest("password2", "Подтвердите пароль");
                return;
            }
    
            if ($password !== $password2){
                $this->ErrorRequest("password2", "Пароли не совпадают");
                return;
            }
            
            if (empty($useremail)){
                $this->ErrorRequest("useremail", "Введите email");
                return;
            }
            
            if (empty($username)){
                $this->ErrorRequest("username", "Введите имя пользователя");
                return;
            }
    
            $user = new User();
            
            try{
                $regResult = $user->CreateUser($userlogin, $password, $useremail, $username);
                
                if(!is_null($regResult)){
                    $this->ErrorRequest($regResult[0], $regResult[1]);
                    return;
                }
            }catch (Exception $e){
                $this->ErrorRequest("username", $e->getMessage());
                return;
            }
    
            $authResult = $user->Authorize($userlogin, $password);
            
            $this->status = "ok";
            $this->SetResponse("redirect", "/");
            $this->message = "$authResult[1]";
            $_SESSION["nameUser"] = $this->message;
        }
    }
    
    
    
    $ajaxRequest = new AuthorizationAjaxRequest($_REQUEST);
    $ajaxRequest->ShowResponse();
    
?>
