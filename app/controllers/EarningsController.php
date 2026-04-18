<?php

class EarningsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    // GET /dashboard
    public function index()
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        if ($role !== 'Provider') {
            $this->notFound();
            return;
        }

        include $viewFile;
    }

}