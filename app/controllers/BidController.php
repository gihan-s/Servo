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
            $this->htmlError(403);
            return;
        }

        $bids = $this->bidModel->getProviderBids($userId);
        $bids = $this->formatBidData($bids);
        $groupedBids = $this->groupBids($bids, (int) $userId);
        unset($bids); // free to save memory
        $activeBids = $groupedBids['active'];
        $acceptedBids = $groupedBids['accepted'];
        $closedBids = $groupedBids['closed'];

        include __DIR__ . '/../views/provider/Bids/index.php';
    }

    public function edit()
    {
        $this->ensureAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonError('Method not allowed', 405);
            return;
        }

        $role = $_SESSION['role'] ?? '';
        if ($role !== 'Provider') {
            $this->jsonError('Forbidden', 403, 'forbidden');
            return;
        }

        $providerId = (int) ($_SESSION['user_id'] ?? 0);
        $bidId = (int) ($_POST['bid_id'] ?? 0);
        $amount = (float) ($_POST['bid_amount'] ?? 0);
        $durationValue = (int) ($_POST['bid_duration'] ?? 0);
        $durationUnit = strtolower(trim((string) ($_POST['bid_duration_unit'] ?? 'd')));
        $comment = trim((string) ($_POST['bid_message'] ?? ''));

        if ($providerId <= 0 || $bidId <= 0) {
            $this->jsonError('Invalid provider or bid identifier', 400, 'invalid_input');
            return;
        }

        if ($amount <= 0) {
            $this->jsonError('Bid amount must be greater than zero', 400, 'invalid_input');
            return;
        }

        if ($durationValue <= 0) {
            $this->jsonError('Duration must be greater than zero', 400, 'invalid_input');
            return;
        }

        $allowedUnits = ['d', 'w', 'm'];
        if (!in_array($durationUnit, $allowedUnits, true)) {
            $this->jsonError('Invalid duration unit', 400, 'invalid_input');
            return;
        }

        $durationHours = $this->durationToHours($durationValue, $durationUnit);

        if ($comment === '') {
            $this->jsonError('Proposal note cannot be empty', 400, 'invalid_input');
            return;
        }

        if (strlen($comment) > 255) {
            $this->jsonError('Proposal note must be 255 characters or less', 400, 'invalid_input');
            return;
        }

        $result = $this->bidModel->updateBidForProvider($providerId, $bidId, $amount, $comment, $durationHours);

        if (!($result['success'] ?? false)) {
            $errorCode = (string) ($result['errorCode'] ?? 'unknown');
            $statusCode = 400;
            if ($errorCode === 'forbidden') {
                $statusCode = 403;
            } elseif ($errorCode === 'not_found') {
                $this->jsonError($result['message'] ?? 'Bid not found', 404, 'not_found');
                return;
            }

            $this->jsonError($result['message'] ?? 'Unable to update bid', $statusCode, $errorCode);
            return;
        }

        $updatedAmount = (float) ($result['amount'] ?? $amount);
        $updatedDurationHours = (int) ($result['durationHours'] ?? $durationHours);
        $updatedDurationState = $this->hoursToDurationState($updatedDurationHours);
        $updatedComment = (string) ($result['comment'] ?? $comment);
        $updatedAt = (string) ($result['updatedAt'] ?? date('Y-m-d H:i:s'));

        $this->jsonResponse([
            'success' => true,
            'message' => 'Bid updated successfully',
            'bid' => [
                'bidId' => $bidId,
                'amount' => $updatedAmount,
                'amountFormatted' => 'LKR ' . number_format($updatedAmount, 2),
                'durationHours' => $updatedDurationHours,
                'durationUnitValue' => $updatedDurationState['value'],
                'durationUnit' => $updatedDurationState['unit'],
                'durationLabel' => $this->formatDurationLabel($updatedDurationHours),
                'comment' => $updatedComment,
                'status' => 'Active',
                'statusKey' => 'active',
                'bidDateLabel' => timeAgo($updatedAt),
            ],
        ]);
    }

    public function withdraw()
    {
        $this->ensureAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonError('Method not allowed', 405);
            return;
        }

        $role = $_SESSION['role'] ?? '';
        if ($role !== 'Provider') {
            $this->jsonError('Forbidden', 403, 'forbidden');
            return;
        }

        $providerId = (int) ($_SESSION['user_id'] ?? 0);
        $bidId = (int) ($_POST['bid_id'] ?? 0);

        if ($providerId <= 0 || $bidId <= 0) {
            $this->jsonError('Invalid provider or bid identifier', 400, 'invalid_input');
            return;
        }

        $result = $this->bidModel->withdrawBidForProvider($providerId, $bidId);

        if (!($result['success'] ?? false)) {
            $errorCode = (string) ($result['errorCode'] ?? 'unknown');

            if ($errorCode === 'not_found') {
                $this->jsonError($result['message'] ?? 'Bid not found', 404, 'not_found');
                return;
            }

            if ($errorCode === 'not_withdrawable') {
                $this->jsonError($result['message'] ?? 'Only active bids can be withdrawn', 409, 'not_withdrawable');
                return;
            }

            $statusCode = $errorCode === 'forbidden' ? 403 : 400;
            $this->jsonError($result['message'] ?? 'Unable to withdraw bid', $statusCode, $errorCode);
            return;
        }

        $this->jsonResponse([
            'success' => true,
            'message' => 'Bid withdrawn successfully',
            'bid' => [
                'bidId' => (int) ($result['bidId'] ?? $bidId),
                'status' => 'Deleted',
                'statusKey' => 'deleted',
            ],
        ]);
    }

    private function groupBids(array $bids, int $providerId): array
    {
        // Post-level state is the source of truth because bid status can be stale.
        $grouped = ['active' => [], 'accepted' => [], 'closed' => []];

        foreach ($bids as &$bid) {
            $statusKey = $this->resolveBidSection($bid, $providerId);

            // if Status_Key = 'deleted', then it is skipped
            if(array_key_exists($statusKey, $grouped)) {
                $bid['Status_Key'] = $statusKey;
                $bid['Status'] = ucfirst($statusKey);
                $grouped[$statusKey][] = $bid;
            }
        }
        unset($bid);

        return $grouped;
    }

    private function resolveBidSection(array $bid, int $providerId): string
    {
        $postStatus = strtolower(trim((string) ($bid['Post_Status'] ?? '')));
        $requestStatus = strtolower(trim((string) ($bid['Post_Request_Status'] ?? $bid['Request_Status'] ?? '')));

        if ($bid['Status_Key'] === 'deleted') {
            return 'deleted';
        }

        if ($postStatus !== '' && !$this->isOpenPostStatus($postStatus)) {
            return 'closed';
        }

        $postProviderRaw = $bid['Post_Provider_ID'] ?? null;
        $postProviderId = ($postProviderRaw === null || $postProviderRaw === '') ? null : (int) $postProviderRaw;

        if ($postProviderId !== null && $postProviderId === $providerId &&
        in_array($requestStatus, ['pending', 'ongoing', 'accepted'], true)
        ) {
            return 'accepted';
        }

        if ($postProviderId === null && $requestStatus === 'open') {
            return 'active';
        }

        return 'closed';
    }

    private function isOpenPostStatus(string $postStatus): bool
    {
        return in_array($postStatus, ['published', 'active'], true);
    }

    private function formatBidData(array $bids): array
    {
        // this function will format the bid data as needed for display in the view
        foreach ($bids as &$bid) {
            $bid['Client_Name'] = $bid['Client_First_Name'] . ' ' . $bid['Client_Last_Name'];
            $bid['Bid_Amount'] = 'LKR ' . number_format($bid['Amount'], 2);
            $durationHours = (int) ($bid['Duration'] ?? 0);
            $durationState = $this->hoursToDurationState($durationHours);
            $bid['Duration_Hours'] = $durationHours;
            $bid['Duration_Unit_Value'] = $durationState['value'];
            $bid['Duration_Unit'] = $durationState['unit'];
            $bid['Duration'] = $this->formatDurationLabel($durationHours);
            $bid['Bid_Ref'] = 'BID-' . str_pad((string) ($bid['Bid_ID'] ?? 0), 6, '0', STR_PAD_LEFT);
            $bid['Bid_Date'] = timeAgo($bid['Created_At']);
        }
        unset($bid); // break reference
        return $bids;
    }

    private function formatDurationLabel(int $durationHours): string
    {
        $durationState = $this->hoursToDurationState($durationHours);
        $value = (int) ($durationState['value'] ?? 0);
        $unit = (string) ($durationState['unit'] ?? 'd');

        if ($value <= 0) {
            return '0 days';
        }

        if ($unit === 'm') {
            return $value . ' month' . ($value > 1 ? 's' : '');
        }

        if ($unit === 'w') {
            return $value . ' week' . ($value > 1 ? 's' : '');
        }

        return $value . ' day' . ($value > 1 ? 's' : '');
    }

    private function durationToHours(int $durationValue, string $durationUnit): int
    {
        if ($durationValue <= 0) {
            return 0;
        }

        if ($durationUnit === 'w') {
            return $durationValue * 7 * 24;
        }

        if ($durationUnit === 'm') {
            return $durationValue * 30 * 24;
        }

        return $durationValue * 24;
    }

    private function hoursToDurationState(int $durationHours): array
    {
        if ($durationHours <= 0) {
            return ['value' => 0, 'unit' => 'd'];
        }

        $durationDays = (int) ceil($durationHours / 24);

        if ($durationDays > 0 && $durationDays % 30 === 0) {
            return ['value' => (int) ($durationDays / 30), 'unit' => 'm'];
        }

        if ($durationDays > 0 && $durationDays % 7 === 0) {
            return ['value' => (int) ($durationDays / 7), 'unit' => 'w'];
        }

        return ['value' => $durationDays, 'unit' => 'd'];
    }

}
