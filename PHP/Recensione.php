<?php

session_start();

if (!isset($_GET['id'])) {
    header('Location: Errore.php');
}

require_once ('Utilities/InputCheckUtilities.php');
require_once ('Utilities/DateUtilities.php');
require_once ('Controller/LoginController.php');
$document = file_get_contents('../HTML/Recensione.html');

require_once ('Controller/ReviewsController.php');
$controller = new ReviewsController();

$login = LoginController::getAuthenticationMenu();

$review = $controller->getReview($_GET['id']);

unset($controller);

$review_object = InputCheckUtilities::prepareStringForDisplay($review['Oggetto']);
$review_content = InputCheckUtilities::prepareStringForDisplay($review['Contenuto']);
$review_user = InputCheckUtilities::prepareStringForDisplay($review['Utente']);
$review_last_data = DateUtilities::englishItalianDate($review['DataUltimaModifica']);

$breadcrumbs = '';
if (isset($_SESSION['previous_page'])) {
    if ($_SESSION['previous_page'] === 'GestioneRecensioni') {
        $page = '?page=' . $_SESSION['reviewPage'];
        $breadcrumbs = '<a href="AreaPersonale.php" title="Vai alla pagina dell\'area personale" aria-label="Vai alla pagina dell\'area personale" rel="nofollow">Area personale</a>
                    &gt;&gt; <a href="GestioneRecensioni.php' . $page . '" title="Vai alla pagina di gestione delle recensioni" aria-label="Vai alla pagina di gestione delle recensioni" rel="nofollow">Gestione recensioni</a>';
    } else if ($_SESSION['previous_page'] === 'CosaDiconoDiNoi') {
        $page = '?page=' . $_SESSION['reviews_page'];
        $breadcrumbs = '<a href="CosaDiconoDiNoi.php' . $page . '" title="Vai alla pagina cosa dicono di noi" aria-label="Vai alla pagina cosa dicono di noi">Cosa dicono di noi</a>';
    }
} else {
    $breadcrumbs = '<a href="CosaDiconoDiNoi.php?page=1" title="Vai alla pagina cosa dicono di noi" aria-label="Vai alla pagina cosa dicono di noi">Cosa dicono di noi</a>';
}

$document = str_replace("<span id='objectPlaceholder'/>", $review_object, $document);
$document = str_replace("<span id='breadcrumbsPlaceholder'/>", $breadcrumbs, $document);
$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='contentPlaceholder'/>", $review_content, $document);
$document = str_replace("<span id='userPlaceholder'/>", $review_user, $document);
$document = str_replace("<span id='lastDataPlaceholder'/>", $review_last_data, $document);

echo $document;

?>
