<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ("Controller/LoginController.php");
require_once ("Controller/ArtworksController.php");

session_start();

$artworks_controller = new ArtworksController();
$artworks_count = $artworks_controller->getArtworksCount();
$filter_type = "";
$link_filter_type = "";

if (isset($_GET["filterType"])) {
    $link_filter_type = "&amp;filterType=" . $_GET["filterType"];
    if ($_GET["filterType"] == "Dipinti") {
        $filter_type = "Dipinto";
        $artworks_count = $artworks_controller->getArtworksCountByStyle($filter_type);
    } elseif ($_GET["filterType"] == "Sculture") {
        $filter_type = "Scultura";
        $artworks_count = $artworks_controller->getArtworksCountByStyle($filter_type);
    }
}

if (!isset($_GET["page"])) {
    $page = 1;
} elseif (($_GET["page"] < 1) || (($_GET["page"] - 1) > ($artworks_count / 5))) {
    header('Location: Error.php');
} else {
    $page = $_GET["page"];
}
$offset = ($page - 1) * 5;

$document = file_get_contents("../HTML/Opere.html");
$login = LoginController::getAuthenticationMenu();

$artworks_list = '<dl class="clickableList">' . $artworks_controller->getArtworks($filter_type, $offset) . '</dl>';
$back_artworks = "";
if ($page > 1) {
    $back_artworks = "<a id=\"buttonBack\" class=\"button\" href=\"?page=" . ($page - 1) . $link_filter_type . "\" title=\"Opere precedenti\" role=\"button\" aria-label=\"Torna alle opere precedenti\"> &lt; Precedente</a>";
}

$next_artworks = "";
if (($page * 5) < $artworks_count) {
    $next_artworks = "<a id=\"buttonNext\" class=\"button\" href=\"?page=" . ($page + 1) . $link_filter_type . "\" title=\"Opere successive\" role=\"button\" aria-label=\"Vai alle opere successive\"> Successivo &gt;</a>";
}

$filter_select = '<select name="filterType" id="filterType" aria-label="filtro per lo stile dell\'opera">
                    <option value="TutteLeOpere" ' . ($filter_type == '' ? 'selected="selected"' : '') . '> Tutte le opere </option>
                    <option value="Sculture" ' . ($filter_type == 'Dipinto' ? 'selected="selected"' : '') . '> Sculture </option>
                    <option value="Dipinti" ' . ($filter_type == 'Scultura' ? 'selected="selected"' : '') . '> Dipinti </option>
                  </select>';

$document = str_replace("<span id=\"loginMenuPlaceholder\"/>", $login, $document);
$document = str_replace("<span id='filterSelectPlaceholder'/>", $filter_select, $document);
$document = str_replace("<span id=\"artworksNumberPlaceholder\"/>", $artworks_count, $document);
$document = str_replace("<span id=\"artworkListPlaceholder\"/>", $artworks_list, $document);
$document = str_replace("<span id='buttonBackPlaceholder'/>", $back_artworks, $document);
$document = str_replace("<span id='buttonNextPlaceholder'/>", $next_artworks, $document);

echo $document;

?>
