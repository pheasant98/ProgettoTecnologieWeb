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
}

?>
