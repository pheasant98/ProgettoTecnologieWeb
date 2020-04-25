<?php

require_once("../Database/DatabaseAccess.php");

class ArtworksRepository {
    private $dbConnection;

    public function __construct() {
        $this->dbConnection = new DatabaseAccess();
    }

    public function postPainting($author, $title, $description, $years, $technique, $dimensions, $loan, $image, $user) {
        $statement = $this->dbConnection->prepare("INSERT INTO Opere (ID, Autore, Titolo, Descrizione, Anni, Stile, Tecnica, Dimensioni, Prestito, Immagine, Utente) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
        $statement->bind_param('sssississ', $author, $title, $description, $years, $technique, $dimensions, $loan, $image, $user);
        return $this->dbConnection->executeQuery($statement);
    }

    public function postSculture($author, $title, $description, $years, $material, $dimensions, $loan, $image, $user) {
        $statement = $this->dbConnection->prepare("INSERT INTO Opere (ID, Autore, Titolo, Descrizione, Anni, Stile, Materiale, Dimensioni, Prestito, Immagine, Utente) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
        $statement->bind_param('sssississ', $author, $title, $description, $years, $material, $dimensions, $loan, $image, $user);
        return $this->dbConnection->executeQuery($statement);
    }

    public function getArtworks($offset) {
        $statement = $this->dbConnection->prepare("SELECT * FROM Opere ORDER BY Titolo LIMIT 5, ?;");
        $statement->bind_param('i', $offset);
        return mysqli_fetch_assoc($this->dbConnection->executeQuery($statement));
    }

    public function getArtworksByType($type, $offset) {
        $statement = $this->dbConnection->prepare("SELECT * FROM Opere WHERE Stile= ? ORDER BY Titolo LIMIT 5, ?;");
        $statement->bind_param('si', $type, $offset);
        return mysqli_fetch_assoc($this->dbConnection->executeQuery($statement));
    }

    public function getArtwork($id) {
        $statement = $this->dbConnection->prepare("SELECT * FROM Opere WHERE ID= ?;");
        $statement->bind_param('i', $id);
        return mysqli_fetch_assoc($this->dbConnection->executeQuery($statement));
    }

    public function updatePainting($id, $author, $title, $description, $years, $technique, $dimensions, $loan, $image, $user) {
        $statement = $this->dbConnection->prepare("UPDATE Opere SET Autore=?, Titolo=?, Descrizione=?, Anni=?, Tecnica=?, Dimensioni=?, Prestito=?, Immagine=?, Utente=? WHERE ID=?;");
        $statement->bind_param('sssississi', $author, $title, $description, $years, $technique, $dimensions, $loan, $image, $user, $id);
        return $this->dbConnection->executeQuery($statement);
    }

    public function updateSculture($id, $author, $title, $description, $years, $material, $dimensions, $loan, $image, $user) {
        $statement = $this->dbConnection->prepare("UPDATE Opere SET Autore=?, Titolo=?, Descrizione=?, Anni=?, Materiale=?, Dimensioni=?, Prestito=?, Immagine=?, Utente=? WHERE ID=?;");
        $statement->bind_param('sssississi', $author, $title, $description, $years, $material, $dimensions, $loan, $image, $user, $id);
        return $this->dbConnection->executeQuery($statement);
    }

    public function deleteArtwork($id) {
        $statement = $this->dbConnection->prepare("DELETE FROM Opere WHERE ID=?;");
        $statement->bind_param('i', $id);
        return $this->dbConnection->executeQuery($statement);
    }
}

?>
