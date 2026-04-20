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

        if ($role === 'Provider') {
            $feedItems = $this->feedModel->getFeedItemsForProvider($userId);
            foreach ($feedItems as &$item) {
                $item['Posted']      = timeAgo($item['Created_At']);
                $item['Client_Name'] = $item['Client_First_Name'] . ' ' . $item['Client_Last_Name'];
            }
            unset($item);

            // Load all bids for every post in one query
            $postIds   = array_column($feedItems, 'Post_ID');
            $allBids   = $this->feedModel->getBidsForPosts($postIds);

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

        $postId = isset($_POST['post_id']) ? (int) $_POST['post_id'] : 0;
        if (!$postId) {
            $this->htmlError(400);
            return;
        }

        // Block duplicate bids
        if ($this->feedModel->providerHasBidOnPost($userId, $postId)) {
            $this->htmlError(409);
            return;
        }

        $bidAmount = $_POST['bid_amount'] ?? null;
        $duration  = $_POST['bid_duration'] ?? null;
        if (!$bidAmount || !$duration) {
            $this->htmlError(400);
            return;
        }
        $bidComment      = $_POST['bid_message'] ?? null;
        $bidDurationUnit = $_POST['bid_duration_unit'] ?? 'd';
        $allowedUnits    = ['d', 'w', 'm'];
        if (!in_array($bidDurationUnit, $allowedUnits, true)) {
            $this->htmlError(400);
            return;
        }
        $durationHours = $this->durationToHours((int) $duration, $bidDurationUnit);

        $result = $this->feedModel->submitBid($userId, $postId, (float) $bidAmount, $bidComment, $durationHours);

        if ($result['success']) {
            header('Location: ' . BASE_URL . '/feed');
            exit();
        } else {
            $this->htmlError(500);
            return;
        }
    }

    public function editBid()
    {
        $this->ensureAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->htmlError(405);
            return;
        }

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        if ($role !== 'Provider') {
            $this->htmlError(403);
            return;
        }

        $bidId    = isset($_POST['bid_id'])    ? (int) $_POST['bid_id']    : 0;
        $bidAmount = $_POST['bid_amount'] ?? null;
        $duration  = $_POST['bid_duration'] ?? null;

        if (!$bidId || !$bidAmount || !$duration) {
            $this->htmlError(400);
            return;
        }

        $bidComment      = $_POST['bid_message'] ?? null;
        $bidDurationUnit = $_POST['bid_duration_unit'] ?? 'd';
        $allowedUnits    = ['d', 'w', 'm'];
        if (!in_array($bidDurationUnit, $allowedUnits, true)) {
            $this->htmlError(400);
            return;
        }
        $durationHours = $this->durationToHours((int) $duration, $bidDurationUnit);

        $result = $this->feedModel->updateBid($bidId, $userId, (float) $bidAmount, $bidComment, $durationHours);

        if ($result['success']) {
            header('Location: ' . BASE_URL . '/feed');
            exit();
        } else {
            $this->htmlError(500);
            return;
        }
    }

    public function cancelBid()
    {
        $this->ensureAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->htmlError(405);
            return;
        }

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        if ($role !== 'Provider') {
            $this->htmlError(403);
            return;
        }

        $bidId = isset($_POST['bid_id']) ? (int) $_POST['bid_id'] : 0;
        if (!$bidId) {
            $this->htmlError(400);
            return;
        }

        $result = $this->feedModel->cancelBid($bidId, $userId);

        if ($result['success']) {
            header('Location: ' . BASE_URL . '/feed');
            exit();
        } else {
            $this->htmlError(500);
            return;
        }
    }

    private function durationToHours(int $durationValue, string $durationUnit): int
    {
        if ($durationValue <= 0) {
            return 0;
        }

        switch ($durationUnit) {
            case 'w':
                return $durationValue * 7 * 24;
            case 'm':
                return $durationValue * 30 * 24;
            case 'd':
            default:
                return $durationValue * 24;
        }
    }

}
