<?php

require_once ('Controller/LoginController.php');

session_start();

if (!LoginController::isAuthenticatedUser() || !LoginController::isAdminUser() || !isset($_SESSION['event_title']) || !isset($_SESSION['event_id'])) {
    header('Location: Errore.php');
}

$document = file_get_contents('../HTML/EventoModificato.html');
$login = LoginController::getAuthenticationMenu();


$breadcrumbs_content_management = '';
if (isset($_SESSION['contentPage'])) {
    $breadcrumbs_content_management .= '?page=' . $_SESSION['contentPage'];
    if (isset($_SESSION['filter_content'])) {
        $breadcrumbs_content_management .= '&filterContent='  . $_SESSION['filter_content'];
        if (isset($_SESSION['filter_content_type'])) {
            $breadcrumbs_content_management .= '&filterContentType='  . $_SESSION['filter_content_type'];
        } else {
            $breadcrumbs_content_management .= '&filterContentType=NessunFiltro';
        }
    } else {
        $breadcrumbs_content_management .= '&filterContent=NessunFiltro';
    }
} else {
    $breadcrumbs_content_management .= '?page=1';
}

$breadcrumbs_event_modify_id = '?id=' . $_SESSION['event_id'];

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='listBreadcrumbsPlaceholder'/>", $breadcrumbs_content_management, $document);
$document = str_replace("<span id='modifyBreadcrumbsPlaceholder'/>", $breadcrumbs_event_modify_id, $document);
$document = str_replace("<span id='eventTitlePlaceholder'/>", $_SESSION['event_title'], $document);

echo $document;

?>
