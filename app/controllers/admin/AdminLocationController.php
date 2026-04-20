<?php

require_once __DIR__ . '/../../models/LocationModel.php';

class AdminLocationController
{
    private function guardAdmin()
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
            header("Location: /admin/login");
            exit;
        }
    }

    public function index()
    {
        $this->guardAdmin();

        $model     = new LocationModel();
        $locations = $model->getAllLocations();
        $districts = $model->getDistrictList();

        include __DIR__ . '/../../views/admin/locations.php';
    }

    public function create()
    {
        $this->guardAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /admin/locations");
            exit;
        }

        $district = trim($_POST['district'] ?? '');
        $city     = trim($_POST['city'] ?? '');

        if ($district === '' || $city === '') {
            header("Location: /admin/locations");
            exit;
        }

        $model = new LocationModel();
        $model->create($district, $city);

        header("Location: /admin/locations");
        exit;
    }

    public function edit()
    {
        $this->guardAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /admin/locations");
            exit;
        }

        $id       = (int)($_POST['location_id'] ?? 0);
        $district = trim($_POST['district'] ?? '');
        $city     = trim($_POST['city'] ?? '');

        if (!$id || $district === '' || $city === '') {
            header("Location: /admin/locations");
            exit;
        }

        $model = new LocationModel();
        $model->update($id, $district, $city);

        header("Location: /admin/locations");
        exit;
    }

    public function delete()
    {
        $this->guardAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /admin/locations");
            exit;
        }

        $id = (int)($_POST['location_id'] ?? 0);
        if (!$id) {
            header("Location: /admin/locations");
            exit;
        }

        $model = new LocationModel();
        $model->deleteLocation($id);

        header("Location: /admin/locations");
        exit;
    }
}
