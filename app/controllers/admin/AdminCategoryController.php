<?php

require_once __DIR__ . '/../../models/CategoryModel.php';
include_once '../helpers/upload.php';

class AdminCategoryController
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

        $model      = new CategoryModel();
        $categories = $model->getCategories();

        include __DIR__ . '/../../views/admin/categories.php';
    }

    public function create()
    {
        $this->guardAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /admin/categories");
            exit;
        }

        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($name === '') {
            header("Location: /admin/categories");
            exit;
        }

        $icon = 'default-category.png';
        try {
            $uploaded = uploadFile('icon', '../uploads/Categories', 'image/*');
            if ($uploaded) {
                $icon = $uploaded;
            }
        } catch (Exception $e) {
            // keep default
        }

        $model = new CategoryModel();
        $model->create($name, $description, $icon);

        header("Location: /admin/categories");
        exit;
    }

    public function edit()
    {
        $this->guardAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /admin/categories");
            exit;
        }

        $id          = (int)($_POST['category_id'] ?? 0);
        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (!$id || $name === '') {
            header("Location: /admin/categories");
            exit;
        }

        $model    = new CategoryModel();
        $existing = $model->getCategoryById($id);

        if (!$existing) {
            header("Location: /admin/categories");
            exit;
        }

        $icon = $existing['Icon'];
        try {
            $uploaded = uploadFile('icon', '../uploads/Categories', 'image/*');
            if ($uploaded) {
                // Remove old icon if not the default
                if ($icon && $icon !== 'default-category.png') {
                    $oldPath = '../uploads/Categories/' . $icon;
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $icon = $uploaded;
            }
        } catch (Exception $e) {
            // keep existing
        }

        $model->update($id, $name, $description, $icon);

        header("Location: /admin/categories");
        exit;
    }

    public function delete()
    {
        $this->guardAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /admin/categories");
            exit;
        }

        $id = (int)($_POST['category_id'] ?? 0);
        if (!$id) {
            header("Location: /admin/categories");
            exit;
        }

        $model    = new CategoryModel();
        $existing = $model->getCategoryById($id);

        if ($existing) {
            $model->delete($id);
            // Remove icon file if not default
            if (!empty($existing['Icon']) && $existing['Icon'] !== 'default-category.png') {
                $oldPath = '../uploads/Categories/' . $existing['Icon'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
        }

        header("Location: /admin/categories");
        exit;
    }
}
