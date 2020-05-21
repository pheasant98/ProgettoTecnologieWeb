<?php

require_once('Repository/EventsRepository.php');

class EventsController {
    private $events;

    public function __construct() {
        $this->events = new EventsRepository();
    }

    public function __destruct() {
        unset($this->events);
    }

    public function getEventsCount() {
        $result_set = $this->events->getEventsCount();
        $count = $result_set->fetch_assoc()['Totale'];
        $result_set->free();
        return $count;
    }

    public function getEventsCountByType($type) {
        $result_set = $this->events->getEventsCountByType($type);
        $count = $result_set->fetch_assoc()['Totale'];
        $result_set->free();
        return $count;
    }

    public function getEvents($type, $offset) {
        if($type === '') {
            $result_set = $this->events->getEvents($offset);
        } else {
            $result_set = $this->events->getEventsByType($type, $offset);
        }

        $id = 'event';
        $button = 'buttonBack';
        $counter = 1;
        $content = '';

        while($row = $result_set->fetch_assoc()) {
            $content .= '
                <dt id="' . $id . $counter . '">
                     <a href="Evento.php?id=' . $row['ID'] . '\" aria-label="Vai all\'evento">' . $row['Titolo'] . '</a>
                </dt>
                <dd>
                    <a href="#' . ($result_set->num_rows == $counter ? $button : $id . ($counter + 1)) . '" class="skipInformation" aria-label="Salta l\'evento">Salta l\'evento</a>
    
                    <p>
                        Data inizio evento: ' . $row['DataInizio'] . '
                    </p>
                    
                    <p>
                        Data chiusura evento: ' . $row['DataFine'] . '
                    </p>

                    <p>
                        Tipologia: ' . $row['Tipologia'] . '
                    </p>
                </dd>
            ';

            $counter++;
        }

        $result_set->free();

        return $content;
    }

    public function getEvent($id) {
        return $result_set = $this->events->getEvent($id)->fetch_assoc();
        $row = $result_set->fetch_assoc();
        $result_set->free();
        return $row;
    }
}

?>
