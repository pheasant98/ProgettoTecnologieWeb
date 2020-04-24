<?php

require_once("Database/DatabaseAccess.php");

class ReviewsRepository {
    private $dbConnection;

    public function __construct() {
        $this->dbConnection = new DatabaseAccess();
    }

    public function postReview($title, $content, $user) {
        return $this->dbConnection->executeQuery("INSERT INTO Recensioni (ID, Oggetto, Contenuto, DataPubblicazione, Utente) VALUES (NULL, '$title', '$content', NOW(), $user);");
    }

    public function getAllReview($offset) {
        return @mysqli_fetch_assoc($this->dbConnection->executeQuery("SELECT * FROM Recensioni ORDER BY DataPubblicazione DESC LIMIT 5, $offset;"));
    }

    public function getUserReview($user, $offset) {
        return @mysqli_fetch_assoc($this->dbConnection->executeQuery("SELECT * FROM Recensioni WHERE Utente = $user ORDER BY DataPubblicazione DESC LIMIT 5, $offset;"));
    }

    public function deleteReview($id) {
        return $this->dbConnection->executeQuery("DELETE FROM Recensioni WHERE ID = $id;");
    }
}

?>
