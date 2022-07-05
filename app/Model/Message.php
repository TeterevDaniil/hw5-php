<?php

namespace App\Model;

use Base\Db;

class Message
{
    private $messageId;
    private $text;
    private $user_id;
    private $insert_date;
    private $img;
    public function __construct($data = [])
    {
        if ($data) {
            $this->messageId = $data['messageId'];
            $this->text = $data['text'];
            $this->user_id = $data['user_id'];
            $this->insert_date = $data['insert_date'];
            $this->img = $data['img'] ?? '';
        }
    }

    public function getText(): string
    {
        return $this->text;
    }
    public function setText(string $text)
    {
        $this->text = $text;
        return $this;
    }
    public function getMessageId(): int
    {
        return $this->messageId;
    }
    public function setMessageId(int $messageId): self
    {
        $this->messageId = $messageId;
        return $this;
    }
    public function getUser_id(): int
    {
        return $this->user_id;
    }
    public function setUser_id(int $user_id): self
    {
        $this->user_id = $user_id;
        return $this;
    }
    public function getImg(): string
    {
        return $this->img;
    }
    public function setImg($img): self
    {
        $this->img = $img;
        return $this;
    }
    public function getInsertDate(): string
    {
        return $this->insert_date;
    }
    public function setInsertDate(string $insert_date): self
    {
        $this->insert_date = $insert_date;
        return $this;
    }
   public static function deleteMessage(int $messageId)
   {
    $db = Db::getInstance();
    $query = "DELETE FROM `message` WHERE id = $messageId";
    return $db->exec($query,[],__METHOD__);
   }


    public function saveMessage()
    {
        $db = Db::getInstance();
        $insert = "INSERT INTO `message`(`text`,`user_id`, `img`)
        VALUES (:text,:user_id,:img)";
        $db->exec($insert, [
            ':text' => $this->text,
            ':user_id' => $this->user_id,
            ':img' => $this->img
        ], __METHOD__);
        $id = $db->lastinsertId();
        $this->id = $id;

        return $id;
    }

    public function loadFile($file)
    {
        if (file_exists($file)) {
            $this->img = $this->genFileName();
            move_uploaded_file($file, getcwd() . './img/' . $this->img);
        }
    }

    public function genFileName()
    {
        return sha1(microtime(1) . mt_rand(1, 100000000)) . '.jpg';
    }

    public static function getListMessages()
    {
        $db = Db::getInstance();
        $select = "SELECT * FROM `message` order by id DESC LIMIT 20";
        $data = $db->findAll($select, [], __METHOD__);
        if (!$data) {
            return [];
        }
        $messages = [];
        foreach ($data as $elem) {
            $messages[] = $elem;
        }
        return $messages;
    }

    public static function getListUserMessages(int $user_id)
    {
        $db = Db::getInstance();
        $select = "SELECT * FROM `message` where user_id =$user_id order by id DESC LIMIT 20";
        $data = $db->findAll($select, [], __METHOD__);
        if (!$data) {
            return [];
        }
        $messages = [];
        foreach ($data as $elem) {
            $messages[] = $elem;
        }
       
        return $messages;
    }

    public function getData()
    {
       // var_dump($this->messageId);
        return[
            'id'=> $this->messageId,
            'text'=>$this->text,
            'user_id'=>$this->user_id,
            'insert_date'=>$this->insert_date,
            'img'=>$this->img
        ];
    }
}
