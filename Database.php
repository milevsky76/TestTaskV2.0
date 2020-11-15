<?php

    class Database {
        private $fileName = "users.xml";
        private $isFileExists;
        
        private $dom;
        
        public function __construct(){
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
    }

?>