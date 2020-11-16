<?php

    class Database {
        private $fileName;
        private $isFileExists;
        
        private $dom;
        
        public function __construct($nameTable){
            $this->fileName = $nameTable;
            
            $this->isFileExists = self::IsFileExists();
            
            if($this->isFileExists){
                self::FileUpload();
            } else{
                self::FileCreation();
            }            
        }
        
        //Проверка существования XML файла
        private function IsFileExists(){
            return file_exists($this->fileName);
        }
        
        //Создание XML файла
        private function FileCreation(){
            $this->dom = new domDocument("1.0", "utf-8");//Создаём XML-документ версии 1.0 с кодировкой utf-8
            
            $root = $this->dom->createElement("users");//Создаём корневой элемент
            $root->setAttribute("increment", 0);
            
            $this->dom->appendChild($root);
            
            $this->dom->save($this->fileName);
        }
        
        //Загрузка XML файла
        private function FileUpload(){
            $this->dom = new domDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
            
            $this->dom->load($this->fileName); // Загружаем XML-документ из файла в объект DOM
        }
        
        //Добавление пользователя
        public function AddingUser($userLogin, $userPass, $userEmail, $userName){
            $root = $this->dom->documentElement; // Получаем корневой элемент
            $increment = $root->getAttribute("increment");
            
            $user = $this->dom->createElement("user"); // Создаём узел "user"
            
            $user->setAttribute("id", ++$increment); // Устанавливаем атрибут "id" у узла "user"
            
            $login = $this->dom->createElement("login", $userLogin); // Создаём узел "login" с текстом внутри
            $user->appendChild($login); // Добавляем в узел "user" узел "login"
            
            $password = $this->dom->createElement("password", $userPass); // Создаём узел "password" с текстом внутри
            $user->appendChild($password);// Добавляем в узел "user" узел "password"
            
            $email = $this->dom->createElement("email", $userEmail); // Создаём узел "email" с текстом внутри
            $user->appendChild($email);// Добавляем в узел "user" узел "email"
            
            $name = $this->dom->createElement("name", $userName); // Создаём узел "name" с текстом внутри
            $user->appendChild($name);// Добавляем в узел "user" узел "name"
            
            $root->appendChild($user); // Добавляем в корневой узел "users" узел "user"
            
            $root->setAttribute("increment", $increment);
            
            $this->dom->save($this->fileName);
        }
        
        //Получение всех данных пользователя
        public function GetAllUser($userId){
            $root = $this->dom->documentElement; // Получаем корневой элемент
            $childs = $root->childNodes; // Получаем дочерние элементы у корневого элемента
                        
            foreach ($childs as $child) {
                if($child->getAttribute("id") == $userId){
                    $user["userId"] = $userId;
                    $data = $child->childNodes;           
                    foreach ($data as $val) {
                        $user[$val->localName] = $val->nodeValue;
                    }
                }
            }
            
            return $user;
        }
        
        //Получение данных(id, name) пользователя
        public function GetUser($userlogin, $password){
            $root = $this->dom->documentElement; // Получаем корневой элемент
            $childs = $root->childNodes; // Получаем дочерние элементы у корневого элемента
                        
            foreach ($childs as $child) {
                $login = $child->getElementsByTagName("login")[0]->nodeValue;
                $pass = $child->getElementsByTagName("password")[0]->nodeValue;
                $name = $child->getElementsByTagName("name")[0]->nodeValue;
                $id = $child->getAttribute("id");
                
                if($login == $userlogin && $pass == $password){
                    $user["userId"] = $id;
                    $user["userName"] = $name;
                    
                    return $user;
                }
            }
            
            return false;
        }
        
        //Удаление пользователей
        public function DeletingUser($userId){
            $root = $this->dom->documentElement; // Получаем корневой элемент
            $childs = $root->childNodes; // Получаем дочерние элементы у корневого элемента
            
            foreach ($childs as $child) {
                if($child->getAttribute("id") == $userId){
                    $root->removeChild($child);
                    break;
                }
            }
            
            self::ResetIncrement();
            
            $this->dom->save($this->fileName);
        }
        
        //Обнуление инкремента
        private function ResetIncrement(){
            $root = $this->dom->documentElement; // Получаем корневой элемент
            $quantityChilds = $root->childNodes->length; // Получаем кооличество дочерние элементы у корневого элемента
            
            if($quantityChilds == 0){
                $root->setAttribute("increment", 0);
            }
        }
        
        //Обновление пользователя
        public function UpdateUser($userId, $userLogin, $userPass, $userEmail, $userName){
            $root = $this->dom->documentElement; // Получаем корневой элемент
            $childs = $root->childNodes; // Получаем дочерние элементы у корневого элемента
                        
            foreach ($childs as $child) {
                if($child->getAttribute("id") == $userId){
                    if($child->getElementsByTagName("login")[0]->nodeValue != $userLogin){
                        $child->getElementsByTagName("login")[0]->nodeValue = $userLogin;
                    }
                    
                    if($child->getElementsByTagName("password")[0]->nodeValue != $userPass){
                        $child->getElementsByTagName("password")[0]->nodeValue = $userPass;
                    }
                    
                    if($child->getElementsByTagName("email")[0]->nodeValue != $userEmail){
                        $child->getElementsByTagName("email")[0]->nodeValue = $userEmail;
                    }
                    
                    if($child->getElementsByTagName("name")[0]->nodeValue != $userName){
                        $child->getElementsByTagName("name")[0]->nodeValue = $userName;
                    }
                }
            }
            
            $this->dom->save($this->fileName);
        }
        
        //Проверка уникальности полей
        public function IsUniqueFields($userLogin, $userEmail, $userId = NULL){
            $root = $this->dom->documentElement; // Получаем корневой элемент
            $childs = $root->childNodes; // Получаем дочерние элементы у корневого элемента
                        
            foreach ($childs as $child) {
                $field = ["userlogin" => true, "useremail" => true];
                
                if($child->getAttribute("id") != $userId){
                    if($child->getElementsByTagName("login")[0]->nodeValue == $userLogin){
                        $field["userlogin"] = false;
                    }
                    
                    if($child->getElementsByTagName("email")[0]->nodeValue == $userEmail){
                        $field["useremail"] = false;
                    }
                    
                    if(!$field["userlogin"] || !$field["useremail"]){
                        return $field;
                    }
                }
            }
            
            return ["userlogin" => true, "useremail" => true];
        }
    }

?>