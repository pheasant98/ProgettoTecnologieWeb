<?php

    function english_italian_date_format($string_date) {
        $timestamp = strtotime($string_date);
        return date("d-m-Y", $timestamp);
    }

    function italian_english_date_format($string_date) {
        $timestamp = strtotime($string_date);
        return date("Y-m-d", $timestamp);
    }

    function get_authentication_menu($is_authenticated, $is_index = false) {
        $path = "";
        if ($is_index) {
            $path = "PHP/";
        }

        if ($is_authenticated) {
            $login = " . $path . "AreaPersonale.php\" title=\"Area utente\" role=\"button\" aria-label=\"vai alla pagina del profilo utente\" tabindex=\"6\">
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
