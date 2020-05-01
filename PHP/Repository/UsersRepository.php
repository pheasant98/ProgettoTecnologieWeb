<?php

require_once('Database/DatabaseAccess.php');

class UsersRepository {
    private $dbConnection;

    public function __construct() {
        $this->dbConnection = new DatabaseAccess();
    }

    public function close() {
        $this->dbConnection->closeConnection();
        unset($this->dbConnection);
    }

    public function postUser($name, $surname, $birthday, $sex, $username, $mail, $password, $admin=false) {
        $hashed_password = hash('sha256', $password);
        $statement = $this->dbConnection->prepareQuery('INSERT INTO Utenti (ID, Nome, Cognome, DataNascita, Sesso, Username, Email, Password, Admin) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?);');
        $statement->bind_param('sssssssi', $name, $surname, $birthday, $sex, $username, $mail, $hashed_password, $admin);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }

    public function getUsers($offset) {
        $statement = $this->dbConnection->prepareQuery('SELECT * FROM Utenti ORDER BY Username LIMIT 5 OFFSET ?;');
        $statement->bind_param('i', $offset);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getUser($id) {
        $statement = $this->dbConnection->prepareQuery('SELECT * FROM Utenti WHERE ID=?;');
        $statement->bind_param('i', $id);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function updateUser($id, $name, $surname, $birthday, $sex, $username, $mail, $password, $admin=false) {
        $hashed_password = hash('sha256', $password);
        $statement = $this->dbConnection->prepareQuery('UPDATE Utenti SET Nome=?, Cognome=?, DataNascita=?, Sesso=?, Username=?, Email=?, Password=?, Admin=? WHERE ID=?;');
        $statement->bind_param('sssssssii', $name, $surname, $birthday, $sex, $username, $mail, $hashed_password, $admin, $id);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }

    public function deleteUser($id) {
        $statement = $this->dbConnection->prepareQuery('DELETE FROM Utenti WHERE ID=?');
        $statement->bind_param('i', $id);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }
}

?>
