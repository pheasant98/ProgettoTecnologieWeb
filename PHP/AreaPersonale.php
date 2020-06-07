<?php

session_start();

require_once ('Utilities/DateUtilities.php');
require_once ('Controller/LoginController.php');
require_once ('Controller/UsersController.php');

if (!LoginController::isAuthenticatedUser()) {
    header('Location: Errore.php');
}

$welcome = '';
if (isset($_SESSION['just_registered']) && $_SESSION['just_registered'] === true) {
    $welcome = '<p class="success">La registrazione è stata completata correttamente</p>';
}
unset($_SESSION['just_registered']);
$_SESSION['previous_page'] = 'AreaPersonale';

$users_controller = new UsersController();
$user = $users_controller->getUser($_SESSION['username']);
unset($users_controller);

if (LoginController::isAdminUser()) {
    $functionalities = 'Ora che hai effettuato l\'accesso all\'area personale, puoi utilizzare il tuo <span xml:lang="en">account</span> per gestire gli utenti e i contenuti del sito.';
    $operations = '    
        <li>
            <a id="ModificaAdmin" class="button userOperationButton" href="ModificaDatiUtente.php" title="Modifica dati profilo" role="button" aria-label="vai alla pagina di modifica dei dati utente">Modifica profilo</a>
        </li>

        <li>
            <a id="InserisciOpera" class="button userOperationButton" href="InserisciOpera.php" title="Inserisci una nuova opera" role="button" aria-label="vai alla pagina per l\'inserimento di un\'opera">Inserisci opera</a>
        </li>
        
        <li>
            <a id="InserisciEvento" class="button userOperationButton" href="InserisciEvento.php" title="Inserisci un nuovo evento" role="button" aria-label="vai alla pagina per l\'inserimento di un evento">Inserisci evento</a>
        </li>

        <li>
            <a id="GestioneUtenti" class="button userOperationButton" href="GestioneUtenti.php" title="Gestisci gli utenti" role="button" aria-label="vai alla pagina per la gestione degli utenti">Gestione utenti</a>
        </li>

        <li>
            <a id="GestioneContenuti" class="button userOperationButton" href="GestioneContenuti.php" title="Gestisci i contenuti" role="button" aria-label="vai alla pagina per la gestione dei contenuti">Gestione contenuti</a>
        </li>
        
        <li>
            <a href="GestioneRecensioni.php" class="button userOperationButton" title="Gestisci le recensioni" aria-label="vai alla pagina per la gestione delle recensioni">Gestisci recensioni</a>
        </li>
    ';
    $header_description = 'Visualizzazione del proprio profilo dove e possibile modificarlo, e possibile effettuare la gestione delle pagine presenti
    nel sito web e di aggiungerne di nuove, e inoltre possibile gestire gli utenti registrati nel sito web';
} else {
    $functionalities = 'Ora che hai effettuato l\'accesso al sito, puoi utilizzare il tuo <span xml:lang="en">account</span> per lasciare delle recensioni riguardo le visite che hai fatto al museo.';
    $operations = '
        <li>
            <a href="ModificaDatiUtente.php" id="ModificaUser" class="button userOperationButton" title="Modifica dati profilo" aria-label="Vai alla pagina per la modifica dei dati del profilo">Modifica profilo</a>
        </li>
        
        <li>
            <a href="LasciaUnaRecensione.php" class="button userOperationButton" title="Lascia una nuova recensione" aria-label="Vai alla pagina per lasciare una nuova recensione">Lascia una recensione</a>
        </li>
        
        <li>
            <a href="GestioneRecensioni.php" class="button userOperationButton" title="Gestisci le tue recensioni" aria-label="Vai alla pagina per la gestione delle recensioni lasciate">Gestisci le recensioni</a>
        </li>
    ';
    $header_description = 'Visualizzazione del proprio profilo dove e possibile modificarlo e inoltre possibile gestire le proprie recensioni oltre ad effettuare di nuove';
}

$document = file_get_contents('../HTML/AreaPersonale.html');
$login = LoginController::getAuthenticationMenu(false);

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='headerDescriptionPlaceholder'/>", $header_description, $document);
$document = str_replace("<span id='justRegisteredWelcomePlaceholder'/>", $welcome, $document);
$document = str_replace("<span id='userNamePlaceholder'/>", $user['Nome'], $document);
$document = str_replace("<span id='userSurnamePlaceholder'/>", $user['Cognome'], $document);
$document = str_replace("<span id='userSexPlaceholder'/>", $user['Sesso'] === 'M' ? 'Maschile' : ($user['Sesso'] === 'F' ? 'Femminile' : 'Non specificato'), $document);
$document = str_replace("<span id='userBirthDatePlaceholder'/>", DateUtilities::englishItalianDate($user['DataNascita']), $document);
$document = str_replace("<span id='userMailPlaceholder'/>", $user['Email'], $document);
$document = str_replace("<span id='functionalitiesPlaceholder'/>", $functionalities, $document);
$document = str_replace("<span id='operationsPlaceholder'/>", $operations, $document);

echo $document;

?>
