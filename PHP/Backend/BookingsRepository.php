<?php

require_once("DatabaseAccess.php");

class BookingsRepository {
    private $dbConnection;

    public function __construct() {
        $this->dbConnection = new DatabaseAccess();
    }

    public function postBooking($type, $fullPrice, $reducedPrice, $guide, $date, $time, $user) {
        $code = (mysqli_fetch_assoc($this->dbConnection->executeQuery("SELECT * FROM Prenotazioni WHERE Utente=$user;")))['Codice'] + 1;
        return $this->dbConnection->executeQuery("INSERT INTO Prenotazioni (ID, Codice, Tipologia, Interi, Ridotti, Guida, Giorno, Orario, DataAcquisto, Utente) VALUES (NULL, $code, '$type', $fullPrice, $reducedPrice, $guide, '$date', '$time', NOW(), $user);");
    }

    public function getBookings($user, $offset) {
        return mysqli_fetch_assoc($this->dbConnection->executeQuery("SELECT * FROM Prenotazioni WHERE Utente=$user ORDER BY Giorno, Orario DESC LIMIT 5, $offset;"));
    }
}

?>
