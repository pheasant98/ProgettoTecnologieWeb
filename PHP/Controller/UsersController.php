<?php

require_once ('Repository/UsersRepository.php');
require_once ('Utilities/DateUtilities.php');

class UsersController {
    private $users;

    private function isUniqueMail($mail) {
        $result_set = $this->users->checkMail($mail);
        $count = $result_set->fetch_assoc()['Totale'];
        $result_set->free();
        return $count === 0;
    }

    private function isUniqueUsername($username) {
        $result_set = $this->users->checkUsername($username);
        $count = $result_set->fetch_assoc()['Totale'];
        $result_set->free();
        return $count === 0;
    }

    private function checkInput($name, $surname, $sex, $date, $mail, $username, $password) {
        $message = '';

        if (strlen($name) === 0) {
            $message .= '[Non è possibile inserire un nome vuoto]';
        } elseif (strlen($name) < 2) {
            $message .= '[Non è possibile inserire un nome più corto di 2 caratteri]';
        } elseif (strlen($name) > 32) {
            $message .= '[Non è possibile inserire un nome più lungo di 32 caratteri]';
        } elseif (!preg_match('/^[A-zÀ-ú\'`.\s]+$/', $name)) {
            $message .= '[Il nome inserito contiene dei caratteri non consentiti, è possibile inserire solamente lettere, possibilmente accentate, apostrofi, punti e spazi]';
        }

        if (strlen($surname) === 0) {
            $message .= '[Non è possibile inserire un cognome vuoto]';
        } elseif (strlen($surname) < 2) {
            $message .= '[Non è possibile inserire un cognome più corto di 2 caratteri]';
        } elseif (strlen($surname) > 32) {
            $message .= '[Non è possibile inserire un cognome più lungo di 32 caratteri]';
        } elseif (!preg_match('/^[A-zÀ-ú\'-`.\s]+$/', $surname)) {
            $message .= '[Il cognome inserito contiene dei caratteri non consentiti, è possibile inserire solamente lettere, possibilmente accentate, apostrofi, punti, trattini e spazi]';
        }

        if ($sex !== 'M' && $sex !== 'F' && $sex !== 'A') {
            $message .= '[Il sesso deve essere scelto tra "Maschile", "Femminile" e "Preferisco non dichiarare"]';
        }

        if (strlen($date) === 0) {
            $message .= '[Non è possibile inserire una data di nascita vuota]';
        } else {
            $formatted_date = DateTime::createFromFormat('d-m-Y', $date);
            if ($formatted_date === false) {
                $message .= '[Non è possibile inserire una data di nascita espressa nel formato diverso da "gg-mm-aaaa"]';
            } else {
                $date_properties = date_create_from_format('d-m-Y', $date);
                if (!checkdate($date_properties['month'], $date_properties['day'], $date_properties['year'])) {
                    $message .= '[La data di nascita inserita non è valida]';
                } else {
                    $inserted_date = DateTime::createFromFormat('Y-m-d', DateUtilities::italianEnglishDate($date));
                    $lower_bound = DateTime::createFromFormat('Y-m-d', '1900-01-01');
                    $upper_bound = DateTime::createFromFormat('Y-m-d', '2006-12-31');

                    if ($inserted_date < $lower_bound) {
                        $message .= '[Non è possibile inserire una data di nascita precedente al 01-01-1900]';
                    } elseif ($inserted_date > $upper_bound) {
                        $message .= '[Non è possibile inserire una data di nascita successiva al 31-12-2006]';
                    }
                }
            }
        }

        if (strlen($mail) === 0) {
            $message .= '[Non è possibile inserire un indirizzo <span xml:lang="en">email</span> vuoto]';
        } elseif (strlen($mail) > 64) {
            $message .= '[Non è possibile inserire un indirizzo <span xml:lang="en">email</span> più lungo di 64 caratteri]';
        } elseif (!preg_match('/^[a-zA-Z0-9.!#$%&\'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/', $mail)) {
            $message .= '[L\'indirizzo <span xml:lang="en">email</span> inserito non è valido]';
        } elseif (!$this->isUniqueMail($mail)) {
            $message .= '[L\'indirizzo <span xml:lang="en">email</span> inserito non può essere utilizzato in quanto è già in uso da altro utente]';
        }

        if (strlen($username) === 0) {
            $message .= '[Non è possibile inserire uno <span xml:lang="en">username</span> vuoto]';
        } elseif (strlen($username) < 4) {
            $message .= '[Non è possibile inserire uno <span xml:lang="en">username</span> più corto di 4 caratteri]';
        } elseif (strlen($username) > 32) {
            $message .= '[Non è possibile inserire uno <span xml:lang="en">username</span> più lungo di 32 caratteri]';
        } elseif (!$this->isUniqueUsername($username)) {
            $message .= '[Lo <span xml:lang="en">username</span> inserito non può essere utilizzato in quanto è già in uso da altro utente]';
        }

        if (strlen($password) === 0) {
            $message .= '[Non è possibile inserire una <span xml:lang="en">password</span> vuota]';
        } elseif (strlen($password) < 8) {
            $message .= '[Non è possibile inserire una <span xml:lang="en">password</span> più corta di 8 caratteri]';
        } elseif (strlen($password) > 64) {
            $message .= '[Non è possibile inserire una <span xml:lang="en">password</span> più lunga di 64 caratteri]';
        } elseif (!preg_match('/^(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#$%^&+=§_/|ç<>£€!?]).*$/', $password)) {
            $message .= '[La <span xml:lang="en">password</span> inserita non soddisfa tutti i requisiti richiesti, deve essere presente almeno una lettera minuscola, una lettera maiuscola, un numero ed un carattere speciale]';
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
        $message = $this->checkInput($name, $surname, $sex, $date, $mail, $username, $password);

        $message .= $password === $repeated_password ? '' : '[La conferma della <span xml:lang="en">password</span> non corrisponde a quella inserita inizialmente]';

        if ($message === '') {
            $hashed_password = hash('sha256', $password);
            if ($this->users->postUser($name, $surname, $date, $sex, $username, $mail, $hashed_password)) {
                $message = '';
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
        $message = $this->checkInput($name, $surname, $sex, $date, $mail, $username, $newPassword);
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
