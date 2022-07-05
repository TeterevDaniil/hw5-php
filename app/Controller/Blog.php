<?php

namespace APP\Controller;

use App\Model\Message;
use APP\Model\User;
use Base\AbstractController;

class Blog extends AbstractController
{
    function indexAction()
    {
        if (!$this->user) {
            $this->redirect('/user/register');
        }
        $messages = Message::getListMessages();

        if ($messages) {
            $data = [];
            $user_name = new User();
            foreach ($messages as $elem) {
                $user = $user_name->getNameById($elem['user_id']);
                if (!$user) {
                    $user['name'] = 'Пользователь не найден';
                }
                $elem['name'] = $user['name'];
                $data[] = $elem;
            }
        }
        return $this->view->render('Blog/index.phtml', [
            'user' => $this->user,
            'message' => $data
        ]);
    }

    public function addMessageAction()
    {
        $success = true;
        if (!$this->user->getId()) {
            $this->redirect('/login');
        }
        $text = (string) $_POST['text'];

        if (!$text) {
            $this->view->assign('error', 'Сообщение не может быть пустым');
            $success = false;
        }

        if ($success == true) {
            $message = (new Message())
                ->setText($text)
                ->setUser_id($this->user->getId());

            if (isset($_FILES['img']['tmp_name'])) {
                $message->loadFile($_FILES['img']['tmp_name']);
            }



            $message->saveMessage();
        }
        $this->redirect('/blog');
    }
}
