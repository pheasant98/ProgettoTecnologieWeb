<?php

# Inclusione del file con la classe per l'interazione con il database
require_once('Database/DatabaseAccess.php');

# Funzione per il controllo degli errori
function checkInput($title, $content) {
    $message = "";

    if (!strlen($title)) {
        $message .= "[Il titolo inserito non può essere vuoto]";
    } elseif (strlen($title) < 3) {
        $message .= "[Il titolo inserito deve essere almeno lungo 3 caratteri]";
    }

    if (strlen($content) == 0) {
        $message .= "[La descrizione inserita non puo' essere vuota]";
    }

    return $message;
}

# Definizione delle variabili per la gestione del form
$title = '';
$content = '';

$message = '';

# Controllo se la pagina e' stata acceduta tramite il bottone di 'submit' del form di inserimento
if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {
    # Recupero dei dati contenuti all'interno dei campi del form di inserimento
    $title = $_POST['title'];
    $content = $_POST['content'];

    # Controllo dell'input
    $message = checkInput($title, $content);

    # Controllo degli errori
    if ($message == "") {
        # Istanziazione oggetto di interacciamento con il database
        $connection = new DatabaseAccess();

        # Apertura connessione al database e inserimento del personaggio nel database
        if ($connection->addPersonaggio($title, $content, $peso, $potenza, $angry_birds, $angry_birds_rio, $angry_birds_star_wars, $angry_birds_space, $descrizione)) {
            # Messaggio di avvenuto inserimento
            $message = "<p class=\"success\">Personaggio correttamente inserito</p>";

            # Pulizia campi del form
            $title = '';
            $content = '';
            $peso = '';
            $descrizione = '';
        } else {
            # Messaggio di errore nell'inserimento
            $message = "<p class=\"error\">Errore nell'inserimento del nuovo personaggio</p>";
        }
    } else {
        # Composizione dei messaggi di errori
        $message = "<ul>" . $message;
        $message = str_replace('[', '<li class="error">', $message);
        $message = str_replace(']', '</li>', $message);
        $message = $message . "</ul>";
    }

}

session_start();

$document = file_get_contents("../HTML/LasciaUnaRecensione.html");
$login = getAuthenticationMenu(isset($_SESSION['username']));

$document = str_replace("<span id=\"loginMenuPlaceholder\"/>", $login, $document);

echo $document;

?>
