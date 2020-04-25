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
        @$this->connection->close();
    }

    public function prepareQuery($query) {
        return @$this->connection->prepare($query);
    }

    public function executeStatement($statement) {
        
    }
}

?>
