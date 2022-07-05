<?php
namespace APP\Controller;

use App\Model\Message;
use Base\AbstractController;

class Admin extends AbstractController
{
   public function deleteMessageAction ()
   {
    if (!$this->user && !$this->user->isAdmin()) {
        $this->redirect('/user/blog');
    }
    $messageId = $_GET['id'];
    Message::deleteMessage($messageId);
    $this->redirect('/blog');
   }

}
