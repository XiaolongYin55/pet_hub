<?php
require_once __DIR__ . '/../repositories/AddressRepository.php';

class AddressService {
    private $addressRepository;

    public function __construct($conn) {
        $this->addressRepository = new AddressRepository($conn);
    }

    public function getAddressByUserId($userId) {
        return $this->addressRepository->getAddressByUserId($userId);
    }
}
