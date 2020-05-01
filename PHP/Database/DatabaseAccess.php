<?php

class DatabaseAccess {
    private $connection;

    # public function __construct($database = 'mtoffole', $pass = 'yu0juo9GeiPheiqu', $user = 'mtoffole', $host = 'localhost') {

    public function __construct($database = 'test_tecweb', $pass = '', $user = 'root', $host = 'localhost') {
        if (!($this->connection = @new mysqli($host, $user, $pass, $database))) {
            error_log('Error number: ' . $this->connection->connect_errno . ' Error message: ' . $this->connection->connect_error);
            echo 'I dati non sono al momento disponibili. Se l\'errore persiste si prega di segnalarlo al supporto tecnico.';
        }
    }

    public function __destruct() {
        $this->closeConnection();
        unset($this->connection);
    }

    public function closeConnection() {
        @$this->connection->close();
    }

    public function prepareQuery($query) {
        return @$this->connection->prepare($query);
    }

    public function executeNotSelectStatement($statement) {
        $result = @$statement->execute();
        $statement->close();
        return $result;
    }

    public function executeSelectStatement($statement) {
        @$statement->execute();
        $result = @$statement->get_result();
        $statement->close();
        return $result;
    }
}

?>
