<?php

require_once __DIR__ . '/../models/BidModel.php';

class BidsController extends BaseController
{
    private $bidModel;

    public function __construct()
    {
        parent::__construct();
        $this->bidModel = new BidModel();
    }

    public function index()
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        if ($role !== 'Provider') {
            http_response_code(403);
            echo 'Invalid role';
            return;
        }

        $groupedBids = $this->bidModel->getProviderBidsGrouped($userId);
        $activeBids = $groupedBids['active'];
        $acceptedBids = $groupedBids['accepted'];
        $rejectedBids = $groupedBids['rejected'];

        include __DIR__ . '/../views/provider/Bids/index.php';
    }

}
