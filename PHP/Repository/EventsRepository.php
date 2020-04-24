<?php

require_once("../Database/DatabaseAccess.php");

class EventsRepository {
    private $dbConnection;

    public function __construct() {
        $this->dbConnection = new DatabaseAccess();
    }

    public function postEvent($title, $description, $beginDate, $endDate, $type, $manager, $image, $user) {
        return $this->dbConnection->executeQuery("INSERT INTO Eventi (ID, Titolo, Descrizione, DataInizio, DataFine, Tipologia, Organizzatore, Immagine, Utente) VALUES (NULL, '$title', '$description', '$beginDate', '$endDate', '$type', '$manager', '$image', $user);");
    }

    public function getEvents($offset) {
        return mysqli_fetch_assoc($this->dbConnection->executeQuery("SELECT * FROM Eventi ORDER BY DataInizio, DataFine DESC LIMIT 5, $offset;"));
    }

    public function getEventsByType($type, $offset) {
        return mysqli_fetch_assoc($this->dbConnection->executeQuery("SELECT * FROM Eventi WHERE Tipologia='$type' ORDER BY DataInizio, DataFine DESC LIMIT 5, $offset;"));
    }

    public function getEvent($id) {
        return mysqli_fetch_assoc($this->dbConnection->executeQuery("SELECT * FROM Eventi WHERE ID = $id;"));
    }

    public function updateEvent($id, $title, $description, $beginDate, $endDate, $type, $manager, $image, $user) {
        return $this->dbConnection->executeQuery("UPDATE Eventi SET Titlo='$title', Descrizione='$description', DataInizio='$beginDate', DataFine='$endDate', Tipologia='$type', Organizzatore='$manager', Immagine='$image', Utente=$user WHERE ID=$id;");
    }

    public function deleteEvent($id) {
        return $this->dbConnection->executeQuery("DELETE FROM Eventi WHERE ID=$id;");
    }
}

?>
