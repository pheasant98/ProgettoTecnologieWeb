<?php

session_start();

if (!isset($_GET['id'])) {
    header('Location: Errore.php');
}

require_once ('Utilities/InputCheckUtilities.php');

require_once ('Controller/LoginController.php');
$document = file_get_contents('../HTML/Opera.html');

require_once ('Controller/ArtworksController.php');
$controller = new ArtworksController();

$login = LoginController::getAuthenticationMenu();

$artwork = $controller->getArtwork($_GET['id']);

unset($controller);

$artwork_title = InputCheckUtilities::prepareStringForDisplay($artwork['Titolo']);
$artwork_img = InputCheckUtilities::prepareStringForDisplay($artwork['Immagine']);
$artwork_author = InputCheckUtilities::prepareStringForDisplay($artwork['Autore']);
$artwork_years = InputCheckUtilities::prepareStringForDisplay($artwork['Datazione']);
$artwork_style = InputCheckUtilities::prepareStringForDisplay($artwork['Stile']);
$artwork_technique = InputCheckUtilities::prepareStringForDisplay($artwork['Tecnica']);
$artwork_material = InputCheckUtilities::prepareStringForDisplay($artwork['Materiale']);
$artwork_dimension = InputCheckUtilities::prepareStringForDisplay($artwork['Dimensioni']);
$artwork_loan = ($artwork['Prestito'] == 1 ? 'Si' : 'No');
$artwork_description = InputCheckUtilities::prepareStringForDisplay($artwork['Descrizione']);

$breadcrumbs = '';
$searchText = '';
if (isset($_SESSION['previous_page'])) {
    if ($_SESSION['previous_page'] === 'GestioneContenuti') {
        $page = 'page=' . $_SESSION['contentPage'];
        $filter_content = 'filterContent=' . $_SESSION['filter_content'];
        $filter_content_type = 'filterContentType=' . $_SESSION['filter_content_type'];
        $breadcrumbs = '<a href="AreaPersonale.php" title="Vai alla pagina dell\'area personale" aria-label="Vai alla pagina dell\'area personale">Area personale</a>
                    &gt;&gt; <a href="GestioneContenuti.php?' . $page . '&amp;' . $filter_content . '&amp;' . $filter_content_type . '" title="Vai alla pagina di gestione dei contenuti" aria-label="Vai alla pagina di gestione dei contenuti">Gestione contenuti</a>';
    } else if ($_SESSION['previous_page'] === 'Opere') {
        $page = 'page=' . $_SESSION['artwork_page'];
        $filter_artwork_type = 'filterType=' . $_SESSION['filter_artwork_type'];
        $breadcrumbs = '<a href="Opere.php?' . $page . '&amp;' . $filter_artwork_type . '" title="Vai alla pagina Opere" aria-label="Vai alla pagina Opere">Opere</a>';
    } elseif ($_SESSION['previous_page'] === 'RicercaOpere') {
        $searchText = $_SESSION['search_artwork_string'];
        $search = 'search=' . $_SESSION['search_artwork_string'];
        $page = 'page=' . $_SESSION['search_artwork_page'];
        $breadcrumbs = '<a href="RicercaOpere.php?' . $search . '&amp;' . $page . '" title="Vai alla pagina di ricerca delle opere" aria-label="Vai alla pagina di ricerca delle opere">Ricerca Opere</a>';
    }
} else {
    $breadcrumbs = '<a href="Opere.php?page=1&amp;filterType=NessunFiltro" title="Vai alla pagina Opere" aria-label="Vai alla pagina Opere">Opere</a>';
}

if ($artwork_style === 'Dipinto') {
    $artwork_technique = '<dt class="inlineDef">
                              Tecnica:
                          </dt>
                          <dd class="definitionOpera">
                              ' . $artwork_technique . '
                          </dd>';
    $artwork_material = '';
} elseif ($artwork_style === 'Scultura') {
    $artwork_technique = '';
    $artwork_material = '<dt class="inlineDef">
                            Materiale:
                        </dt>
                        <dd class="definitionOpera">
                            ' . $artwork_material . '
                        </dd>';
}

$document = str_replace("<span id='titlePlaceholder'/>", $artwork_title, $document);
$document = str_replace("<span id='searchTextPlaceholder'/>", $searchText, $document);
$document = str_replace("<span id='breadcrumbsPlaceholder'/>", $breadcrumbs, $document);
$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='artworkImgPlaceholder'/>", $artwork_img, $document);
$document = str_replace("<span id='artworkAuthorPlaceholder'/>", $artwork_author, $document);
$document = str_replace("<span id='artworkAgePlaceholder'/>", $artwork_years, $document);
$document = str_replace("<span id='artworkStylePlaceholder'/>", $artwork_style, $document);
$document = str_replace("<span id='artworkTechniquePlaceholder'/>", $artwork_technique, $document);
$document = str_replace("<span id='artworkMaterialPlaceholder'/>", $artwork_material, $document);
$document = str_replace("<span id='artworkDimensionPlaceholder'/>", $artwork_dimension, $document);
$document = str_replace("<span id='artworkLoanPlaceholder'/>", $artwork_loan, $document);
$document = str_replace("<span id='artworkDescriptionPlaceholder'/>", $artwork_description, $document);

echo $document;

?>
