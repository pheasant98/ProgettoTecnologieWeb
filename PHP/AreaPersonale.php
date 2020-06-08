<?php

session_start();

require_once ('Utilities/DateUtilities.php');
require_once ('Utilities/InputCheckUtilities.php');
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
            <a id="ModificaAdmin" class="button userOperationButton" href="ModificaDatiUtente.php" title="Vai alla pagina di modifica dei dati utente" role="button" aria-label="Vai alla pagina di modifica dei dati utente" rel="nofollow">Modifica profilo</a>
        </li>

        <li>
            <a id="InserisciOpera" class="button userOperationButton" href="InserisciOpera.php" title="Vai alla pagina per l\'inserimento di un\'opera" role="button" aria-label="Vai alla pagina per l\'inserimento di un\'opera" rel="nofollow">Inserisci opera</a>
        </li>
        
        <li>
            <a id="InserisciEvento" class="button userOperationButton" href="InserisciEvento.php" title="Vai alla pagina per l\'inserimento di un evento" role="button" aria-label="Vai alla pagina per l\'inserimento di un evento" rel="nofollow">Inserisci evento</a>
        </li>

        <li>
            <a id="GestioneUtenti" class="button userOperationButton" href="GestioneUtenti.php" title="Vai alla pagina per la gestione degli utenti" role="button" aria-label="Vai alla pagina per la gestione degli utenti" rel="nofollow">Gestione utenti</a>
        </li>

        <li>
            <a id="GestioneContenuti" class="button userOperationButton" href="GestioneContenuti.php" title="Vai alla pagina per la gestione dei contenuti" role="button" aria-label="Vai alla pagina per la gestione dei contenuti" rel="nofollow">Gestione contenuti</a>
        </li>
        
        <li>
            <a href="GestioneRecensioni.php" class="button userOperationButton" title="Vai alla pagina per la gestione delle recensioni" aria-label="Vai alla pagina per la gestione delle recensioni" rel="nofollow">Gestisci recensioni</a>
        </li>
    ';
    $header_description = 'Visualizzazione del proprio profilo e delle opzioni di modifica dei dati personali, gestione delle pagine presenti
    nel sito web e aggiunta di nuove pagine, e gestione degli utenti registrati nel sito web';
} else {
    $functionalities = 'Ora che hai effettuato l\'accesso al sito, puoi utilizzare il tuo <span xml:lang="en">account</span> per lasciare delle recensioni riguardo le visite che hai fatto al museo.';
    $operations = '
        <li>
            <a href="ModificaDatiUtente.php" id="ModificaUser" class="button userOperationButton" title="Vai alla pagina per la modifica dei dati del profilo" aria-label="Vai alla pagina per la modifica dei dati del profilo" rel="nofollow">Modifica profilo</a>
        </li>
        
        <li>
            <a href="LasciaUnaRecensione.php" class="button userOperationButton" title="Vai alla pagina per lasciare una nuova recensione" aria-label="Vai alla pagina per lasciare una nuova recensione" rel="nofollow">Lascia una recensione</a>
        </li>
        
        <li>
            <a href="GestioneRecensioni.php" class="button userOperationButton" title="Vai alla pagina per la gestione delle recensioni lasciate" aria-label="Vai alla pagina per la gestione delle recensioni lasciate" rel="nofollow">Gestisci le recensioni</a>
        </li>
    ';
    $header_description = 'Visualizzazione del proprio profilo e delle opzioni di modifica dei dati personali, gestione delle proprie recensioni presenti
    nel sito web e aggiunta di nuove recensioni';
}

$document = file_get_contents('../HTML/AreaPersonale.html');
$login = LoginController::getAuthenticationMenu(false);

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='headerDescriptionPlaceholder'/>", $header_description, $document);
$document = str_replace("<span id='justRegisteredWelcomePlaceholder'/>", $welcome, $document);
$document = str_replace("<span id='userNamePlaceholder'/>", InputCheckUtilities::prepareStringForDisplay($user['Nome']), $document);
$document = str_replace("<span id='userSurnamePlaceholder'/>", InputCheckUtilities::prepareStringForDisplay($user['Cognome']), $document);
$document = str_replace("<span id='userSexPlaceholder'/>", $user['Sesso'] === 'M' ? 'Maschile' : ($user['Sesso'] === 'F' ? 'Femminile' : 'Non specificato'), $document);
$document = str_replace("<span id='userBirthDatePlaceholder'/>", DateUtilities::englishItalianDate($user['DataNascita']), $document);
$document = str_replace("<span id='userMailPlaceholder'/>", InputCheckUtilities::prepareStringForDisplay($user['Email']), $document);
$document = str_replace("<span id='functionalitiesPlaceholder'/>", $functionalities, $document);
$document = str_replace("<span id='operationsPlaceholder'/>", $operations, $document);

echo $document;

?>
