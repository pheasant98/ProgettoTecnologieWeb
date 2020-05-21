<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once ('Utilities/DateUtilities.php');
require_once ('Controller/LoginController.php');
require_once ('Controller/UsersController.php');

if (!LoginController::isAuthenticatedUser()) {
    header('Location: Error.php');
}

$users_controller = new UsersController();
$user = $users_controller->getUser($_SESSION['Username']);

if ($user['Admin'] === 1) {
    $functionalities = 'Ora che hai effettuato l\'accesso all\'area personale, puoi utilizzare il tuo <span xml:lang="en">account</span> per gestire gli utenti e i contenuti del sito.';
    $operations = '    
        <li>
            <a id="ModificaAdmin" class="button userOperationButton" href="ModificaDatiUtente.html" title="vai alla pagina modifica dati utente" role="button" aria-label="vai alla pagina modifica dati utente">Modifica profilo</a>
        </li>

        <li>
            <a id="Inserisci" class="button userOperationButton" href="Inserisci.html" title="vai alla pagina inserisci pagina" role="button" aria-label="vai alla pagina inserisci pagina">Inserisci pagina</a>
        </li>

        <li>
            <a id="GestioneUtenti" class="button userOperationButton" href="GestioneUtenti.html" title="vai alla pagina Gestione Utenti" role="button" aria-label="vai alla pagina iGestione Utenti">Gestione utenti</a>
        </li>

        <li>
            <a id="ContenutoSito" class="button userOperationButton" href="GestioneContenuti.html" title="vai alla pagina Contenuto Sito" role="button" aria-label="vai alla pagina Contenuto Sito">Contenuto sito</a>
        </li>
    ';
} else {
    $functionalities = 'Ora che hai effettuato l\'accesso al sito, puoi utilizzare il tuo <span xml:lang="en">account</span> per lasciare delle recensioni riguardo le visite che hai fatto al museo.';
    $operations = '
        <li>
            <a href="ModificaDatiUtente.html" id="ModificaUser" class="button userOperationButton" title="Modifica dati profilo" aria-label="Vai alla pagina per la modifica dei dati del profilo">Modifica profilo</a>
        </li>

        <li>
            <a href="RecensioniUtente.html" class="button userOperationButton" title="Gestisci le tue recensioni" aria-label="Vai alla pagina per la gestione delle recensioni lasciate">Gestisci le recensioni</a>
        </li>

        <li>
            <a href="LasciaUnaRecensione.html" class="button userOperationButton" title="Lascia una nuova recensione" aria-label="Vai alla pagina per lasciare una nuova recensione">Lascia una recensione</a>
        </li>
    ';
}

$document = file_get_contents('../HTML/AreaPersonale.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='namePlaceholder'/>", $user['Nome'], $document);
$document = str_replace("<span id='userNamePlaceholder'/>", $user['Nome'], $document);
$document = str_replace("<span id='userSurnamePlaceholder'/>", $user['Cognome'], $document);
$document = str_replace("<span id='userSexPlaceholder'/>", $user['Sesso'] === 'M' ? 'Maschile' : ($user['Sesso'] === 'F' ? 'Femminile' : 'Non specificato'), $document);
$document = str_replace("<span id='userBirthDatePlaceholder'/>", DateUtilities::englishItalianDate($user['DataNascita']), $document);
$document = str_replace("<span id='userMailPlaceholder'/>", $user['Email'], $document);
$document = str_replace("<span id='functionalitiesPlaceholder'/>", $functionalities, $document);
$document = str_replace("<span id='operationsPlaceholder'/>", $operations, $document);

echo $document;

?>
