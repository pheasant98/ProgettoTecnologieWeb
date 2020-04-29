<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ("Controller/LoginController.php");
require_once ("Controller/ArtworksController.php");

session_start();

$artworks_controller = new ArtworksController();
$artworks_counter = $artworks_controller->getArtworksCount();

if (!isset($_GET["page"])) {
    header('Location: Opere.php?page=1');
} elseif (($_GET["page"] < 1) || (($_GET["page"] - 1) > ($artworks_counter["Totale"] / 5))) {
    header('Location: Error.php');
}

$document = file_get_contents("../HTML/Opere.html");
$login = LoginController::getAuthenticationMenu();

$offset = ($_GET["page"] - 1) * 5;
$artwork_list = "<dl class=\"clickableList\">" . $artworks_controller->getArtworks($offset) . "</dl>";
$back_artworks = "";
if ($_GET["page"] > 1) {
    $back_artworks = "Opere.php?page=" . ($_GET["page"] - 1);
}
$next_artworks = "Opere.php?page=" . ($_GET["page"] + 1);
if (($_GET["page"] * 5) >= $artworks_counter) {
    $next_artworks = "";
}

$document = str_replace("<span id=\"loginMenuPlaceholder\"/>", $login, $document);
$document = str_replace("<span id=\"artworksNumberPlaceholder\"/>", $artworks_counter["Totale"], $document);
$document = str_replace("<span id=\"artworkListPlaceholder\"/>", $artwork_list, $document);
$document = str_replace("<span id='buttonBackPlaceholder'/>", $back_artworks, $document);
$document = str_replace("<span id='buttonNextPlaceholder'/>", $next_artworks, $document);

echo $document;

?>