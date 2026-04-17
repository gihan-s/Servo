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

        // TODO: to be implemented and remove the following lines below this code segment
        // $activeBids = $this->bidModel->getActiveBidsForProvider($userId);
        // $acceptedBids = $this->bidModel->getAcceptedBidsForProvider($userId);
        // $closedBids = $this->bidModel->getClosedBidsForProvider($userId);
        // // formatting data for view
        // $activeBids = $this->formatBidData($activeBids);
        // $acceptedBids = $this->formatBidData($acceptedBids);
        // $closedBids = $this->formatBidData($closedBids);

        $bids = $this->bidModel->getProviderBids($userId);
        $bids = $this->formatBidData($bids);
        $groupedBids = $this->groupBids($bids);
        unset($bids); // free to save memory
        $activeBids = $groupedBids['active'];
        $acceptedBids = $groupedBids['accepted'];
        $closedBids = $groupedBids['closed'];

        include __DIR__ . '/../views/provider/Bids/index.php';
    }

    private function groupBids(array $bids) {
        // $bids contains all bids for the provider, we need to group them by status for the view
        // status can be 'Active', 'Accepted', 'Closed'
        // for each $bid in $bids, we will add it to the appropriate group based on lowercase($item['Status']) == 'active'/'accepted'/'closed'
        $grouped = ['active' => [], 'accepted' => [], 'closed' => []];
        foreach ($bids as $bid) {
            $status = strtolower($bid['Status']);
            if (isset($grouped[$status])) {
                $grouped[$status][] = $bid;
            }
        }
        return $grouped;
    }

    private function formatBidData(array $bids) {
        // this function will format the bid data as needed for display in the view
        foreach ($bids as &$bid) {
            $bid['Client_Name'] = $bid['Client_First_Name'] . ' ' . $bid['Client_Last_Name'];
            $bid['Bid_Amount'] = 'LKR ' . number_format($bid['Amount'], 2);
            $durationHours = $bid['Duration']; 
            if ($durationHours >= 24 * 7) {
                $weeks = floor($durationHours / (24 * 7));
                $bid['Duration'] = $weeks . ' week' . ($weeks > 1 ? 's' : '');
            } else if ($durationHours >= 24) {
                $days = floor($durationHours / 24);
                $bid['Duration'] = $days . ' day' . ($days > 1 ? 's' : '');
            } else {
                $bid['Duration'] = $durationHours . ' hour' . ($durationHours > 1 ? 's' : '');
            }
            $bid['Bid_Date'] = timeAgo($bid['Created_At']);
        }
        unset($bid); // break reference
        return $bids;
    }
}
