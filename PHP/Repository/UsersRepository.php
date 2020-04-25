<?php

require_once("../Database/DatabaseAccess.php");

class UsersRepository {
    private $dbConnection;

    public function __construct() {
        $this->dbConnection = new DatabaseAccess();
    }

    public function postUser($name, $surname, $birthday, $sex, $username, $mail, $password, $admin=false) {
        $hashed_password = hash('sha256', $password);
        return $this->dbConnection->executeQuery("INSERT INTO Utenti (ID, Nome, Cognome, DataNascita, Sesso, Username, Email, Password, Admin) VALUES (NULL, '$name', '$surname', '$birthday', '$sex', '$username', '$mail', '$hashed_password', $admin);");
    }

    public function getUsers($offset) {
        return mysqli_fetch_assoc($this->dbConnection->executeQuery("SELECT * FROM Utenti ORDER BY Username LIMIT 5, $offset;"));
    }

    public function getUser($id) {
        return mysqli_fetch_assoc($this->dbConnection->executeQuery("SELECT * FROM Utenti WHERE ID=$id;"));
    }

    public function updateUser($id, $name, $surname, $birthday, $sex, $username, $mail, $password, $admin=false) {
        $hashed_password = hash('sha256', $password);
        return $this->dbConnection->executeQuery("UPDATE Utenti SET Nome='$name', Cognome='$surname', DataNascita='$birthday', Sesso='$sex', Username='$username', Email='$mail', Password='$hashed_password', Admin=$admin WHERE ID=$id;");
    }

    public function deleteUser($id) {
        return $this->dbConnection->executeQuery("DELETE FROM Utenti WHERE ID=$id;");
    }
}

?>
