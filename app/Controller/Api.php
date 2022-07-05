<?php
namespace App\Controller;

use App\Model\Message;
use Base\AbstractController;

class Api extends AbstractController
{
    public function getUserMessagesAction()
    {
        $user_id = (int)$_GET['id'] ?? 0;
        if(!$user_id){
            return $this->response(['error'=>'no user id']);
        }
       
        $messages = Message::getListUserMessages($user_id);
       // var_dump('<pre>');
        if(!$messages){
          return $this->response(['error'=>'no messages']);
        }
        
        // $data = array_map(function(Message $message) {
        //     var_dump($message);
        //     return $message->getData();
        // }, $messages);
      
        return $this->response(['messages' => $messages]);
    }


    public function response(array $data)
    {
        header('Content-type: application/json');
        return json_encode($data);
    }
}