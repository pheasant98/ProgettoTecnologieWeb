<?php

require_once("Database/DatabaseAccess.php");

class ReviewsRepository {
    private $dbConnection;

    public function __construct() {
        $this->dbConnection = new DatabaseAccess();
    }

    public function postReview($title, $content, $user) {
        $statement = $this->dbConnection->prepare("INSERT INTO Recensioni (ID, Oggetto, Contenuto, DataPubblicazione, Utente) VALUES (NULL, ?, ?, NOW(), ?);");
        $statement->bind_param('sss', $title, $content, $user);
        return $this->dbConnection->executeQuery($statement);
    }

    public function getAllReview($offset) {
        $statement = $this->dbConnection->prepare("SELECT * FROM Recensioni ORDER BY DataPubblicazione DESC LIMIT 5, ?;");
        $statement->bind_param('i', $offset);
        return @mysqli_fetch_assoc($this->dbConnection->executeQuery($statement));
    }

    public function getUserReview($user, $offset) {
        $statement = $this->dbConnection->prepare("SELECT * FROM Recensioni WHERE Utente = ? ORDER BY DataPubblicazione DESC LIMIT 5, ?;");
        $statement->bind_param('si', $user, $offset);
        return @mysqli_fetch_assoc($this->dbConnection->executeQuery($statement));
    }

    public function deleteReview($id) {
        $statement = $this->dbConnection->prepare("DELETE FROM Recensioni WHERE ID = ?;");
        $statement->bind_param('i', $id);
        return $this->dbConnection->executeQuery($statement);
    }
}

?>
