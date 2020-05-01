<?php

require_once('Repository/UsersRepository.php');

class UserController {
    private $users;

    public function __construct() {
        $this->users = new UsersRepository();
    }


}

?>
