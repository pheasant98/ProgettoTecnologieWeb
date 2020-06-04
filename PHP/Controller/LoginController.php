<?php

class LoginController {
    public static function getAuthenticationMenu($isNotUserArea = true) {
        if (isset($_SESSION['username'])) {
            if($isNotUserArea) {
                $login = '<div id="loginMenu">
                        <ul class="list">
                            <li>
                                <a id="areaPersonale" class="button" href="AreaPersonale.php" title="Area personale" role="button" aria-label="Vai alla pagina del profilo utente">
                                    Area personale
                                </a>
                            </li>
                            <li>
                                <a id="logout" class="button" href="Logout.php" title="Logout" role="button" aria-label="Esci dal tuo profilo" xml:lang="en">
                                    Logout
                                </a>
                            </li>
                        </ul>
                      </div>';
            } else {
                $login = '<div id="loginMenu">
                            <a id="logout" class="button" href="Logout.php" title="Logout" role="button" aria-label="Esci dal tuo profilo" xml:lang="en">
                                Logout
                            </a>
                          </div>';
            }
        } else {
            $login = '<div id="loginMenu">
                        <a id="login" class="button" href="Login.php" title="Login" role="button" aria-label="vai alla pagina di login" xml:lang="en">
                            Login
                        </a>
                      </div>';
        }

        return $login;
    }

    public static function isAuthenticatedUser() {
        return isset($_SESSION['username']);
    }

    public static function isAdminUser() {
        return isset($_SESSION['admin']) && $_SESSION['admin'] === 1;
    }
}

?>
