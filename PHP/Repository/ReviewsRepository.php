<?php

require_once("Database/DatabaseAccess.php");

class ReviewsRepository {
    private $dbConnection;

    public function __construct() {
        $this->dbConnection = new DatabaseAccess();
    }

    public function close() {
        $this->dbConnection->closeConnection();
        unset($this->dbConnection);
    }

    public function postReview($title, $content, $user) {
        $statement = $this->dbConnection->prepareQuery("INSERT INTO Recensioni (ID, Oggetto, Contenuto, DataPubblicazione, Utente) VALUES (NULL, ?, ?, NOW(), ?);");
        $statement->bind_param('sss', $title, $content, $user);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }

    public function getAllReview($offset) {
        $statement = $this->dbConnection->prepareQuery("SELECT * FROM Recensioni ORDER BY DataPubblicazione DESC LIMIT 5 OFFSET ?;");
        $statement->bind_param('i', $offset);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getUserReview($user, $offset) {
        $statement = $this->dbConnection->prepareQuery("SELECT * FROM Recensioni WHERE Utente = ? ORDER BY DataPubblicazione DESC LIMIT 5 OFFSET ?;");
        $statement->bind_param('si', $user, $offset);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function deleteReview($id) {
        $statement = $this->dbConnection->prepareQuery("DELETE FROM Recensioni WHERE ID = ?;");
        $statement->bind_param('i', $id);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }
}

?>
