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

        $tab    = $_GET['tab']  ?? 'completed';
        $search = trim((string) ($_GET['q'] ?? ''));
        $sort   = $_GET['sort'] ?? 'recent';
        $page   = max(1, (int) ($_GET['page'] ?? 1));

        $validTabs  = ['completed', 'refunded'];
        $validSorts = ['recent', 'oldest', 'amount-desc', 'amount-asc'];
        if (!in_array($tab,  $validTabs,  true)) $tab  = 'completed';
        if (!in_array($sort, $validSorts, true)) $sort = 'recent';

        $clientId = (int) $userId;
        $pageSize = PaymentModel::PAGE_SIZE;

        $completedTotal = $this->paymentModel->countCompletedByClientId($clientId, $tab === 'completed' ? $search : '');
        $refundedTotal  = $this->paymentModel->countRefundedByClientId($clientId,  $tab === 'refunded'  ? $search : '');

        $completedPages = (int) max(1, ceil($completedTotal / $pageSize));
        $refundedPages  = (int) max(1, ceil($refundedTotal  / $pageSize));

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
            $this->redirectBack('completed');
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

    /**
     * Initiates a PayHere checkout for the given Post_ID.
     * Called via POST form from the confirm-payment dialog.
     */
    public function payhereRedirect(int $postId): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); echo 'Method not allowed'; exit;
        }
        if (($_SESSION['role'] ?? '') !== 'Client') {
            http_response_code(403); echo 'Forbidden'; exit;
        }

        $clientId = (int) $_SESSION['user_id'];
        $details  = $this->paymentModel->getPaymentDetailsForPayHere($postId, $clientId);

        if (!$details) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Payment record not found or already processed.'];
            header('Location: ' . BASE_URL . '/projects');
            exit;
        }

        $amount    = number_format((float) ($details['Requesting_Price'] ?? 0), 2, '.', '');
        $orderId   = 'SERVO-POST-' . $postId;
        $currency  = 'LKR';

        // Generate hash: MD5(merchant_id + order_id + amount + currency + strtoupper(MD5(merchant_secret)))
        $hash = strtoupper(md5(
            PAYHERE_MERCHANT_ID .
            $orderId .
            $amount .
            $currency .
            strtoupper(md5(PAYHERE_MERCHANT_SECRET))
        ));

        $firstName = htmlspecialchars($details['First_Name'] ?? 'Customer', ENT_QUOTES, 'UTF-8');
        $lastName  = htmlspecialchars($details['Last_Name']  ?? '', ENT_QUOTES, 'UTF-8');
        $email     = htmlspecialchars($details['Email']      ?? '', ENT_QUOTES, 'UTF-8');
        $phone     = preg_replace('/[^0-9+\-\s()]/', '', $details['Contact_No'] ?? '0000000000');
        if (empty($phone)) $phone = '0000000000';

        $checkoutUrl = PAYHERE_CHECKOUT_URL;

        $returnUrl = APP_URL . '/payments/payhere-return';
        $cancelUrl = APP_URL . '/payments/payhere-cancel';
        $notifyUrl = APP_URL . '/payments/payhere-notify';

        $itemName = htmlspecialchars(substr($details['Project_Title'] ?? 'Service Payment', 0, 100), ENT_QUOTES, 'UTF-8');

        // Store minimal order info in session for the return page
        $_SESSION['payhere_order'] = [
            'order_id' => $orderId,
            'post_id'  => $postId,
            'title'    => $details['Project_Title'],
            'amount'   => $amount,
            'currency' => $currency,
        ];

        $fields = [
            'merchant_id' => PAYHERE_MERCHANT_ID,
            'return_url'  => $returnUrl,
            'cancel_url'  => $cancelUrl,
            'notify_url'  => $notifyUrl,
            'order_id'    => $orderId,
            'items'       => $itemName,
            'currency'    => $currency,
            'amount'      => $amount,
            'first_name'  => $firstName,
            'last_name'   => $lastName,
            'email'       => $email,
            'phone'       => $phone,
            'address'     => 'N/A',
            'city'        => 'Colombo',
            'country'     => 'Sri Lanka',
            'hash'        => $hash,
        ];

        // Render auto-submit redirect page
        include __DIR__ . '/../views/client/Payments/payhere_redirect.php';
        exit;
    }
}
