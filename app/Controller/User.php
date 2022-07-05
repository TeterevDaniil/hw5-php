<?php

namespace APP\Controller;

use App\Model\User as UserModel;
use Base\AbstractController;
use Base\Helper;

class User extends AbstractController
{
    public function loginAction()
    {
        $email = trim($_POST['email']);
        if ($email) {
            $password = $_POST['password'];
            $user = UserModel::getByEmail($email);
            $helper = new Helper();
            if (!$user) {
                $this->view->assign('error', 'Неверный Email или пароль');
            }
            if ($user) {
                if ($user->getPassword() != $helper->hashPassword($password)) {
                    $this->view->assign('error', 'Неверный Email или пароль');
                } else {
                    $_SESSION['id'] = $user->getId();
                    $this->redirect('/blog/index');
                }
            }
        }
        return $this->view->render('User/register.phtml', [
            'user' => UserModel::getById((int) $_GET['id'])
        ]);
    }
    public function registerAction()
    {
        $success = true;
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $helper = new Helper();
        $password = trim($_POST['password']);
        $password2 = trim($_POST['password2']);
        if (mb_strlen($password) < 4) {
            $this->view->assign('error', 'Пароль должен быть не менее 4 знаков');
            $success = false;
        }
        if ($password !== $password2) {
            $this->view->assign('error', 'Пароли не совподают');
            $success = false;
        }
        $password = $helper->hashPassword($password);

        if ($name) {
            if (!$name) {
                $this->view->assign('error', 'Имя не может быть пустым');
                $success = false;
            }

            if (!$email) {
                $this->view->assign('error', 'Емаил не может быть пустым');
                $success = false;
            }

            if (!$password) {
                $this->view->assign('error', 'Пароль не может быть пустым');
                $success = false;
            }
            if ($user = UserModel::getByEmail($email)) {
                $this->view->assign('error', 'Email уже занят');
                $success = false;
            }
            if ($success == true) {
                $user = (new UserModel())
                    ->setName($name)
                    ->setEmail($email)
                    ->setPassword($password);
                $user->save();

                $_SESSION['id'] = $user->getId();
                $this->setUser($user);
                $this->redirect('/blog');
            }
        }
        return $this->view->render('User/register.phtml', [
            'user' => UserModel::getById((int) $_GET['id'])
        ]);
    }

    public function logoutAction()
    {
        session_destroy();
        $this->redirect('/user/login');
    }

    public function profileAction()
    {
        return $this->view->render('User/profile.phtml', [
            'user' => UserModel::getById((int) $_GET['id'])
        ]);
    }
}
