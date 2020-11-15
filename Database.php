<?php

    class Database {
        private $fileName = "users.xml";
        private $isFileExists;
        
        public function __construct(){
            $this->isFileExists = self::IsFileExists();
        }
        
        //Проверка существования XML файла
        private function IsFileExists(){
            return file_exists($this->fileName);
        }
    }

?>