<?php

class InputCheckUtilities {
    public static function englishWordsReplace($string) {
        $string = str_replace('[en]', '<span xml:lang="en">', $string);
        return str_replace('[/en]', '</span>', $string);
    }
}