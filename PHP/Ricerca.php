<?php

if (!isset($_POST['submit']) || $_POST['submit'] !== 'Cerca' || !isset($_POST['filter']) || !isset($_POST['search'])) {
    header('Location: Errore.php');
}

require_once ('Utilities/InputCheckUtilities.php');

if ($_POST['filter'] === 'Opera') {
    header('Location: RicercaOpere.php?search=' . InputCheckUtilities::prepareStringForChecks($_POST['search']) . '&page=1');
} else if ($_POST['filter'] === 'Evento') {
    header('Location: RicercaEventi.php?search=' . InputCheckUtilities::prepareStringForChecks($_POST['search']) . '&page=1');
} else {
    header('Location: Errore.php');
}

?>
