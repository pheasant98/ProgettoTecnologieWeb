<?php

require_once("Database/DatabaseAccess.php");

class ArtworksRepository {
    private $dbConnection;

    public function __construct() {
        $this->dbConnection = new DatabaseAccess();
    }

    public function close() {
        $this->dbConnection->closeConnection();
        unset($this->dbConnection);
    }

    public function postPainting($author, $title, $description, $years, $technique, $dimensions, $loan, $image, $user) {
        $statement = $this->dbConnection->prepareQuery("INSERT INTO Opere (ID, Autore, Titolo, Descrizione, Anni, Stile, Tecnica, Dimensioni, Prestito, Immagine, Utente) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
        $statement->bind_param('sssississ', $author, $title, $description, $years, $technique, $dimensions, $loan, $image, $user);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }

    public function postSculture($author, $title, $description, $years, $material, $dimensions, $loan, $image, $user) {
        $statement = $this->dbConnection->prepareQuery("INSERT INTO Opere (ID, Autore, Titolo, Descrizione, Anni, Stile, Materiale, Dimensioni, Prestito, Immagine, Utente) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
        $statement->bind_param('sssississ', $author, $title, $description, $years, $material, $dimensions, $loan, $image, $user);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }

    public function getArtworks($offset) {
        $statement = $this->dbConnection->prepareQuery("SELECT * FROM Opere ORDER BY Titolo LIMIT 5, ?;");
        $statement->bind_param('i', $offset);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getArtworksByType($type, $offset) {
        $statement = $this->dbConnection->prepareQuery("SELECT * FROM Opere WHERE Stile= ? ORDER BY Titolo LIMIT 5, ?;");
        $statement->bind_param('si', $type, $offset);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getArtwork($id) {
        $statement = $this->dbConnection->prepareQuery("SELECT * FROM Opere WHERE ID= ?;");
        $statement->bind_param('i', $id);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function updatePainting($id, $author, $title, $description, $years, $technique, $dimensions, $loan, $image, $user) {
        $statement = $this->dbConnection->prepareQuery("UPDATE Opere SET Autore=?, Titolo=?, Descrizione=?, Anni=?, Tecnica=?, Dimensioni=?, Prestito=?, Immagine=?, Utente=? WHERE ID=?;");
        $statement->bind_param('sssississi', $author, $title, $description, $years, $technique, $dimensions, $loan, $image, $user, $id);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }

    public function updateSculture($id, $author, $title, $description, $years, $material, $dimensions, $loan, $image, $user) {
        $statement = $this->dbConnection->prepareQuery("UPDATE Opere SET Autore=?, Titolo=?, Descrizione=?, Anni=?, Materiale=?, Dimensioni=?, Prestito=?, Immagine=?, Utente=? WHERE ID=?;");
        $statement->bind_param('sssississi', $author, $title, $description, $years, $material, $dimensions, $loan, $image, $user, $id);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }

    public function deleteArtwork($id) {
        $statement = $this->dbConnection->prepareQuery("DELETE FROM Opere WHERE ID=?;");
        $statement->bind_param('i', $id);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }
}

?>
