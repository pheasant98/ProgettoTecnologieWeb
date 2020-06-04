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
    $welcome = '<p>La registrazione è stata completata correttamente</p>';
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
            <a id="ModificaAdmin" class="button userOperationButton" href="ModificaDatiUtente.php" title="vai alla pagina modifica dati utente" role="button" aria-label="vai alla pagina modifica dati utente">Modifica profilo</a>
        </li>

        <li>
            <a id="InserisciOpera" class="button userOperationButton" href="InserisciOpera.php" title="vai alla pagina inserisci opera" role="button" aria-label="vai alla pagina inserisci opera">Inserisci opera</a>
        </li>
        
        <li>
            <a id="InserisciEvento" class="button userOperationButton" href="InserisciEvento.php" title="vai alla pagina inserisci evento" role="button" aria-label="vai alla pagina inserisci evento">Inserisci evento</a>
        </li>

        <li>
            <a id="GestioneUtenti" class="button userOperationButton" href="GestioneUtenti.php" title="vai alla pagina Gestione Utenti" role="button" aria-label="vai alla pagina iGestione Utenti">Gestione utenti</a>
        </li>

        <li>
            <a id="GestioneContenuti" class="button userOperationButton" href="GestioneContenuti.php" title="vai alla pagina Gestione Contenuti" role="button" aria-label="vai alla pagina Gestione Contenuti">Gestione Contenuti</a>
        </li>
    ';
} else {
    $functionalities = 'Ora che hai effettuato l\'accesso al sito, puoi utilizzare il tuo <span xml:lang="en">account</span> per lasciare delle recensioni riguardo le visite che hai fatto al museo.';
    $operations = '
        <li>
            <a href="ModificaDatiUtente.php" id="ModificaUser" class="button userOperationButton" title="Modifica dati profilo" aria-label="Vai alla pagina per la modifica dei dati del profilo">Modifica profilo</a>
        </li>

        <li>
            <a href="GestioneRecensioni.php" class="button userOperationButton" title="Gestisci le tue recensioni" aria-label="Vai alla pagina per la gestione delle recensioni lasciate">Gestisci le recensioni</a>
        </li>

        <li>
            <a href="LasciaUnaRecensione.php" class="button userOperationButton" title="Lascia una nuova recensione" aria-label="Vai alla pagina per lasciare una nuova recensione">Lascia una recensione</a>
        </li>
    ';
}

$document = file_get_contents('../HTML/AreaPersonale.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
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
