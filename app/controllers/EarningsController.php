<?php

require_once __DIR__ . '/../models/EarningsModel.php';

class EarningsController extends BaseController
{
    private $earningsModel;

    public function __construct()
    {
        parent::__construct();
        $this->earningsModel = new EarningsModel();
    }

    // GET /earnings
    public function index()
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        if ($role !== 'Provider') {
            http_response_code(403);
            echo "Invalid role";
            return;
        }

        $providerId = (int) $userId;

        // Metrics
        $totalEarnings      = $this->earningsModel->getTotalEarnings($providerId);
        $earningsChange     = $this->earningsModel->getMonthlyEarningsChange($providerId);
        $pendingPayout      = $this->earningsModel->getPendingPayout($providerId);
        $avgProjectValue    = $this->earningsModel->getAvgProjectValue($providerId);
        $completedProjects  = $this->earningsModel->getCompletedProjectsCount($providerId);
        $completedThisMonth = $this->earningsModel->getCompletedThisMonth($providerId);

        // Chart data (all periods)
        $chartData = $this->earningsModel->getMonthlyChartData($providerId);

        // Recent transactions + full list for modal
        $transactions    = $this->earningsModel->getRecentTransactions($providerId, 5);
        $allTransactions = $this->earningsModel->getAllTransactions($providerId);

        $viewFile = __DIR__ . '/../views/provider/Earnings/index.php';
        include $viewFile;
    }

    // GET /earnings/report?from=YYYY-MM-DD&to=YYYY-MM-DD
    public function report(): void
    {
        header('Content-Type: application/json');

        if (($_SESSION['role'] ?? '') !== 'Provider') {
            http_response_code(403);
            echo json_encode(['error' => 'Invalid role']);
            return;
        }

        $from = trim((string) ($_GET['from'] ?? ''));
        $to   = trim((string) ($_GET['to']   ?? ''));

        if (!$this->isValidDate($from) || !$this->isValidDate($to)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid date format. Use YYYY-MM-DD.']);
            return;
        }
        if (strtotime($from) > strtotime($to)) {
            http_response_code(400);
            echo json_encode(['error' => 'Start date must be before end date.']);
            return;
        }

        $summary = $this->earningsModel->getReportSummary((int) $_SESSION['user_id'], $from, $to);
        echo json_encode([
            'from'       => $from,
            'to'         => $to,
            'gross'      => $summary['total_gross'],
            'commission' => $summary['total_commission'],
            'net'        => $summary['total_net'],
            'pending'    => $summary['total_pending'],
            'count'      => $summary['txn_count'],
        ]);
    }

    // GET /earnings/receipt/{id}
    public function receipt(int $paymentId): void
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        if ($role !== 'Provider') {
            http_response_code(403);
            echo "Invalid role";
            return;
        }

        $ownerId = $this->earningsModel->getReceiptOwnerProviderId($paymentId);
        if ($ownerId === null) {
            http_response_code(404);
            echo "Receipt not found";
            return;
        }
        if ($ownerId !== (int) $userId) {
            http_response_code(403);
            echo "Forbidden";
            return;
        }

        $receipt = $this->earningsModel->getReceiptById($paymentId);
        if (!$receipt) {
            http_response_code(404);
            echo "Receipt not found";
            return;
        }

        include __DIR__ . '/../views/provider/Earnings/receipt.php';
    }

    private function isValidDate(string $date): bool
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d !== false && $d->format('Y-m-d') === $date;
    }
}
