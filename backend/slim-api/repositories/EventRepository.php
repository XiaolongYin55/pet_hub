<?php
// repositories/EventRepository.php

class EventRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllEvents() {
        $stmt = $this->pdo->query("SELECT * FROM events");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEventById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE event_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addEvent($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO events (title, description, image, publisher)
            VALUES (:title, :description, :image, :publisher)
        ");
        return $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':image' => $data['image'],
            ':publisher' => $data['publisher']
        ]);
    }

    public function updateEvent($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE events SET
                title = :title,
                description = :description,
                image = :image,
                publisher = :publisher,
                update_time = CURRENT_TIMESTAMP
            WHERE event_id = :id
        ");
        return $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':image' => $data['image'],
            ':publisher' => $data['publisher'],
            ':id' => $id
        ]);
    }

    public function deleteEvent($id) {
        $stmt = $this->pdo->prepare("DELETE FROM events WHERE event_id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
