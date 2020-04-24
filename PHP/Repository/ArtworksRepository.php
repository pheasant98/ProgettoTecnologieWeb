<?php

require_once("DatabaseAccess.php");

class ArtworksRepository {
    private $dbConnection;

    public function __construct() {
        $this->dbConnection = new DatabaseAccess();
    }

    public function postPainting($author, $title, $description, $years, $historicalPeriod, $technique, $dimensions, $loan, $image, $user) {
        return $this->dbConnection->executeQuery("INSERT INTO Opere (ID, Autore, Titolo, Descrizione, Anni, PeriodoStorico, Stile, Tecnica, Dimensioni, Prestito, Immagine, Utente) VALUES (NULL, '$author', '$title', '$description', '$years', '$historicalPeriod', 'Pittura', '$technique', '$dimensions', $loan, '$image', $user);");
    }

    public function postSculture($author, $title, $description, $years, $historicalPeriod, $material, $dimensions, $loan, $image, $user) {
        return $this->dbConnection->executeQuery("INSERT INTO Opere (ID, Autore, Titolo, Descrizione, Anni, PeriodoStorico, Stile, Materiale, Dimensioni, Prestito, Immagine, Utente) VALUES (NULL, '$author', '$title', '$description', '$years', '$historicalPeriod', 'Scultura', '$material', '$dimensions', $loan, '$image', $user);");
    }

    public function getArtworks($offset) {
        return mysqli_fetch_assoc($this->dbConnection->executeQuery("SELECT * FROM Opere ORDER BY Titolo LIMIT 5, $offset;"));
    }

    public function getArtworksByHistoricalPeriod($historicalPeriod, $offset) {
        return mysqli_fetch_assoc($this->dbConnection->executeQuery("SELECT * FROM Opere WHERE PeriodoStorico='$historicalPeriod' ORDER BY Titolo LIMIT 5, $offset;"));
    }

    public function getArtworksByType($type, $offset) {
        return mysqli_fetch_assoc($this->dbConnection->executeQuery("SELECT * FROM Opere WHERE Stile='$type' ORDER BY Titolo LIMIT 5, $offset;"));
    }

    public function getArtworksByTypeAndHistoricalPeriod($type, $historicalPeriod, $offset) {
        return mysqli_fetch_assoc($this->dbConnection->executeQuery("SELECT * FROM Opere WHERE Stile='$type' AND PeriodoStorico='$historicalPeriod' ORDER BY Titolo LIMIT 5, $offset;"));
    }

    public function getArtwork($id) {
        return mysqli_fetch_assoc($this->dbConnection->executeQuery("SELECT * FROM Opere WHERE ID=$id;"));
    }

    public function updatePainting($id, $author, $title, $description, $years, $historicalPeriod, $technique, $dimensions, $loan, $image, $user) {
        return $this->dbConnection->executeQuery("UPDATE Opere SET Autore='$author', Titolo=$title'', Descrizione='$description', Anni='$years', PeriodoStorico='$historicalPeriod', Tecnica='$technique', Dimensioni='$dimensions', Prestito='$loan', Immagine='$image', Utente='$user' WHERE ID=$id;");
    }

    public function updateSculture($id, $author, $title, $description, $years, $historicalPeriod, $material, $dimensions, $loan, $image, $user) {
        return $this->dbConnection->executeQuery("UPDATE Opere SET Autore='$author', Titolo=$title'', Descrizione='$description', Anni='$years', PeriodoStorico='$historicalPeriod', Materiale='$material', Dimensioni='$dimensions', Prestito='$loan', Immagine='$image', Utente='$user' WHERE ID=$id;");
    }

    public function deleteArtwork($id) {
        return $this->dbConnection->executeQuery("DELETE FROM Opere WHERE ID=$id;");
    }
}

?>
