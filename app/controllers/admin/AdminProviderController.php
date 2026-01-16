<?php

require_once __DIR__ . '/../../models/ProviderModel.php';
require_once __DIR__ . '/../../models/CategoryModel.php';
require_once __DIR__ . '/../../models/SkillsModel.php';
require_once __DIR__ . '/../../models/LocationModel.php';

class AdminProviderController
{

    public function index()
    {

        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
            header("Location: /admin/login");
            exit;
        }

        $model = new ProviderModel();

        // Pagination setup
        $limit = 10; // users per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;
        $users = $model->getAllProviders($limit, $offset);
        $totalUsers = $model->getUserCount();
        $totalPages = ceil($totalUsers / $limit);

        include __DIR__ . '/../../views/admin/providers.php';
    }


    public function view($id)
    {
        $ProviderModel = new ProviderModel();
        $user = $ProviderModel->getProviderById($id);
        if (!$user) {
            echo "<p style='color:red;'>Provider not found</p>";
            return;
        }

        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getByProviderId($id);
        $categoryIds = array_column($categories, 'ID');


        $skillModel = new SkillsModel();
        $skills    = $skillModel->getByProviderCategoryIds($categoryIds);

        $locationModel = new LocationModel();
        $locations = $locationModel->getByProviderCategoryIds($categoryIds);

        foreach ($categories as &$category) {
            $categoryId = $category['ID'];
            $category['Skills']    = $skills[$categoryId] ?? [];
            $category['Locations'] = $locations[$categoryId] ?? [];
        }


        $user['Categories'] = $categories;

        include __DIR__ . '/../../views/admin/providerView.php';
    }


    public function review()
    {
        $model = new ProviderModel();

        $Status = "";
        if (isset($_POST["accept"])) {
            $Status = "Active";
        } else if (isset($_POST["reject"])) {
            $Status = "Rejected";
        } else {
            return;
        }

        $model->updateProviderStatus($_POST["provider_id"], $Status);

        header("Location: ../Providers");
    }
}
