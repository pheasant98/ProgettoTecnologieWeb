<?php

if (!isset($_POST['submit']) || $_POST['submit'] !== 'Cerca' || !isset($_POST['filter']) || !isset($_POST['search'])) {
    header('Location: Errore.php');
}

if ($_POST['filter'] === 'Opera') {
    header('Location: RicercaOpere.php?search=' . $_POST['search']);
} else if ($_POST['filter'] === 'Evento') {
    header('Location: RicercaEventi.php?search=' . $_POST['search']);
} else {
    header('Location: Errore.php');
}

?>
