<?php

    require_once("PHP/DatabaseAccess.php");
    require_once("PHP/Utilities.php");

    function get_last_news() {
        $database_access = new DatabaseAccess();

        $database_access->open_connection();
        $result = $database_access->get_last_two_news();

        if ($result) {
            $news_list = "<dl class=\"clickableDl\">";

            $news_id = '0';
            if (count($result) == 1) {
                $skip_id = "p";
            } else {
                $skip_id = "a";
            }

            foreach ($result as $row) {
                $news_list .= "<dt>
                                    <a id=\"a" . $news_id . "\" href=\"AvvisoSingolo.php?id=" . $row["ID"] . "\">Oggetto: " . $row["Oggetto"] . "</a>
                               </dt>
                               <dd>
                                    <a href=\"#" . $skip_id . "1\" class=\"skip\">Salta l'avviso</a>
                                    <p> Data: " . english_italian_date_format($row["DataTermine"]) . "</p>
                                    <p>" . $row["Descrizione"] . "</p>
                               </dd>";

                $news_id = "1";
                $skip_id = "p";
            }

            $news_list .= "</dl>";
        } else {
            $news_list = "<p id=\"warning\">Non ci sono nuovi avvisi</p>";
        }

        $database_access->close_connection();

        return $news_list;
    }

    session_start();

    $document = file_get_contents("HTML/Index.html");
    $login = get_authentication_menu(isset($_SESSION['username']));
    $news = get_last_news();

    $document = str_replace("<span id=\"loginMenuPlaceholder\"/>", $login, $document);
    $document = str_replace("<span id=\"newsPlaceholder\"/>", $news, $document);

    echo $document;

?>
