<?php

require_once('Repository/EventsRepository.php');

class EventsController {
    private $events;

    public function __construct() {
        $this->events = new EventsRepository();
    }

    public function getEventsCount() {
        return mysqli_fetch_assoc($this->events->getEventsCount())["Totale"];
    }

    public function getEventsCountByType($type) {
        return mysqli_fetch_assoc($this->events->getEventsCountByType($type))["Totale"];
    }

    public function getEvents($type, $offset) {
        if($type == "") {
            $result_set = $this->events->getEvents($offset);
        } else {
            $result_set = $this->events->getEventsByType($type, $offset);
        }
        $id_ref = "event";
        $button_ref = "buttonBack";
        $counter = 1;
        $content = "";
        while($row = mysqli_fetch_assoc($result_set)) {
            $content .= "
                <dt id=\"$id_ref . '' . $counter\">
                     <a href=\"ContenutoSingolo.php?type=Evento&id=" . $row["ID"] . "\" aria-label=\"Vai all'evento\">" . $row["Titolo"] . "</a>
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
                </dd>
            ";
            $counter++;
        }
        return $content;
    }

    public function getEvent($id) {
        $result_set = $this->events->getEvent($id);
        $row = mysqli_fetch_assoc($result_set);
        return ' <h3 class="subtitle">' . $row["Titolo"] . '</h3>
                 <dl>
                     <dt>
                        Tipologia:
                     </dt>
                     <dd>
                         ' . $row["Tipologia"] . '
                     </dd>
                    
                     <dt>
                         Data inizio:
                     </dt>
                     <dd>
                         ' . $row["DataInizio"] . '
                     </dd>
                    
                     <dt>
                         Data fine:
                     </dt>
                     <dd>
                         ' . $row["DataFine"] . '
                     </dd>
        
                     <dt>
                         Organizzatore:
                     </dt>
                     <dd>
                         ' . $row["Organizzatore"] . '
                     </dd>
                 </dl>     
                 <p class="paragraph">
                     ' . $row["Descrizione"] . '
                 </p>
                ';
    }
}