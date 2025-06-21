<?php
// repositories/ContractRepository.php

class ContractRepository
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllContracts()
    {
        $stmt = $this->pdo->query("SELECT * FROM contracts");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContractById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM contracts WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getContractByUserId($userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM contracts WHERE owner_id = :user_id ORDER BY create_time DESC LIMIT 1");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addContract($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO contracts (title, owner_id, adopter_id, image, description, adoption_doc, application_doc, status, create_time, update_time)
            VALUES (:title, :owner_id, :adopter_id, :image, :description, :adoption_doc, :application_doc, :status, NOW(), NOW())
        ");

        return $stmt->execute([
            ':title' => $data['title'],
            ':owner_id' => $data['owner_id'],
            ':adopter_id' => $data['adopter_id'],
            ':image' => $data['image'],
            ':description' => $data['description'],
            ':adoption_doc' => $data['adoption_doc'],
            ':application_doc' => $data['application_doc'],
            ':status' => $data['status']
        ]);
    }

    public function updateContract($id, $data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE contracts
            SET title = :title,
                owner_id = :owner_id,
                adopter_id = :adopter_id,
                image = :image,
                description = :description,
                adoption_doc = :adoption_doc,
                application_doc = :application_doc,
                status = :status,
                update_time = NOW()
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':title' => $data['title'],
            ':owner_id' => $data['owner_id'],
            ':adopter_id' => $data['adopter_id'],
            ':image' => $data['image'],
            ':description' => $data['description'],
            ':adoption_doc' => $data['adoption_doc'],
            ':application_doc' => $data['application_doc'],
            ':status' => $data['status']
        ]);
    }

    public function deleteContract($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM contracts WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
