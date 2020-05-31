<?php

require_once('Repository/EventsRepository.php');

class EventsController {
    private $events;

    private static function checkInput($title, $description, $begin_date, $end_date, $type, $manager) {
        $message = '';

        if (strlen($title) === 0) {
            $message .= '[Il titolo dell\'evento non può essere vuoto]';
        } elseif (strlen($title) < 3) {
            $message .= '[Il titolo dell\'evento deve essere lungo almeno 3 caratteri]';
        }

        if (strlen($description) === 0) {
            $message .= '[La descrizione dell\'evento non può essere vuota]';
        } elseif (strlen($description) < 30) {
            $message .= '[La descrizione dell\'evento deve essere lunga almeno 30 caratteri]';
        }

        if (strlen($begin_date) === 0) {
            $message .= '[La data d\'inizio dell\'evento non può essere vuota]';
        }

        if (strlen($end_date) === 0) {
            $message .= '[La data di fine dell\'evento non può essere vuota]';
        }

        if ($type !== 'Mostra' && $type !== 'Conferenza') {
            $message .= '[La tipologia dell\'evento è inesistente]';
        }

        if (strlen($manager) === 0) {
            $message .= '[L\'organizzatore dell\'evento non può essere vuoto]';
        }

        return $message;
    }

    public function __construct() {
        $this->events = new EventsRepository();
    }

    public function __destruct() {
        unset($this->events);
    }

    public function addEvent($title, $description, $begin_date, $end_date, $type, $manager, $user) {
        $message = EventsController::checkInput($title, $description, $begin_date, $end_date, $type, $manager);

        if ($message === '') {
            if ($this->events->postEvent($title, $description, $begin_date, $end_date, $type, $manager, $user)) {
                $message = '<p class="success">L\' evento ' . $title . ' è stato inserito correttamente</p>';
            } else {
                $message = '<p class="error">Errore nell\'inserimento dell\' evento ' . $title . '</p>';
            }
        } else {
            $message = '<ul>' . $message;
            $message = str_replace('[', '<li class="error">', $message);
            $message = str_replace(']', '</li>', $message);
            $message .= '</ul>';
        }

        return $message;
    }

    public function getSearchedEventsCount($search) {
        $result_set = $this->events->getSearchedEventsCount($search);
        $count = $result_set->fetch_assoc()['Totale'];
        $result_set->free();
        return $count;
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

    public function getSearchedEvents($search, $offset) {
        $result_set = $this->events->getSearchedEvents($search, $offset);

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
                    <a href="#' . ($result_set->num_rows === $counter ? $button : $id . ($counter + 1)) . '" class="skipInformation" aria-label="Salta l\'evento">Salta l\'evento</a>
    
                    <dl>
                        <dt>
                            Data inizio evento: 
                        </dt>
                        <dd>
                            ' . $row['DataInizio'] . '
                        </dd>
                        
                        <dt>
                            Data chiusura evento: 
                        </dt>
                        <dd>
                            ' . $row['DataFine'] . '
                        </dd>
    
                        <dt>
                            Tipologia: 
                        </dt>
                        <dd>
                            ' . $row['Tipologia'] . '
                        </dd>
                    </dl>
                    
                </dd>
            ';

            $counter++;
        }

        $result_set->free();

        return $content;
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
                    <a href="#' . ($result_set->num_rows === $counter ? $button : $id . ($counter + 1)) . '" class="skipInformation" aria-label="Salta l\'evento">Salta l\'evento</a>
    
                    <dl>
                        <dt>
                            Data inizio evento: 
                        </dt>
                        <dd>
                            ' . $row['DataInizio'] . '
                        </dd>
                        
                        <dt>
                            Data chiusura evento: 
                        </dt>
                        <dd>
                            ' . $row['DataFine'] . '
                        </dd>
    
                        <dt>
                            Tipologia: 
                        </dt>
                        <dd>
                            ' . $row['Tipologia'] . '
                        </dd>
                    </dl>
                </dd>
            ';

            $counter++;
        }

        $result_set->free();

        return $content;
    }

    public function getEventsTitle($type, $offset, $quantity = 5) {
        if($type === '') {
            $result_set = $this->events->getEventsOrderByTitle($offset, $quantity);
        } else {
            $result_set = $this->events->getEventsByTypeOrderByTitle($type, $offset, $quantity);
        }

        $content = '';

        while($row = $result_set->fetch_assoc()) {
            $content .= '
                <li>
                    <a href="Evento.php?id=' . $row['ID'] . '" aria-label="Vai all\'evento">' . $row['Titolo'] . '</a>
                    
                    <form class="userButton" action="EliminaContenuto.php" method="post" role="form">
                        <fieldset class="hideFieldset">
                            <legend class="hideLegend">Pulsanti di modifica ed eliminazione dell\'evento</legend>
                            
                            <input type="hidden" name="id" value="' . $row['ID'] . '"/>
                            <input type="hidden" name="type" value="Evento"/>
                            
                            <a class="button" href="ModificaEvento.php?id=' . $row['ID'] . '" title="Modifica dettagli evento" role="button" aria-label="Modifica dettagli evento">Modifica</a>
                            <input id="buttonConfirm" class="button" name="submit" type="submit" value="Rimuovi" role="button" aria-label="Rimuovi evento"/>
                        </fieldset>
                    </form>
                </li>
            ';
        }

        $result_set->free();
        return $content;
    }

    public function getEvent($id) {
        $result_set = $this->events->getEvent($id);
        $row = $result_set->fetch_assoc();
        $result_set->free();
        return $row;
    }

    public function updateEvent($id, $title, $description, $begin_date, $end_date, $type, $manager, $user) {
        $message = EventsController::checkInput($title, $description, $begin_date, $end_date, $type, $manager);

        if ($message === '') {
            if ($this->events->updateEvent($id, $title, $description, $begin_date, $end_date, $type, $manager, $user)) {
                $message = '<p class="success">Evento aggiornato correttamente</p>';
            } else {
                $message = '<p class="error">Errore nell\'aggiornamento dell\'evento</p>';
            }
        } else {
            $message = '<ul>' . $message;
            $message = str_replace('[', '<li class="error">', $message);
            $message = str_replace(']', '</li>', $message);
            $message .= '</ul>';
        }

        return $message;
    }

    public function deleteEvent($id) {
        $this->events->deleteEvent($id);
    }
}

?>
