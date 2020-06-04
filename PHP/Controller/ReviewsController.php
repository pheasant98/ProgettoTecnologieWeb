<?php

require_once ('Repository/ReviewsRepository.php');

class ReviewsController {
    private $reviews;

    private static function checkInput($title, $content) {
        $message = '';

        if (strlen($title) === 0) {
            $message .= '[Non è possibile inserire una recensione con un titolo vuoto]';
        } elseif (strlen($title) < 2) {
            $message .= '[Non è possibile inserire una recensione con un titolo più corto di 2 caratteri]';
        } elseif (strlen($title) > 64) {
            $message .= '[Non è possibile inserire una recensione con un titolo più lungo di 64 caratteri]';
        } elseif (!preg_match('/^[A-zÀ-ú0-9\'`!.,\-:()\s]+$/', $title)) {
            $message .= '[Il titolo inserito contiene dei caratteri non consentiti. Quelli possibili sono lettere, anche accentate, numeri, spazi e i seguenti caratteri speciali \' ` ! . , \ - : ()]';
        }

        if (strlen($content) === 0) {
            $message .= '[Non è possibile inserire una recensione con un contenuto vuoto]';
        } elseif (strlen($content) < 30) {
            $message .= '[Non è possibile inserire una recensione con un contenuto più corto di 30 caratteri]';
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

    public function addReview($title, $content, $user) {
        $message = ReviewsController::checkInput($title, $content);

        if ($message === '') {
            if ($this->reviews->postReview($title, $content, $user)) {
                $message = '<p class="success">Recensione inserita correttamente</p>';
            } else {
                $message = '<p class="error">Errore nell\'inserimento della recensione</p>';
            }
        } else {
            $message = '<ul>' . $message;
            $message = str_replace('[', '<li class="error">', $message);
            $message = str_replace(']', '</li>', $message);
            $message = $message . '</ul>';
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

    public function getReviews($offset) {
        $result_set = $this->reviews->getReviews($offset);

        $id = 'review';
        $button = 'buttonBack';
        $counter = 1;
        $content = '';

        while($row = $result_set->fetch_assoc()) {
            $content .= '
                <dt id="' . $id . $counter . '" class="reviewObject">
                     ' . $row['Oggetto'] . '
                </dt>
                <dd>
                    <a href="#' . ($result_set->num_rows == $counter ? $button : $id . ($counter + 1)) . '" class="skipInformation" aria-label="Salta la recensione">Salta la recensione</a>
    
                    <dl>
                        <dt class="reviewAuthor">
                            Utente: 
                        </dt>
                        <dd class="definition">
                            ' . $row['Utente'] . '
                        </dd>
                        
                        <dt class="reviewData">
                            Data ultima modifica: 
                        </dt>
                        <dd class="definition">
                            ' . $row['DataUltimaModifica'] . '
                        </dd>
    
                        <dt class="reviewDescription">
                            Contenuto: 
                        </dt>
                        <dd class="definition">
                            ' . $row['Contenuto'] . '
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
                    <a href="Recensione.php?id=' . $row['ID'] . '" aria-label="Vai alla pagina della recensione">' . $row['Oggetto'] . '</a>

                    <form class="userButton" action="EliminaRecensione.php" method="post" role="form">
                        <fieldset class="hideFieldset">
                            <legend class="hideLegend">Pulsante di eliminazione della recensione</legend>
                            
                            <input type="hidden" name="id" value="' . $row['ID'] . '"/>
                            <input class="button" name="submit" type="submit" value="Rimuovi" role="button" aria-label="Rimuovi recensione"/>
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
                    <a href="Recensione.php?id=' . $row['ID'] . '" aria-label="Vai alla pagina della recensione">' . $row['Oggetto'] . '</a>

                    <form class="userButton" action="EliminaRecensione.php" method="post" role="form">
                        <fieldset class="hideFieldset">
                            <legend class="hideLegend">Pulsante di eliminazione della recensione</legend>
                            
                            <input type="hidden" name="id" value="' . $row['ID'] . '"/>
                            
                            <a class="button" href="ModificaRecensione.php?id=' . $row['ID'] . '" title="Modifica dettagli recensione" role="button" aria-label="Modifica dettagli recensione">Modifica</a>
                            <input class="button" name="submit" type="submit" value="Rimuovi" role="button" aria-label="Rimuovi recensione"/>
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

    public function updateReview($id, $title, $description, $user) {
        $message = ReviewsController::checkInput($title, $description);

        if ($message === '') {
            if ($this->reviews->updateReview($id, $title, $description, $user)) {
                $message = '';
            } else {
                $message = '<p class="error">Errore nell\'aggiornamento della recensione</p>';
            }
        } else {
            $message = '<ul>' . $message;
            $message = str_replace('[', '<li class="error">', $message);
            $message = str_replace(']', '</li>', $message);
            $message .= '</ul>';
        }

        return $message;
    }

    public function deleteReview($id) {
        return $this->reviews->deleteReview($id);
    }
}

?>
