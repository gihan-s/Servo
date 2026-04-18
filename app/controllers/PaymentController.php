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

    // GET /payments
    public function index()
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        if ($role === 'Client') {
            // Fetch all payment sections
            $awaitingPayments  = $this->paymentModel->getAwaitingPaymentsByClientId($userId);
            $pendingPayments   = $this->paymentModel->getPendingPaymentsByClientId($userId);
            $completedPayments = $this->paymentModel->getCompletedPaymentsByClientId($userId);
            $refundedPayments  = $this->paymentModel->getRefundedPaymentsByClientId($userId);

            $viewFile = __DIR__ . '/../views/client/Payments/index.php';
        } else {
            $this->notFound();
            return;
        }

        include $viewFile;
    }
}
