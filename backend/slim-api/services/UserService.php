<?php
// services/UserService.php
require_once __DIR__ . '/../repositories/UserRepository.php';
class UserService
{
    private $userRepo;

    public function __construct($pdo)
    {
        $this->userRepo = new UserRepository($pdo);
    }

    public function getUsers()
    {
        return $this->userRepo->getAllUsers();
    }

    public function getUserById($id)
    {
        return $this->userRepo->getUserById($id);
    }

    public function updateUserWithAddress($data)
    {
        return $this->userRepo->updateUserWithAddress($data);
    }
    public function addUserWithAddress($data)
    {
        return $this->userRepo->addUserWithAddress($data);
    }

    public function deleteUserById($id)
    {
        return $this->userRepo->deleteUserById($id);
    }
}
