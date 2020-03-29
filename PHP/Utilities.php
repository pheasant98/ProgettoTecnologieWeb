<?php
    function englishToItalianDateFormat($stringDate) {
        $timestamp = strtotime($stringDate);
        return date("d-m-Y", $timestamp);
    }

    function italianToEnglishDateFormat($stringDate) {
        $timestamp = strtotime($stringDate);
        return date("Y-m-d", $timestamp);
    }

    function getAuthenticationMenu($isAuthenticated, $isIndex = false) {
        $path = "";
        if ($isIndex) {
            $path = "PHP/";
        }

        if ($isAuthenticated) {
            $login = "<div id=\"UserTools\" class=\"ImgBackground\" title=\"area dedicata alla registrazione, login e logout\" aria-label=\"area dedicata alla registrazione, login e logout\" tabindex=\"5\">
                        <ul id=\"UserMenu\">
                            <li>
                                <a id=\"profile\" class=\"Button\" href=\"" . $path . "AreaPersonale.php\" title=\"Area utente\" role=\"button\" aria-label=\"vai alla pagina del profilo utente\" tabindex=\"6\">
                                    Area utente
                                </a>
                            </li>
                            <li>
                                <a id=\"logOut\" class=\"Button\" href=\"" . $path . "Logout.php\" title=\"LogOut\" role=\"button\" aria-label=\"esci dal tuo profilo\" tabindex=\"7\" xml:lang=\"en\">
                                    LogOut
                                </a>
                            </li>
                        </ul>
                      </div>";
        } else {
            $login = "<div id=\"LoginMenu\">
                        <a id=\"logIn\" class=\"Button\" href=\"" . $path . "Login.php\" title=\"LogIn\" role=\"button\" aria-label=\"vai alla pagina di login\" tabindex=\"4\" xml:lang=\"en\">
                            LogIn
                        </a>
                      </div>";
        }

        return $login;
    }
?>
