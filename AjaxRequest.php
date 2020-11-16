<?php

    class AjaxRequest
    {
        public $actions = array();
        public $data;
        public $code;
        public $message;
        public $status;
    
        public function __construct($request)
        {
            $this->request = $request;
            $this->action = $this->getRequestParam("act");
            
            if (!empty($this->actions[$this->action])) {
                $this->callback = $this->actions[$this->action];
                call_user_func(array($this, $this->callback));
            } else {
                header("HTTP/1.1 400 Bad Request");
                $this->ErrorRequest("main", "Некорректный запрос");
            }
    
            $this->response = $this->InJSON();
        }
    
        //Получить параметр запроса
        public function GetRequestParam($name){
            if(array_key_exists($name, $this->request)) {
                return trim($this->request[$name]);
            }
            return null;
        }
    
        //Формирования ответа
        public function SetResponse($key, $value){
            $this->data[$key] = $value;
        }
    
        //Ошибка в запросе
        public function ErrorRequest($name, $message = ""){
            $this->status = "Error";
            $this->message = $message;
            $this->code = $name;        
        }
    
        //JSON ответ
        public function InJSON(){
            $jsonResponse = [
                "status" => $this->status,
                "message" => $this->message,
                "data" => $this->data,
                "code" => $this->code,
            ];
                    
            return json_encode($jsonResponse, ENT_NOQUOTES);
        }
    
        //Вывод ответа
        public function ShowResponse(){
            header("Content-Type: application/json; charset=UTF-8");
            echo $this->response;
        }
    }

?>