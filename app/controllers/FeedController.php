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

    // GET /dashboard
    public function index()
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        // Choose view by role
        if ($role === 'Provider') {
            $feedItems = $this->feedModel->getFeedItemsForProvider($userId);
            foreach ($feedItems as $item) {
                $item['Posted'] = timeAgo($item['Created_At']);
                $item['Timeline'] = formatDuration($item['Est_Date']);
                $item['Client_Name'] = $item['Client_First_Name'] . ' ' . $item['Client_Last_Name'];
            }
            $viewFile = __DIR__ . '/../views/provider/Feed/index.php';
        } else {
            http_response_code(403);
            echo "Invalid role";
            return;
        }

        include $viewFile;
    }

    public function submitBid()
    {
        $this->ensureAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            // show error page and a link to go back to feed
            include __DIR__ . '/../views/error.php';
            return;
        }

        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        if ($role !== 'Provider') {
            http_response_code(403);
            echo 'Sorry something unexpected happened';
            include __DIR__ . '/../views/error.php';
            return;
        }

        $postId = $_POST['post_id'] ?? null;

        if (!$postId) {
            http_response_code(400);
            echo 'Post ID is required';
            return;
        }

        $bidAmount = $_POST['bid_amount'] ?? null;
        $bidComment = $_POST['bid_message'] ?? null;
        $bidTimeline = $_POST['bid_timeline'] ?? null;
        $estDate = date('Y-m-d H:i:s', strtotime("+$bidTimeline days"));

        if (!$bidAmount || !$bidTimeline) {
            http_response_code(400);
            echo 'Bid Amount, and Timeline are required';
            return;
        }

        echo '<pre>';
        print_r($_POST);
        echo '</pre>';
        exit();

        // $result = $this->feedModel->submitBid($userId, $postId, $bidAmount, $bidComment, $estDate);

        // if ($result['success']) {
        //     header('Location: ' . BASE_URL . '/feed');
        //     exit();
        // } else {
        //     http_response_code(500);
        //     echo 'Failed to submit bid';
        // }
    }

}