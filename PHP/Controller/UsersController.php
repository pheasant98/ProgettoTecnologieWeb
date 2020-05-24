<?php

require_once ('Repository/UsersRepository.php');

class UsersController {
    private $users;

    private static function checkInput($name, $surname, $sex, $date, $mail, $username, $password) {
        $message = '';

        if (strlen($name) === 0) {
            $message .= '[Il nome non può essere vuoto]';
        } elseif (strlen($name) < 2) {
            $message .= '[Il nome deve essere lungo almeno 2 caratteri]';
        }

        if (strlen($surname) === 0) {
            $message .= '[Il cognome non può essere vuoto]';
        } elseif (strlen($surname) < 2) {
            $message .= '[Il cognome deve essere lungo almeno 2 caratteri]';
        }

        if ($sex !== 'M' && $sex !== 'F' && $sex !== 'A') {
            $message .= '[Il sesso deve essere scelto tra M, F ed A]';
        }

        if (strlen($date) === 0) {
            $message .= '[La data di nascita non può essere vuota]';
        }

        if (strlen($mail) === 0) {
            $message .= '[L\'indirizzo <span xml:lang="en">email</span> non può essere vuoto]';
        }

        if (strlen($username) === 0) {
            $message .= '[Lo <span xml:lang="en">username</span> non può essere vuoto]';
        } elseif (strlen($username) < 3) {
            $message .= '[Lo <span xml:lang="en">username</span> deve essere lungo almeno 3 caratteri]';
        }

        if (strlen($password) === 0) {
            $message .= '[La <span xml:lang="en">password</span> non può essere vuota]';
        } elseif (strlen($password) < 8) {
            $message .= '[La <span xml:lang="en">password</span> deve essere lungo almeno 8 caratteri]';
        }

        return $message;
    }

    public function __construct() {
        $this->users = new UsersRepository();
    }

    public function __destruct() {
        unset($this->users);
    }

    public function addUser($name, $surname, $sex, $date, $mail, $username, $password, $repeted_password) {
        $message = UsersController::checkInput($name, $surname, $sex, $date, $mail, $username, $password);

        $message .= $password === $repeted_password ? '' : '[La conferma della <span xml:lang="en">password</span> non corrisponde a quella inserita inizialmente]';

        if ($message === '') {
            if ($this->users->postUser($name, $surname, $date, $sex, $username, $mail, $password)) {
                $message = '<p class="success">Nuovo utente correttamente registrato</p>';
            } else {
                $message = '<p class="error">Errore durante la registrazione del nuovo utente</p>';
            }
        } else {
            $message = '<ul>' . $message;
            $message = str_replace('[', '<li class="error">', $message);
            $message = str_replace(']', '</li>', $message);
            $message = $message . '</ul>';
        }

        return $message;
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
