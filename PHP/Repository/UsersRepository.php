<?php

require_once('Database/DatabaseAccess.php');

class UsersRepository {
    private $dbConnection;

    public function __construct() {
        $this->dbConnection = new DatabaseAccess();
    }

    public function __destruct() {
        unset($this->dbConnection);
    }

    public function postUser($name, $surname, $birthday, $sex, $username, $mail, $password, $admin=0) {
        $hashed_password = hash('sha256', $password);
        $statement = $this->dbConnection->prepareQuery('INSERT INTO Utenti (Nome, Cognome, DataNascita, Sesso, Username, Email, Password, Admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?);');
        $statement->bind_param('sssssssi', $name, $surname, $birthday, $sex, $username, $mail, $hashed_password, $admin);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }

    public function getUsers($offset) {
        $statement = $this->dbConnection->prepareQuery('SELECT * FROM Utenti ORDER BY Username LIMIT 5 OFFSET ?;');
        $statement->bind_param('i', $offset);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getUsersCount() {
        $statement = $this->dbConnection->prepareQuery('SELECT COUNT(*) AS Totale FROM Utenti;');
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getUser($username) {
        $statement = $this->dbConnection->prepareQuery('SELECT * FROM Utenti WHERE Username=?;');
        $statement->bind_param('s', $username);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getUserByCredential($username, $password) {
        $hashed_password = hash('sha256', $password);
        $statement = $this->dbConnection->prepareQuery('SELECT * FROM Utenti WHERE Username=? AND Password=?;');
        $statement->bind_param('ss', $username, $hashed_password);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function updateUser($username, $name, $surname, $date, $sex, $mail, $hashed_password) {
        $statement = $this->dbConnection->prepareQuery('UPDATE Utenti SET Nome=?, Cognome=?, DataNascita=?, Sesso=?, Email=?, Password=? WHERE Username=?;');
        $statement->bind_param('sssssss', $name, $surname, $date, $sex, $mail, $hashed_password, $username);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }

    public function deleteUser($username) {
        $statement = $this->dbConnection->prepareQuery('DELETE FROM Utenti WHERE Username=?;');
        $statement->bind_param('s', $username);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }
}

?>
