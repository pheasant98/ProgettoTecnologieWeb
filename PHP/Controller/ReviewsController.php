<?php

require_once ('Repository/ReviewsRepository.php');

class ReviewsController {
    private $reviews;

    public function __construct() {
        $this->reviews = new ReviewsRepository();
    }

    public function __destruct() {
        unset($this->reviews);
    }

    private function checkInput($title, $content) {
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

    public function addReview($title, $content, $user) {
        $message = $this->checkInput($title, $content);

        if ($message === '') {
            if ($this->reviews->postReview($title, $content, $user)) {
                $message = '<p class="success">Recensione inserita correttamente</p>';
            } else {
                $message = '<p class="error">Errore nell\'inserimento della nuova recensione</p>';
            }
        } else {
            # Composizione dei messaggi di errori
            $message = '<ul>' . $message;
            $message = str_replace('[', '<li class="error">', $message);
            $message = str_replace(']', '</li>', $message);
            $message = $message . '</ul>';
        }

        return $message;
    }
}

?>
