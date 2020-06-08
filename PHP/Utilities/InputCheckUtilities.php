<?php

class InputCheckUtilities {
    private static function englishWordsReplace($string) {
        $string = str_replace('[en]', '<span xml:lang="en">', $string);
        $string = str_replace('[/en]', '</span>', $string);
        return $string;
    }

    private static function frenchWordsReplace($string) {
        $string = str_replace('[fr]', '<span xml:lang="fr">', $string);
        $string = str_replace('[/fr]', '</span>', $string);
        return $string;
    }

    private static function deutschWordsReplace($string) {
        $string = str_replace('[de]', '<span xml:lang="de">', $string);
        $string = str_replace('[/de]', '</span>', $string);
        return $string;
    }

    private static function spanishWordsReplace($string) {
        $string = str_replace('[es]', '<span xml:lang="es">', $string);
        $string = str_replace('[/es]', '</span>', $string);
        return $string;
    }

    private static function foreignWordsReplace($string) {
        $string = self::englishWordsReplace($string);
        $string = self::frenchWordsReplace($string);
        $string = self::deutschWordsReplace($string);
        $string = self::spanishWordsReplace($string);
        return $string;
    }

    public static function prepareStringForChecks($string) {
        $string = trim($string);
        $string = strip_tags($string);
        $string = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $string);
        return $string;
    }

    public static function prepareStringForDisplay($string) {
        $string = htmlentities($string);
        $string = self::foreignWordsReplace($string);
        return $string;
    }
}

?>
