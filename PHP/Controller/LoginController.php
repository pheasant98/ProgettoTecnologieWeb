<?php

class LoginController {
    public static function getAuthenticationMenu($is_authenticated) {
        if ($is_authenticated) {
            $login = "<div id=\"loginMenu\">
                        <ul>
                            <li>
                                <a id=\"areaPersonale\" class=\"button\" href=\"AreaPersonale.php\" title=\"Area personale\" role=\"button\" aria-label=\"Vai alla pagina del profilo utente\" tabindex=\"6\">
                                    Area personale
                                </a>
                            </li>
                            <li>
                                <a id=\"logout\" class=\"button\" href=\"Logout.php\" title=\"Logout\" role=\"button\" aria-label=\"Esci dal tuo profilo\" tabindex=\"7\" xml:lang=\"en\">
                                    Logout
                                </a>
                            </li>
                        </ul>
                      </div>";
        } else {
            $login = "<div id=\"loginMenu\">
                        <a id=\"login\" class=\"button\" href=\"Login.php\" title=\"Login\" role=\"button\" aria-label=\"vai alla pagina di login\" tabindex=\"4\" xml:lang=\"en\">
                            Login
                        </a>
                      </div>";
        }

        return $login;
    }
}

?>

