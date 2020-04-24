<?php

    function englishItalianDate($string_date) {
        $timestamp = strtotime($string_date);
        return date("d-m-Y", $timestamp);
    }

    function italianEnglishDate($string_date) {
        $timestamp = strtotime($string_date);
        return date("Y-m-d", $timestamp);
    }

    function getAuthenticationMenu($is_authenticated) {
        if ($is_authenticated) {
            $login = "<div id=\"LoginMenu\">
                        <ul>
                            <li>
                                <a id=\"areaPersonale\" class=\"Button\" href=\"AreaPersonale.php\" title=\"Area personale\" role=\"button\" aria-label=\"Vai alla pagina del profilo utente\" tabindex=\"6\">
                                    Area personale
                                </a>
                            </li>
                            <li>
                                <a id=\"logout\" class=\"Button\" href=\"Logout.php\" title=\"Logout\" role=\"button\" aria-label=\"Esci dal tuo profilo\" tabindex=\"7\" xml:lang=\"en\">
                                    Logout
                                </a>
                            </li>
                        </ul>
                      </div>";
        } else {
            $login = "<div id=\"LoginMenu\">
                        <a id=\"login\" class=\"Button\" href=\"Login.php\" title=\"Login\" role=\"button\" aria-label=\"vai alla pagina di login\" tabindex=\"4\" xml:lang=\"en\">
                            Login
                        </a>
                      </div>";
        }

        return $login;
    }

?>
