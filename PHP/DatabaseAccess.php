<?php

class DatabaseAccess {
    private $connection = null;

    public function openConnection($database = "mtoffole", $pass = "", $user = "mtoffole", $host = "localhost") {
        $this->connection = mysqli_connect($host, $user, $pass, $database);

        if (!$this->connection) {
            return false;
        } else {
            return true;
        }
    }

    public function closeConnection() {
        if ($this->connection) {
            if (!$this->connection->close()) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    public function getLastTwoNews() {
        if ($this->connection) {
            $query = "SELECT * FROM Avvisi WHERE DataTermine <= NOW() ORDER BY DataTermine DESC LIMIT 2";

            $resultSet = mysqli_query($this->connection, $query);

            if ($resultSet && mysqli_num_rows($resultSet) > 0) {
                $resultArray = array();

                while ($resultRow = mysqli_fetch_assoc($resultSet)) {
                    array_push($resultArray, $resultRow);
                }

                $resultSet->free();

                return $resultArray;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}

?>