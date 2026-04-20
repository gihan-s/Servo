<?php

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/PaymentModel.php';

/**
 * Handles PayHere callbacks (notify, return, cancel).
 * Does NOT extend BaseController — no session/auth required.
 */
class PayHereWebhookController
{
    private PaymentModel $paymentModel;
    private string $logFile;

    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
        $this->logFile = __DIR__ . '/../../payhere_debug.log';
    }

    private function log(string $msg): void
    {
        file_put_contents($this->logFile, date('Y-m-d H:i:s') . ' ' . $msg . PHP_EOL, FILE_APPEND);
    }

    /**
     * PayHere IPN Notify — server-to-server POST, no session.
     * Extracts Post_ID from order_id (SERVO-POST-{id}), creates project + payment.
     */
    public function notify(): void
    {
        $this->log('NOTIFY HIT — method=' . $_SERVER['REQUEST_METHOD'] . ' POST=' . json_encode($_POST));

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->log('NOTIFY REJECTED: not POST');
            http_response_code(405);
            exit('Method Not Allowed');
        }

        $merchantId      = $_POST['merchant_id']      ?? '';
        $orderId         = $_POST['order_id']          ?? '';
        $payhereAmount   = $_POST['payhere_amount']    ?? '';
        $payhereCurrency = $_POST['payhere_currency']  ?? '';
        $statusCode      = $_POST['status_code']       ?? '';
        $md5sig          = $_POST['md5sig']            ?? '';

        if (empty($merchantId) || empty($orderId) || empty($payhereAmount) || empty($payhereCurrency) || empty($statusCode) || empty($md5sig)) {
            $this->log('NOTIFY REJECTED: missing fields');
            http_response_code(400);
            exit('Bad Request');
        }

        if ($merchantId !== PAYHERE_MERCHANT_ID) {
            $this->log("NOTIFY REJECTED: merchant mismatch got=$merchantId expected=" . PAYHERE_MERCHANT_ID);
            http_response_code(403);
            exit('Forbidden');
        }

        // Verify hash
        $localHash = strtoupper(md5(
            $merchantId .
            $orderId .
            $payhereAmount .
            $payhereCurrency .
            $statusCode .
            strtoupper(md5(PAYHERE_MERCHANT_SECRET))
        ));

        if ($localHash !== strtoupper($md5sig)) {
            $this->log("NOTIFY REJECTED: hash mismatch local=$localHash remote=$md5sig");
            http_response_code(403);
            exit('Forbidden');
        }

        // Extract Post_ID from order_id format: SERVO-POST-{postId}
        if (!preg_match('/^SERVO-POST-(\d+)$/i', $orderId, $m)) {
            $this->log("NOTIFY REJECTED: bad order_id format: $orderId");
            http_response_code(400);
            exit('Invalid order ID');
        }
        $postId = (int) $m[1];

        if ($statusCode === '2') {
            $result = $this->paymentModel->createProjectAndPayment($postId, (float) $payhereAmount);
            $this->log("NOTIFY createProjectAndPayment postId=$postId amount=$payhereAmount result=" . ($result ? 'OK' : 'FAIL'));
        } else {
            $this->log("NOTIFY status not success: code=$statusCode order=$orderId");
        }

        http_response_code(200);
        exit('OK');
    }

    /**
     * Return page — user's browser redirects here after payment.
     * Also creates project as fallback (in case IPN was blocked by ngrok free tier).
     */
    public function returnPage(): void
    {
        $order = $_SESSION['payhere_order'] ?? null;
        unset($_SESSION['payhere_order']);

        $this->log('RETURN HIT | session_order=' . json_encode($order) . ' | GET=' . json_encode($_GET));

        // Fallback: create project from session data if IPN hasn't done it yet
        if ($order && !empty($order['post_id']) && !empty($order['amount'])) {
            $result = $this->paymentModel->createProjectAndPayment(
                (int) $order['post_id'],
                (float) $order['amount']
            );
            $this->log('RETURN createProjectAndPayment postId=' . $order['post_id'] . ' result=' . ($result ? 'OK' : 'SKIP/FAIL'));
        }

        include __DIR__ . '/../views/client/Payments/payhere_return.php';
    }

    /**
     * Cancel page — user cancelled payment on PayHere.
     */
    public function cancelPage(): void
    {
        $order = $_SESSION['payhere_order'] ?? null;
        unset($_SESSION['payhere_order']);
        $this->log('CANCEL HIT | order=' . json_encode($order));
        include __DIR__ . '/../views/client/Payments/payhere_cancel.php';
    }
}
