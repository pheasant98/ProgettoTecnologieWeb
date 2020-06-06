<?php

session_start();

if (!isset($_GET['id'])) {
    header('Location: Errore.php');
}

require_once ('Controller/LoginController.php');
require_once ('Utilities/DateUtilities.php');
$document = file_get_contents('../HTML/Evento.html');

require_once ('Controller/EventsController.php');
$controller = new EventsController();

$login = LoginController::getAuthenticationMenu();

$event = $controller->getEvent($_GET['id']);

unset($controller);

$event_title = $event['Titolo'];
$event_type = $event['Tipologia'];
$event_begin_date = DateUtilities::englishItalianDate($event['DataInizio']);
$event_end_date = DateUtilities::englishItalianDate($event['DataFine']);
$event_manager = $event['Organizzatore'];
$event_description = $event['Descrizione'];
$breadcrumbs = '';
if ($_SESSION['previous_page'] === 'GestioneContenuti') {
    $page = 'page=' . $_SESSION['contentPage'];
    $filter_content = 'filterContent=' . $_SESSION['filter_content'];
    $filter_content_type = 'filterContentType=' . $_SESSION['filter_content_type'];
    $breadcrumbs = '<a href="AreaPersonale.php" title="Area Personale" aria-label="Vai alla pagina dell\'area personale">Area personale</a>
                    &gt;&gt;<a href="GestioneContenuti.php?' . $page . '&amp;' . $filter_content . '&amp;' . $filter_content_type . '" title="Gestione contenuti" aria-label="Vai alla pagina di gestione dei contenuti">Gestione contenuti</a>';
} else if ($_SESSION['previous_page'] === 'Eventi') {
    $page = 'page=' . $_SESSION['event_page'];
    $filter_event_type = 'filterType=' . $_SESSION['filter_event_type'];
    $breadcrumbs = '<a href="Eventi.php?' . $page . '&amp;' . $filter_event_type . '" title="Eventi" aria-label="Vai alla pagina Eventi">Eventi</a>';
}

$document = str_replace("<span id='titlePlaceholder'/>", $event_title, $document);
$document = str_replace("<span id='breadcrumbsPlaceholder'/>", $breadcrumbs, $document);
$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='eventTypePlaceholder'/>", $event_type, $document);
$document = str_replace("<span id='eventBeginDatePlaceholder'/>", $event_begin_date, $document);
$document = str_replace("<span id='eventEndDatePlaceholder'/>", $event_end_date, $document);
$document = str_replace("<span id='eventManagerPlaceholder'/>", $event_manager, $document);
$document = str_replace("<span id='eventDescriptionPlaceholder'/>", $event_description, $document);

echo $document;

?>
