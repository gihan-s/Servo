<?php
// require_once __DIR__ . '/../models/UserModel.php';

class UsersController {
    public function index() {
        // $model = new UserModel();
        // $users = $model->getUsers();
        include __DIR__ . '/../views/users.php';
    }

    
    public function show($data){
        echo $data;
    }
}
