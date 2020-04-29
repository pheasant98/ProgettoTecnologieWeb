<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ("Controller/LoginController.php");
require_once ("Controller/ArtworksController.php");

session_start();

$artworks_controller = new ArtworksController();
$artworks_count = $artworks_controller->getArtworksCount();
$filterType = "";

if (isset($_GET["filterType"])) {
    if ($_GET["filterType"] == "Dipinti") {
        $filterType ="Dipinto";
        $artworks_count = $artworks_controller->getArtworksCountByStyle($filterType);
    } elseif ($_GET["filterType"] == "Sculture") {
        $filterType ="Scultura";
        $artworks_count = $artworks_controller->getArtworksCountByStyle($filterType);
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
$artworks_list = "<dl class=\"clickableList\">" . $artworks_controller->getArtworks($filterType, $offset) . "</dl>";

$back_artworks = "";
if ($page > 1) {
    $back_artworks = "<a id=\"buttonBack\" class=\"button\" href=\"?page=" . ($page - 1) . "&amp;filterType=" . $_GET["filterType"] . "\" title=\"Opere precedenti\" role=\"button\" aria-label=\"Torna alle opere precedenti\"> &lt; Precedente</a>";
}

$next_artworks = "";
if (($page * 5) < $artworks_count) {
    $page_next_artworks = "?page=" . ($page + 1);
    $next_artworks = "<a id=\"buttonNext\" class=\"button\" href=\"?page=" . ($page + 1) . "&amp;filterType=" . $_GET["filterType"] . "\" title=\"Opere successive\" role=\"button\" aria-label=\"Vai alle opere successive\"> Successivo &gt;</a>";
}


$document = str_replace("<span id=\"loginMenuPlaceholder\"/>", $login, $document);
$document = str_replace("<span id=\"artworksNumberPlaceholder\"/>", $artworks_count, $document);
$document = str_replace("<span id=\"artworkListPlaceholder\"/>", $artworks_list, $document);
$document = str_replace("<span id='buttonBackPlaceholder'/>", $back_artworks, $document);
$document = str_replace("<span id='buttonNextPlaceholder'/>", $next_artworks, $document);

echo $document;

?>
