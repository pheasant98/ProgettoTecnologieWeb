<?php

require_once ('Repository/ReviewsRepository.php');

class ReviewsController {
    private $reviews;

    private static function checkInput($title, $content) {
        $message = '';

        if (strlen($title) === 0) {
            $message .= '[Il titolo della recensione non può essere vuoto]';
        } elseif (strlen($title) < 3) {
            $message .= '[Il titolo della recensione deve essere lungo almeno 3 caratteri]';
        }

        if (strlen($content) === 0) {
            $message .= '[Il contenuto della recensione non può essere vuoto]';
        } elseif (strlen($content) < 30) {
            $message .= '[Il contenuto della recensione deve essere lungo almeno 30 caratteri]';
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
                        <dd>
                            ' . $row['Utente'] . '
                        </dd>
                        
                        <dt class="reviewData">
                            Data: 
                        </dt>
                        <dd>
                            ' . $row['DataPubblicazione'] . '
                        </dd>
    
                        <dt class="reviewDescription">
                            Descrizione: 
                        </dt>
                        <dd>
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
                            <input id="buttonConfirm" class="button" name="submit" type="submit" value="Rimuovi" role="button" aria-label="Rimuovi recensione"/>
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
                            <input id="buttonConfirm" class="button" name="submit" type="submit" value="Rimuovi" role="button" aria-label="Rimuovi recensione"/>
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

    public function deleteReview($id) {
        $this->reviews->deleteReview($id);
    }
}

?>
