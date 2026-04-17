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
            foreach ($feedItems as &$item) {
                $item['Posted'] = timeAgo($item['Created_At']);
                $item['Client_Name'] = $item['Client_First_Name'] . ' ' . $item['Client_Last_Name'];
            }
            unset($item); // break reference
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
        $duration = $_POST['bid_duration'] ?? null;
        if (!$bidAmount || !$duration) {
            http_response_code(400);
            echo 'Bid Amount, and Duration are required';
            return;
        }
        $bidComment = $_POST['bid_message'] ?? null;
        // calculate duration in days based on unit (d/w/m) and value from form
        $bidDurationUnit = $_POST['bid_duration_unit'] ?? 'd';
        $allowedUnits = ['d', 'w', 'm'];
        if (!in_array($bidDurationUnit, $allowedUnits, true)) {
            http_response_code(400);
            echo 'Invalid duration unit';
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

        echo '<pre>';
        print_r($_POST);
        echo 'Calculated duration in days: ' . $durationDays;
        echo '</pre>';
        exit();

        // $result = $this->feedModel->submitBid($userId, $postId, $bidAmount, $bidComment, $durationDays);

        // if ($result['success']) {
        //     header('Location: ' . BASE_URL . '/feed');
        //     exit();
        // } else {
        //     http_response_code(500);
        //     echo 'Failed to submit bid';
        // }
    }

}
