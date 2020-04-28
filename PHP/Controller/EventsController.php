<?php

require_once('Repository/EventsRepository.php');

class EventsController {
    private $events;

    public function __construct() {
        $this->events = new EventsRepository();
    }

    public function getEventsCount() {
        return mysqli_fetch_assoc($this->events->getEventsCount());
    }

    public function getEvents($offset) {
        $result_set = $this->events->getEvents($offset);
        $id_ref = "e";
        $button_ref = "buttonBack";
        $counter = 1;
        $content = "";
        while($row = mysqli_fetch_assoc($result_set)) {
            $content .= "
                <dt id=\"$id_ref . '' . $counter\">
                     <a href=\"ContenutoSingolo.php?type=evento&id=" . $row["ID"] . "\" aria-label=\"Vai all'evento\">" . $row["Titolo"] . "</a>
                </dt>
                <dd>
                    <a href=\"#" . ($result_set->num_rows == $counter ? $button_ref : $id_ref . ($counter+1)) . "\" class=\"skipInformation\" aria-label=\"Salta l'evento\">Salta l'evento</a>
    
                    <p>
                        Data inizio evento: " . $row["DataInizio"] . "
                    </p>
                    
                    <p>
                        Data chiusura evento: " . $row["DataFine"] . "
                    </p>

                    <p>
                        Tipologia: " . $row["Tipologia"] . "
                    </p>

                    <p>
                        " . $row["Descrizione"] . "
                    </p>
                </dd>
            ";
            $counter++;
        }
        return $content;
    }
}