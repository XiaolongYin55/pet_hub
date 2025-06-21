<?php
// services/EventService.php

require_once __DIR__ . '/../repositories/EventRepository.php';

class EventService {
    private $eventRepo;

    public function __construct($pdo) {
        $this->eventRepo = new EventRepository($pdo);
    }

    public function getEvents() {
        return $this->eventRepo->getAllEvents();
    }

    public function getEventById($id) {
        return $this->eventRepo->getEventById($id);
    }

        public function addEvent($data) {
        return $this->eventRepo->addEvent($data);
    }

    public function updateEvent($id, $data) {
        return $this->eventRepo->updateEvent($id, $data);
    }

    public function deleteEvent($id) {
        return $this->eventRepo->deleteEvent($id);
    }
}

