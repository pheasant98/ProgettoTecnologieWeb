<?php

class DateUtilities {
    public static function englishItalianDate($string_date) {
        $timestamp = strtotime($string_date);
        return date('d-m-Y', $timestamp);
    }

    public static function italianEnglishDate($string_date) {
        $timestamp = strtotime($string_date);
        return date('Y-m-d', $timestamp);
    }
}

?>
