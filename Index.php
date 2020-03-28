<?php
    require_once("PHP/DatabaseAccess.php");
    require_once("PHP/Utilities.php");

    function getLastNews() {
        $databaseAccess = new DatabaseAccess();

        $databaseAccess->openConnection();
        $result = $databaseAccess->getLastTwoNews();

        if ($result) {
            $newsList = "<dl class=\"clickableDl\">";

            $newsID = '0';
            if (count($result) == 1) {
                $skipID = "p";
            } else {
                $skipID = "a";
            }

            foreach ($result as $row) {
                $newsList .= "  <dt>
                                    <a id=\"a" . $newsID . "\" href=\"AvvisoSingolo.php?id=" . $row["ID"] . "\">Oggetto: " . $row["Oggetto"] . "</a>
                                </dt>
                                <dd>
                                    <a href=\"#" . $skipID . "\'' . englishToItalianDateFormat($row["DataTermine"]) . "</p>
                                    <p>" . $row["Descrizione"] . "</p>
                                </dd>";

                $newsID = "1";
                $skipID = "p";
            }

            $newsList .= "</dl>";
        } else {
            $newsList = "<p id=\"warning\">Non ci sono nuovi avvisi</p>";
        }

        $databaseAccess->closeConnection();

        return $newsList;
    }

    session_start();

    $document = file_get_contents("HTML/Index.html");
    $login = getAuthenticationMenu(isset($_SESSION['username']));
    $news = getLastNews();

    $document = str_replace("<span id=\"loginMenuPlaceholder\"/>", $login, $document);
    $document = str_replace("<span id=\"newsPlaceholder\"/>", $news, $document);

    echo $document;
?>