<?php

require_once('Database/DatabaseAccess.php');

class ArtworksRepository {
    private $dbConnection;

    public function __construct() {
        $this->dbConnection = new DatabaseAccess();
    }

    public function __destruct() {
        unset($this->dbConnection);
    }

    public function postPainting($author, $title, $description, $years, $technique, $dimensions, $loan, $image, $user) {
        $statement = $this->dbConnection->prepareQuery('INSERT INTO Opere (ID, Autore, Titolo, Descrizione, Datazione, Stile, Tecnica, Dimensioni, Prestito, Immagine, Utente) VALUES (NULL, ?, ?, ?, ?, "Dipinti", ?, ?, ?, ?, ?);');
        $statement->bind_param('ssssssiss', $author, $title, $description, $years, $technique, $dimensions, $loan, $image, $user);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }

    public function postSculture($author, $title, $description, $years, $material, $dimensions, $loan, $image, $user) {
        $statement = $this->dbConnection->prepareQuery('INSERT INTO Opere (ID, Autore, Titolo, Descrizione, Datazione, Stile, Materiale, Dimensioni, Prestito, Immagine, Utente) VALUES (NULL, ?, ?, ?, ?, "Sculture", ?, ?, ?, ?, ?);');
        $statement->bind_param('ssssssiss', $author, $title, $description, $years, $material, $dimensions, $loan, $image, $user);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }

    public function getSearchedArtworks($search, $offset) {
        $statement = $this->dbConnection->prepareQuery('SELECT * FROM Opere WHERE Titolo LIKE ? ORDER BY Titolo LIMIT 5 OFFSET ?;');
        $search = '%' . $search . '%';
        $statement->bind_param('si', $search, $offset);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getSearchedArtworksCount($search) {
        $statement = $this->dbConnection->prepareQuery('SELECT COUNT(*) AS Totale FROM Opere WHERE Titolo LIKE ?;');
        $search = '%' . $search . '%';
        $statement->bind_param('s', $search);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getArtworks($offset) {
        $statement = $this->dbConnection->prepareQuery('SELECT * FROM Opere ORDER BY Titolo LIMIT 5 OFFSET ?;');
        $statement->bind_param('i', $offset);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getArtworksCount() {
        $statement = $this->dbConnection->prepareQuery('SELECT COUNT(*) AS Totale FROM Opere;');
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getArtworksByStyle($style, $offset) {
        $statement = $this->dbConnection->prepareQuery('SELECT * FROM Opere WHERE Stile=? ORDER BY Titolo LIMIT 5 OFFSET ?;');
        $statement->bind_param('si', $style, $offset);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getArtworksCountByStyle($style) {
        echo $style;
        $statement = $this->dbConnection->prepareQuery('SELECT COUNT(*) AS Totale FROM Opere WHERE Stile=?;');
        $statement->bind_param('s', $style);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function getArtwork($id) {
        $statement = $this->dbConnection->prepareQuery('SELECT * FROM Opere WHERE ID=?;');
        $statement->bind_param('i', $id);
        return $this->dbConnection->executeSelectStatement($statement);
    }

    public function updateArtwork($id, $author, $title, $description, $years, $technique, $material, $dimensions, $loan, $image, $user) {
        $statement = $this->dbConnection->prepareQuery('UPDATE Opere SET Autore=?, Titolo=?, Descrizione=?, Datazione=?, Tecnica=?, Materiale=?, Dimensioni=?, Prestito=?, Immagine=?, Utente=? WHERE ID=?;');
        $statement->bind_param('sssssssissi', $author, $title, $description, $years, $technique, $material, $dimensions, $loan, $image, $user, $id);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }

    public function deleteArtwork($id) {
        $statement = $this->dbConnection->prepareQuery('DELETE FROM Opere WHERE ID=?;');
        $statement->bind_param('i', $id);
        return $this->dbConnection->executeNotSelectStatement($statement);
    }
}

?>
