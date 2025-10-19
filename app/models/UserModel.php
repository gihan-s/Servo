<?php
require_once __DIR__ . '/../core/Database.php';

class UserModel extends Database {
    public function getUsers() {
        return $this->fetchAll("SELECT * FROM client");
    }
}
