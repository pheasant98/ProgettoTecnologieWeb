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

    public function getUser($username) {
        $result_set = $this->users->getUser($username);
        $count = $result_set->fetch_assoc();
        $result_set->free();
        return $count;
    }

}

?>
