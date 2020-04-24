<?php

class DatabaseAccess {
    private $connection;

    public function __construct($database = "mtoffole", $pass = "", $user = "mtoffole", $host = "localhost") {
        if (!($this->connection = @mysqli_connect($host, $user, $pass, $database))) {
            error_log("Errno: " . mysqli_connect_errno() . " Error: " . mysqli_connect_error());
            echo "I dati non sono al momento disponibili. Se l'errore persiste si prega di segnalarlo al supporto tecnico.";
        }
    }

    public function closeConnection() {
        @mysqli_close($this->connection);
    }

    public function executeQuery($query) {
        return @mysqli_query($this->connection, $query);
    }
}

?>
