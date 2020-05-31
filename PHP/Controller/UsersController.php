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

    public function addUser($name, $surname, $sex, $date, $mail, $username, $password, $repeated_password) {
        $message = UsersController::checkInput($name, $surname, $sex, $date, $mail, $username, $password);

        $message .= $password === $repeated_password ? '' : '[La conferma della <span xml:lang="en">password</span> non corrisponde a quella inserita inizialmente]';

        if ($message === '') {
            $hashed_password = hash('sha256', $password);
            if ($this->users->postUser($name, $surname, $date, $sex, $username, $mail, $hashed_password)) {
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

    public function getUsersCount() {
        $result_set = $this->users->getUsersCount();
        $count = $result_set->fetch_assoc()['Totale'];
        $result_set->free();
        return $count;
    }

    public function getUsers($offset) {
        $result_set = $this->users->getUsers($offset);

        $content = '';

        while($row = $result_set->fetch_assoc()) {
            $content .= '
                <li>
                    <a href="Utente.php?user=' . $row['Username'] . '" aria-label="Vai alla pagina dell\'utente">' .  $row['Username'] . '</a>

                    <form class="userButton" action="EliminaUtente.php" method="post" role="form">
                        <fieldset class="hideFieldset">
                            <legend class="hideLegend">Pulsante di eliminazione dell\'utente</legend>
                            
                            <input type="hidden" name="username" value="' . $row['Username'] . '"/>
                            <input id="buttonConfirm" class="button" name="submit" type="submit" value="Rimuovi" role="button" aria-label="Rimuovi utente"/>
                        </fieldset>
                    </form>
                </li>
            ';
        }

        $result_set->free();
        return $content;
    }

    public function getUser($username) {
        $result_set = $this->users->getUser($username);
        $row = $result_set->fetch_assoc();
        $result_set->free();
        return $row;
    }

    public function getUserByCredential($username, $password) {
        $hashed_password = hash('sha256', $password);
        $result_set = $this->users->getUserByCredential($username, $hashed_password);
        $row = $result_set->fetch_assoc();
        $result_set->free();
        return $row;
    }

    public function updateUser($username, $name, $surname, $date, $sex, $mail, $oldPassword, $newPassword, $repeated_password) {
        //TODO: Fare controllo sulla password
        $message = UsersController::checkInput($name, $surname, $sex, $date, $mail, $username, $newPassword);
        $message .= $newPassword === $repeated_password ? '' : '[La conferma della <span xml:lang="en">password</span> non corrisponde a quella inserita inizialmente]';
        if ($message === '') {
            if ($this->users->updateUser($username, $name, $surname, $date, $sex, $mail, $newPassword)) {
                $message = '<p class="success">Utente aggiornata correttamente</p>';
            } else {
                $message = '<p class="error">Errore nell\'aggiornamento dell\'utente</p>';
            }
        } else {
            $message = '<ul>' . $message;
            $message = str_replace('[', '<li class="error">', $message);
            $message = str_replace(']', '</li>', $message);
            $message .= '</ul>';
        }

        return $message;
    }

    public function deleteUser($username) {
        $this->users->deleteUser($username);
    }
}

?>
