<?php
require_once __DIR__ . '/../models/PaymentModel.php';

class PaymentController extends BaseController
{
    private PaymentModel $paymentModel;

    public function __construct()
    {
        parent::__construct();
        $this->paymentModel = new PaymentModel();
    }

    public function index()
    {
        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        if ($role !== 'Client') {
            http_response_code(403);
            echo "Invalid role";
            return;
        }

        $this->paymentModel->ensureAwaitingRowsForClient((int) $userId);

        $tab    = $_GET['tab']  ?? 'awaiting';
        $search = trim((string) ($_GET['q'] ?? ''));
        $sort   = $_GET['sort'] ?? 'recent';
        $page   = max(1, (int) ($_GET['page'] ?? 1));

        $validTabs  = ['awaiting', 'pending', 'completed', 'refunded'];
        $validSorts = ['recent', 'oldest', 'amount-desc', 'amount-asc'];
        if (!in_array($tab,  $validTabs,  true)) $tab  = 'awaiting';
        if (!in_array($sort, $validSorts, true)) $sort = 'recent';

        $clientId = (int) $userId;
        $pageSize = PaymentModel::PAGE_SIZE;

        $awaitingTotal  = $this->paymentModel->countAwaitingByClientId($clientId, $tab === 'awaiting'  ? $search : '');
        $pendingTotal   = $this->paymentModel->countPendingByClientId($clientId,   $tab === 'pending'   ? $search : '');
        $completedTotal = $this->paymentModel->countCompletedByClientId($clientId, $tab === 'completed' ? $search : '');
        $refundedTotal  = $this->paymentModel->countRefundedByClientId($clientId,  $tab === 'refunded'  ? $search : '');

        $awaitingPages  = (int) max(1, ceil($awaitingTotal  / $pageSize));
        $pendingPages   = (int) max(1, ceil($pendingTotal   / $pageSize));
        $completedPages = (int) max(1, ceil($completedTotal / $pageSize));
        $refundedPages  = (int) max(1, ceil($refundedTotal  / $pageSize));

        $awaitingPayments  = $tab === 'awaiting'  ? $this->paymentModel->getAwaitingPaymentsByClientId($clientId,  $search, $sort, $page) : [];
        $pendingPayments   = $tab === 'pending'   ? $this->paymentModel->getPendingPaymentsByClientId($clientId,   $search, $sort, $page) : [];
        $completedPayments = $tab === 'completed' ? $this->paymentModel->getCompletedPaymentsByClientId($clientId, $search, $sort, $page) : [];
        $refundedPayments  = $tab === 'refunded'  ? $this->paymentModel->getRefundedPaymentsByClientId($clientId,  $search, $sort, $page) : [];

        include __DIR__ . '/../views/client/Payments/index.php';
    }

    public function report(): void
    {
        header('Content-Type: application/json');

        if (($_SESSION['role'] ?? '') !== 'Client') {
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

        $summary = $this->paymentModel->getReportSummary((int) $_SESSION['user_id'], $from, $to);
        echo json_encode([
            'from'     => $from,
            'to'       => $to,
            'total'    => $summary['total_paid'],
            'pending'  => $summary['total_pending'],
            'refunded' => $summary['total_refunded'],
            'count'    => $summary['txn_count'],
        ]);
    }

    private function isValidDate(string $date): bool
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d !== false && $d->format('Y-m-d') === $date;
    }

    public function pay(int $paymentId): void
    {
        $this->requirePostAndOwnership($paymentId);
        if ($this->paymentModel->capturePayment($paymentId)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Payment placed on hold successfully.'];
            $this->redirectBack('pending');
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Payment could not be placed on hold. Please try again.'];
            $this->redirectBack();
        }
    }

    public function cancel(int $paymentId): void
    {
        $this->requirePostAndOwnership($paymentId);
        if ($this->paymentModel->cancelPaymentAndProject($paymentId)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Payment and project cancelled.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Cancel failed. This payment may already be completed.'];
        }
        $this->redirectBack();
    }

    public function refund(int $paymentId): void
    {
        $this->requirePostAndOwnership($paymentId);
        if ($this->paymentModel->requestRefund($paymentId)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Refund request submitted for admin review.'];
            $this->redirectBack('refunded');
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Refund request failed. Only paid invoices can be refunded.'];
            $this->redirectBack();
        }
    }

    private function requirePostAndOwnership(int $paymentId): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Method not allowed';
            exit;
        }
        if (($_SESSION['role'] ?? '') !== 'Client') {
            http_response_code(403);
            echo 'Invalid role';
            exit;
        }
        $ownerId = $this->paymentModel->getPaymentOwnerClientId($paymentId);
        if ($ownerId === null) {
            http_response_code(404);
            echo 'Payment not found';
            exit;
        }
        if ($ownerId !== (int) $_SESSION['user_id']) {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }
    }

    private function redirectBack(?string $tab = null): void
    {
        $target = BASE_URL . '/payments';
        if ($tab !== null) $target .= '?tab=' . urlencode($tab);
        header('Location: ' . $target);
        exit;
    }

    public function invoice(int $paymentId): void
    {
        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        if ($role !== 'Client') {
            http_response_code(403);
            echo "Invalid role";
            return;
        }

        $invoice = $this->paymentModel->getInvoiceById($paymentId);

        if (!$invoice) {
            http_response_code(404);
            echo "Invoice not found";
            return;
        }

        if ((int) $invoice['Client_ID'] !== (int) $userId) {
            http_response_code(403);
            echo "Forbidden";
            return;
        }

        include __DIR__ . '/../views/client/Payments/invoice.php';
    }
}
