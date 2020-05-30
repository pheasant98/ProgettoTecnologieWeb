<?php

require_once('Database/DatabaseAccess.php');

class EventsRepository {
    private $dbConnection;

    public function __construct() {
        $this->dbConnection = new DatabaseAccess();
    }

    public function __destruct() {
        unset($this->dbConnection);
    }

    public function postEvent($title, $description, $begin_date, $end_date, $type, $manager, $user) {
        $statement = $this->dbConnection->prepareQuery('INSERT INTO Eventi (ID, Titolo, Descrizione, DataInizio, DataFine, Tipologia, Organizzatore, Utente) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?);');
        $statement->bind_param('sssssss', $title, $description, $begin_date, $end_date, $type, $manager, $user);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }

    public function getSearchedEvents($search, $offset) {
        $statement = $this->dbConnection->prepareQuery('SELECT * FROM Eventi WHERE Titolo LIKE ? ORDER BY DataInizio, DataFine DESC LIMIT 5 OFFSET ?;');
        $search = '%' . $search . '%';
        $statement->bind_param('si', $search, $offset);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getSearchedEventsCount($search) {
        $statement = $this->dbConnection->prepareQuery('SELECT COUNT(*) AS Totale FROM Eventi WHERE Titolo LIKE ?;');
        $search = '%' . $search . '%';
        $statement->bind_param('s', $search);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getEvents($offset) {
        $statement = $this->dbConnection->prepareQuery('SELECT * FROM Eventi ORDER BY DataInizio, DataFine DESC LIMIT 5 OFFSET ?;');
        $statement->bind_param('i', $offset);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getEventsOrderByTitle($offset, $quantity = 5) {
        $statement = $this->dbConnection->prepareQuery('SELECT * FROM Eventi ORDER BY Titolo ASC LIMIT ? OFFSET ?;');
        $statement->bind_param('ii', $quantity,$offset);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getEventsCount() {
        $statement = $this->dbConnection->prepareQuery('SELECT COUNT(*) AS Totale FROM Eventi;');
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getEventsByType($type, $offset) {
        $statement = $this->dbConnection->prepareQuery('SELECT * FROM Eventi WHERE Tipologia=? ORDER BY DataInizio, DataFine DESC LIMIT 5 OFFSET ?;');
        $statement->bind_param('si', $type, $offset);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getEventsByTypeOrderByTitle($type, $offset, $quantity = 5) {
        $statement = $this->dbConnection->prepareQuery('SELECT * FROM Eventi WHERE Tipologia=? ORDER BY Titolo DESC LIMIT ? OFFSET ?;');
        $statement->bind_param('sii', $type, $quantity, $offset);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getEventsCountByType($type) {
        $statement = $this->dbConnection->prepareQuery('SELECT COUNT(*) AS Totale FROM Eventi WHERE Tipologia=?;');
        $statement->bind_param('s', $type);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getEvent($id) {
        $statement = $this->dbConnection->prepareQuery('SELECT * FROM Eventi WHERE ID=?;');
        $statement->bind_param('i', $id);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function updateEvent($id, $title, $description, $begin_date, $end_date, $type, $manager, $user) {
        $statement = $this->dbConnection->prepareQuery('UPDATE Eventi SET Titolo=?, Descrizione=?, DataInizio=?, DataFine=?, Tipologia=?, Organizzatore=?, Utente=? WHERE ID=?;');
        $statement->bind_param('sssssssi', $title, $description, $begin_date, $end_date, $type, $manager, $user, $id);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }

    public function deleteEvent($id) {
        $statement = $this->dbConnection->prepareQuery('DELETE FROM Eventi WHERE ID=?;');
        $statement->bind_param('i', $id);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }
}

?>
