<?php

require_once("Database/DatabaseAccess.php");

class EventsRepository {
    private $dbConnection;

    public function __construct() {
        $this->dbConnection = new DatabaseAccess();
    }

    public function close() {
        $this->dbConnection->closeConnection();
        unset($this->dbConnection);
    }

    public function postEvent($title, $description, $beginDate, $endDate, $type, $manager, $image, $user) {
        $statement = $this->dbConnection->prepareQuery("INSERT INTO Eventi (ID, Titolo, Descrizione, DataInizio, DataFine, Tipologia, Organizzatore, Immagine, Utente) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?);");
        $statement->bind_param('ssssssss', $title, $description, $beginDate, $endDate, $type, $manager, $image, $user);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }

    public function getEvents($offset) {
        $statement = $this->dbConnection->prepareQuery("SELECT * FROM Eventi ORDER BY DataInizio, DataFine DESC LIMIT 5, ?;");
        $statement->bind_param('i', $offset);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getEventsByType($type, $offset) {
        $statement = $this->dbConnection->prepareQuery("SELECT * FROM Eventi WHERE Tipologia=? ORDER BY DataInizio, DataFine DESC LIMIT 5, ?;");
        $statement->bind_param('si', $type, $offset);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getEvent($id) {
        $statement = $this->dbConnection->prepareQuery("SELECT * FROM Eventi WHERE ID = ?;");
        $statement->bind_param('i', $id);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function updateEvent($id, $title, $description, $beginDate, $endDate, $type, $manager, $image, $user) {
        $statement = $this->dbConnection->prepareQuery("UPDATE Eventi SET Titlo=?, Descrizione=?, DataInizio=?, DataFine=?, Tipologia=?, Organizzatore=?, Immagine=?, Utente=? WHERE ID=?;");
        $statement->bind_param('ssssssssi', $title, $description, $beginDate, $endDate, $type, $manager, $image, $user, $id);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }

    public function deleteEvent($id) {
        $statement = $this->dbConnection->prepareQuery("DELETE FROM Eventi WHERE ID=?;");
        $statement->bind_param('i', $id);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }
}

?>
