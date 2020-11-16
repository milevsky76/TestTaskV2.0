<?php

    require_once "db/Database.php";

    class User{
        private $db;
        private $nameTable = "db/users.xml";
        private $userId;
        private $userName;
        private $isAuthorized = false;
    
        public function __construct($userName = null, $userPass = null){
            $this->db = $this->connectDb($this->nameTable);
            
            $this->userName = $userName;
        }
        
        //Проверка авторизации
        public static function CheckAuthorization(){
            if (!empty($_SESSION["userId"])) {
                return (bool) $_SESSION["userId"];
            }
            
            return false;
        }
        
        //Солим пароль
        public function SaltyPassword($password, $salt = "m9bbv03bu5nwqoqc5f03"){
            $saltedHash = md5(trim($password) . $salt);
    
            return $saltedHash;
        }
        
        //Авторизация
        public function Authorize($userlogin, $password, $remember = false){
            //Солим пароль
            $hashes = $this->SaltyPassword($password);
            
            //Получаем id, name пользователя
            $user = $this->db->GetUser($userlogin, $hashes);
            
            if (!$user) {
                $this->isAuthorized = false;
                return array($user);
            } else {
                $this->isAuthorized = true;
                $this->userId = $user["userId"];
                $this->userName = $user["userName"];
                $this->CreatingCookie($remember);
                return array($this->isAuthorized, $this->userName);
            }
        }
        
        //Выход из системы
        public function Logout(){
            if (!empty($_SESSION["userId"])) {
                unset($_SESSION["nameUser"]);
                unset($_SESSION["userId"]);
            }
        }
        
        //Создание Cookie
        public function CreatingCookie($remember = false, $httpOnly = true, $days = 7){
            $_SESSION["userId"] = $this->userId;
    
            if ($remember) {
                //Значение Cookie
                $sid = session_id();
                //Время, когда срок действия cookie истекает
                $expire = time() + $days * 24 * 3600;
                //Путь к директории на сервере, из которой будут доступны Cookie
                $path = "/";
                //(Под)домен, которому доступны Cookie
                $domain = "";
                //Cookie должно передаваться от клиента по защищенному соединению HTTPS
                $secure = false;
    
                $cookie = setcookie("sid", $sid, $expire, $path, $domain, $secure, $httpOnly);
            }
        }
        
        //Проверка на существование пользователя
        public function IsUserExist($userlogin, $useremail) {
            return $this->db->IsUniqueFields($userlogin, $useremail);
        }
        
        //Добавление нового пользователя
        public function CreateUser($userlogin, $password, $useremail, $username) {
            $userExists = $this->IsUserExist($userlogin, $useremail);
            
            if(!$userExists["userlogin"]){
                return ["userlogin", "Такой логин существует"];
            }
            
            if(!$userExists["useremail"]){
                return ["useremail", "Пользователь с таким email существует"];
            }

            try {
                $hashes = $this->SaltyPassword($password);
                $result = $this->db->AddingUser($userlogin, $hashes, $useremail, $username);                
            } catch (Exception $e) {
                //Ловим ошибки из Database
                echo "Ошибка базы данных: " . $e->getMessage();
                die();
            }

            return $result;
        }
        
        //Подключение к XML файлу
        public function connectdb($nameTable){
            return new Database($nameTable);
        }
    }

?>
