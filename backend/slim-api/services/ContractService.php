<?php
// services/ContractService.php

require_once __DIR__ . '/../repositories/ContractRepository.php';

class ContractService
{
    private $contractRepo;

    public function __construct($pdo)
    {
        $this->contractRepo = new ContractRepository($pdo);
    }

    public function getContracts()
    {
        return $this->contractRepo->getAllContracts();
    }

    public function getContractById($id)
    {
        return $this->contractRepo->getContractById($id);
    }
public function getContractsByUserId($userId)
{
    return $this->contractRepo->getContractsByUserId($userId);
}

    public function addContract($data)
    {
        return $this->contractRepo->addContract($data);
    }

    public function updateContract($id, $data)
    {
        return $this->contractRepo->updateContract($id, $data);
    }

    public function deleteContract($id)
    {
        return $this->contractRepo->deleteContract($id);
    }
}
