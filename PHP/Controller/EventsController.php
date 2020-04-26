<?php

require_once ('Repository/EventsRepository.php');

class EventsController {
    public static function getEvents() {
        $events = new EventsRepository();

    }
}