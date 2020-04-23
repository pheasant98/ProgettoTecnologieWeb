<?php

require_once("DatabaseAccess.php");

class NewsRepository {
    private $dbConnection;

    public function __construct() {
        $this->dbConnection = new DatabaseAccess();
    }

    public function postNews($object, $description, $expireDate, $user) {
        return $this->dbConnection->executeQuery("INSERT INTO Avvisi (ID, Oggetto, Descrizione, DataEmissione, DataTermine, Utente) VALUES (NULL, '$object', '$description', NOW(), '$expireDate', $user);");
    }

    public function getNews($offset) {
        return mysqli_fetch_assoc($this->dbConnection->executeQuery("SELECT * FROM Avvisi ORDER BY DataTermine DESC LIMIT 5, $offset;"));
    }

    public function getSingleNews($id) {
        return mysqli_fetch_assoc($this->dbConnection->executeQuery("SELECT * FROM Avvisi WHERE ID = $id;"));
    }

    public function getLatestNews() {
        return mysqli_fetch_assoc($this->dbConnection->executeQuery("SELECT * FROM Avvisi WHERE DataTermine <= NOW() ORDER BY DataTermine DESC LIMIT 2;"));
    }

    public function updateNews($id, $object, $description, $expireDate, $user) {
        return $this->dbConnection->executeQuery("UPDATE Avvisi SET Oggetto='$object', Descrizione='$description', DataTermine='$expireDate', Utente=$user WHERE ID=$id;");
    }

    public function deleteNews($id) {
        return $this->dbConnection->executeQuery("DELETE FROM Avvisi WHERE ID=$id;");
    }
}

?>
