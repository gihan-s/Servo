<?php

require_once __DIR__ . '/../models/FeedModel.php';

class FeedController extends BaseController
{
    private $feedModel;

    public function __construct()
    {
        parent::__construct();
        $this->feedModel = new FeedModel();
    }

    // GET /feed
    public function index()
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        // Choose view by role
        if ($role === 'Provider') {
            $feedItems = $this->feedModel->getFeedItemsForProvider($userId);
            foreach ($feedItems as &$item) {
                $item['Posted'] = timeAgo($item['Created_At']);
                $item['Client_Name'] = $item['Client_First_Name'] . ' ' . $item['Client_Last_Name'];
            }
            unset($item); // break reference
            $viewFile = __DIR__ . '/../views/provider/Feed/index.php';
        } else {
            $this->notFound();
            return;
        }

        include $viewFile;
    }

    public function submitBid()
    {
        $this->ensureAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->htmlError(405);
            return;
        }

        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        if ($role !== 'Provider') {
            $this->htmlError(403);
            return;
        }

        $postId = $_POST['post_id'] ?? null;

        if (!$postId) {
            $this->htmlError(400);
            return;
        }

        $providerHasBid = $this->feedModel->providerHasBidOnPost($userId, $postId);
        if ($providerHasBid) {
            $this->htmlError(400);
            return;
        }

        $bidAmount = $_POST['bid_amount'] ?? null;
        $duration = $_POST['bid_duration'] ?? null;
        if (!$bidAmount || !$duration) {
            $this->htmlError(400);
            return;
        }
        $bidComment = $_POST['bid_message'] ?? null;
        // calculate duration in days based on unit (d/w/m) and value from form
        $bidDurationUnit = $_POST['bid_duration_unit'] ?? 'd';
        $allowedUnits = ['d', 'w', 'm'];
        if (!in_array($bidDurationUnit, $allowedUnits, true)) {
            $this->htmlError(400);
            return;
        }
        switch ($bidDurationUnit) {
            case 'd':
                $durationDays = $duration;
                break;
            case 'w':
                $durationDays = $duration * 7;
                break;
            case 'm':
                $durationDays = $duration * 30;
                break;
            default:
                $durationDays = $duration;
        }

        $result = $this->feedModel->submitBid($userId, $postId, $bidAmount, $bidComment, $durationDays);

        if ($result['success']) {
            header('Location: ' . BASE_URL . '/feed');
            exit();
        } else {
            $this->htmlError(500);
            return;
        }
    }

}
