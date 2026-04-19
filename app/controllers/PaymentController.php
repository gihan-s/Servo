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

    // GET /dashboard
    public function index()
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        // Choose view by role
        if ($role === 'Client') {
            $viewFile = __DIR__ . '/../views/client/Payments/index.php';
        } else {
            $this->notFound();
            return;
        }

        include $viewFile;
    }
}