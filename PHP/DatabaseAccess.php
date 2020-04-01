<?php

class DatabaseAccess {
    private $connection = null;

    public function open_connection($database = "mtoffole", $pass = "", $user = "mtoffole", $host = "localhost") {
        $this->connection = mysqli_connect($host, $user, $pass, $database);

        if (!$this->connection) {
            return false;
        } else {
            return true;
        }
    }

    public function close_connection() {
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

    public function get_last_two_news() {
        if ($this->connection) {
            $query = "SELECT * FROM Avvisi WHERE DataTermine <= NOW() ORDER BY DataTermine DESC LIMIT 2";

            $result_set = mysqli_query($this->connection, $query);

            if ($result_set && mysqli_num_rows($result_set) > 0) {
                $result_array = [];

                while ($result_row = mysqli_fetch_assoc($result_set)) {
                    array_push($result_array, $result_row);
                }

                $result_set->free();

                return $result_array;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}

?>
