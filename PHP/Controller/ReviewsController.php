<?php

require_once ('Repository/ReviewsRepository.php');

class ReviewsController {
    private $reviews;

    private static function checkInput($title, $content) {
        $message = '';

        if (!strlen($title)) {
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
                $message = '<p class="error">Errore nell\'inserimento della nuova recensione</p>';
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
    
                    <p class="reviewAuthor">
                        Utente: ' . $row['Utente'] . '
                    </p>
                    
                    <p class="reviewData">
                        Data: ' . $row['DataPubblicazione'] . '
                    </p>

                    <p class="reviewDescription">
                        ' . $row['Contenuto'] . '
                    </p>
                </dd>
            ';

            $counter++;
        }

        $result_set->free();

        return $content;
    }
}

?>
