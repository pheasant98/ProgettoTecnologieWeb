<?php

require_once ('Repository/UsersRepository.php');

class UsersController {
    private $users;

    public function __construct() {
        $this->users = new UsersRepository();
    }

    public function __destruct() {
        unset($this->users);
    }

    public function addUser($name, $surname, $sex, $date, $mail, $username, $password) {
        // TODO: implementare
        return '';
    }

    public function getUser($username) {
        $result_set = $this->users->getUser($username);
        $row = $result_set->fetch_assoc();
        $result_set->free();
        return $row;
    }

    public function getUserByCredential($username, $password) {
        $result_set = $this->users->getUserByCredential($username, $password);
        $row = $result_set->fetch_assoc();
        $result_set->free();
        return $row;
    }
}

?>
