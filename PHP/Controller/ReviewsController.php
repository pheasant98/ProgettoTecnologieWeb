<?php

require_once ('Repository/ReviewsRepository.php');

class ReviewsController {
    # Funzione per il controllo degli errori
    private static function checkInput($title, $content) {
        $message = "";

        if (!strlen($title)) {
            $message .= "[Il titolo inserito non può essere vuoto]";
        } elseif (strlen($title) < 3) {
            $message .= "[Il titolo inserito deve essere lungo almeno 3 caratteri]";
        }

        if (strlen($content) == 0) {
            $message .= "[La descrizione inserita non può essere vuota]";
        } elseif (strlen($content) < 30) {
            $message .= "[La descrizione inserita deve essere lunga almeno 30 caratteri]";
        }

        return $message;
    }

    # Controllo se la pagina e' stata acceduta tramite il bottone di 'submit' del form di inserimento
    public static function addReview($title, $content, $user) {
        # Controllo dell'input
        $message = ReviewsController::checkInput($title, $content);

        # Controllo degli errori
        if ($message == "") {
            # Istanziazione oggetto di interfacciamento con il database
            $reviews = new ReviewsRepository();

            # Apertura connessione al database e inserimento del personaggio nel database
            if ($reviews->postReview($title, $content, $user)) {
                # Messaggio di avvenuto inserimento
                $message = "<p class=\"success\">Recensione inserita correttamente</p>";
            } else {
                # Messaggio di errore nell'inserimento
                $message = "<p class=\"error\">Errore nell'inserimento della nuova recensione</p>";
            }
        } else {
            # Composizione dei messaggi di errori
            $message = "<ul>" . $message;
            $message = str_replace('[', '<li class="error">', $message);
            $message = str_replace(']', '</li>', $message);
            $message = $message . "</ul>";
        }

        return $message;
    }
}

?>

