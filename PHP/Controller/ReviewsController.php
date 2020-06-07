<?php

require_once ('Repository/ReviewsRepository.php');
require_once ('Utilities/DateUtilities.php');

class ReviewsController {
    private $reviews;

    private static function checkInput($object, $content) {
        $message = '';

        if (strlen($object) === 0) {
            $message .= '[Non è possibile inserire una recensione con un titolo vuoto]';
        } elseif (strlen($object) < 2) {
            $message .= '[Non è possibile inserire una recensione con un titolo più corto di 2 caratteri]';
        } elseif (strlen($object) > 64) {
            $message .= '[Non è possibile inserire una recensione con un titolo più lungo di 64 caratteri]';
        } elseif (!preg_match('/^[A-zÀ-ú0-9\'`!.,\-:()\s]+$/', $object)) {
            $message .= '[Il titolo inserito contiene dei caratteri non consentiti. Quelli possibili sono lettere, anche accentate, numeri, spazi e i seguenti caratteri speciali \' ` ! . , \ - : ()]';
        }

        if (strlen($content) === 0) {
            $message .= '[Non è possibile inserire una recensione con un contenuto vuoto]';
        } elseif (strlen($content) < 4) {
            $message .= '[Non è possibile inserire una recensione con un contenuto più corto di 4 caratteri]';
        } elseif (strlen($content) > 65535) {
            $message .= '[Non è possibile inserire una recensione con un contenuto più lungo di 65535 caratteri]';
        }

        return $message;
    }

    public function __construct() {
        $this->reviews = new ReviewsRepository();
    }

    public function __destruct() {
        unset($this->reviews);
    }

    public function addReview($object, $content, $user) {
        $message = ReviewsController::checkInput($object, $content);

        if ($message === '') {
            if ($this->reviews->postReview($object, $content, $user)) {
                $message = '<p class="success">Recensione ' . $object . ' inserita correttamente</p>';
            } else {
                $message = '<p class="error">Non è stato possibile inserire la recensione ' . $object . ', se l\'errore persiste si prega di segnalarlo al supporto tecnico.</p>';
            }
        } else {
            $message = '<p><ul>' . $message;
            $message = str_replace('[', '<li class="error">', $message);
            $message = str_replace(']', '</li>', $message);
            $message = $message . '</ul></p>';
        }

        return $message;
    }
	
	public function getReviewsCount() {
        $result_set = $this->reviews->getReviewsCount();
        $count = $result_set->fetch_assoc()['Totale'];
        $result_set->free();
        return $count;
    }

    public function getUserReviewsCount($user) {
        $result_set = $this->reviews->getUserReviewsCount($user);
        $count = $result_set->fetch_assoc()['Totale'];
        $result_set->free();
        return $count;
    }

    public function getReviews($offset, $button) {
        $result_set = $this->reviews->getReviews($offset);

        $id = 'review';
        $counter = 1;
        $content = '';

        while($row = $result_set->fetch_assoc()) {
            $content .= '
                <dt id="' . $id . $counter . '" class="titleDef">
                    <a href="Recensione.php?id=' . $row['ID'] . '" aria-label="Vai alla recensione">' . $row['Oggetto'] . '</a>
                </dt>
                <dd>
                    <a href="#' . ($result_set->num_rows == $counter ? $button : $id . ($counter + 1)) . '" class="skipInformation" aria-label="Salta la recensione">Salta la recensione</a>
    
                    <dl>
                        <dt class="inlineDef">
                            Utente: 
                        </dt>
                        <dd class="definition">
                            ' . $row['Utente'] . '
                        </dd>
                        
                        <dt class="inlineDef">
                            Data ultima modifica: 
                        </dt>
                        <dd class="definition">
                            ' . DateUtilities::englishItalianDate($row['DataUltimaModifica']) . '
                        </dd>
    
                        <dt class="inlineDef">
                            Contenuto: 
                        </dt>
                        <dd class="definition">
                            ' . substr($row['Contenuto'], 0, 150) . (strlen($row['Contenuto']) > 150 ? '...' : '') . '
                        </dd>
                    </dl>
                </dd>
            ';

            $counter++;
        }

        $result_set->free();

        return $content;
    }

    public function getListReviews($offset) {
        $result_set = $this->reviews->getReviews($offset);

        $content = '';

        while($row = $result_set->fetch_assoc()) {
            $content .= '
                <li>
                    <a href="Recensione.php?id=' . $row['ID'] . '" title="Vai alla pagina della recensione,' . $row['Oggetto'] . ' " aria-label="Vai alla pagina della recensione,' . $row['Oggetto'] . ' ">' . $row['Oggetto'] . '</a>

                    <form class="userButton" action="EliminaRecensione.php" method="post" role="form">
                        <fieldset class="hideRight">
                            <legend class="hideLegend">Pulsante di eliminazione della recensione</legend>
                            
                            <input type="hidden" name="id" value="' . $row['ID'] . '"/>
                            <input class="button" name="submit" type="submit" value="Rimuovi" role="button" title="Rimunovi recensione" aria-label="Rimuovi recensione"/>
                        </fieldset>
                    </form>
                </li>
            ';
        }

        $result_set->free();

        return $content;
    }

    public function getUserListReviews($user, $offset) {
        $result_set = $this->reviews->getUserReviews($user, $offset);

        $content = '';

        while($row = $result_set->fetch_assoc()) {
            $content .= '
                <li>
                    <a href="Recensione.php?id=' . $row['ID'] . '" title="Vai alla pagina della recensione,' . $row['Oggetto'] . ' " aria-label="Vai alla pagina della recensione,' . $row['Oggetto'] . ' ">' . $row['Oggetto'] . '</a>

                    <form class="userButton" action="EliminaRecensione.php" method="post" role="form">
                        <fieldset class="hideRight">
                            <legend class="hideLegend">Pulsanti per la gestione della recensione</legend>
                            
                            <input type="hidden" name="id" value="' . $row['ID'] . '"/>
                            
                            <a class="button" href="ModificaRecensione.php?id=' . $row['ID'] . '" title="Modifica dettagli recensione" role="button" aria-label="Modifica dettagli recensione">Modifica</a>
                            <input class="button" name="submit" type="submit" value="Rimuovi" role="button" title="Rimunovi recensione" aria-label="Rimuovi recensione"/>
                        </fieldset>
                    </form>
                </li>
            ';
        }

        $result_set->free();

        return $content;
    }

    public function getReview($id) {
        $result_set = $this->reviews->getReview($id);
        $row = $result_set->fetch_assoc();
        $result_set->free();
        return $row;
    }

    public function updateReview($id, $object, $description, $user) {
        $message = ReviewsController::checkInput($object, $description);

        if ($message === '') {
            if ($this->reviews->updateReview($id, $object, $description, $user)) {
                $message = '';
            } else {
                $message = '<p class="error">Non è stato possibile aggiornare la recensione ' . $object . ', se l\'errore persiste si prega di segnalarlo al supporto tecnico.</p>';
            }
        } else {
            $message = '<p><ul>' . $message;
            $message = str_replace('[', '<li class="error">', $message);
            $message = str_replace(']', '</li>', $message);
            $message .= '</ul></p>';
        }

        return $message;
    }

    public function deleteReview($id) {
        return $this->reviews->deleteReview($id);
    }
}

?>
