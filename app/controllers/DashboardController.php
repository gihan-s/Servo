<?php

require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../models/PaymentModel.php';
require_once __DIR__ . '/../models/ProjectModel.php';
require_once __DIR__ . '/../models/BidModel.php';
require_once __DIR__ . '/../models/EarningsModel.php';

class DashboardController extends BaseController
{
    private $postModel;
    private $paymentModel;
    private $projectModel;
    private $bidModel;
    private $earningsModel;

    public function __construct()
    {
        parent::__construct(); // inherit BaseController instead of overriding
        $this->postModel = new PostModel();
        $this->paymentModel = new PaymentModel();
        $this->projectModel = new ProjectModel();
        $this->bidModel = new BidModel();
        $this->earningsModel = new EarningsModel();
    }

    // GET /dashboard
    public function index()
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        // Choose view by role
        if ($role === 'Client') {
            $activeRequestCount = $this->postModel->countActiveRequests($userId);
            $recentRequests = $this->postModel->getRecentRequests($userId, 2);
            $pendingPaymentCount = $this->paymentModel->getPaymentsCountByClientId($userId, 'Pending');
            $recentPayments = $this->paymentModel->getRecentPaymentsByClientId($userId, 2);
            $totalSpent = $this->paymentModel->getTotalSpentByClientId($userId);
            $totalProjectCount = $this->projectModel->countProjectsByClientId($userId);
            $ongoingProjects = array_map(static function (array $project): array {
                $stage = trim((string) ($project['Project_Status'] ?? 'ongoing'));

                return [
                    'Title' => (string) ($project['Title'] ?? ''),
                    'Stage' => $stage !== '' ? ucwords(str_replace(['-', '_'], ' ', $stage)) : 'Ongoing',
                    'Provider' => trim((string) ($project['Provider_Name'] ?? 'Unassigned')) ?: 'Unassigned',
                    'Budget' => (float) ($project['Requesting_Price'] ?? 0),
                    'Progress' => (int) ($project['Progress'] ?? 0),
                ];
            }, $this->projectModel->getOngoingProjectsForClient($userId));

            foreach ($recentRequests as &$request) {
                $request['Time_Ago'] = timeAgo($request['Created_At']);
                $request['Time_Left'] = timeLeft($request['End_At']);
            }
            unset($request);

            $viewFile = __DIR__ . '/../views/client/Dashboard/index.php';
        } elseif ($role === 'Provider') {
            $providerId = (int) $userId;

            $ongoingProjectsPayload = $this->projectModel->getOngoingProjectsForProvider($providerId, 1, 5);
            $pendingReviewPayload = $this->projectModel->getPendingReviewProjectsForProvider($providerId, 1, 5);

            $activeProjectsCount = (int) ($ongoingProjectsPayload['total'] ?? 0);
            $pendingProjectsCount = (int) ($pendingReviewPayload['total'] ?? 0);

            $providerBids = $this->bidModel->getProviderBids($providerId);
            $totalBids = count($providerBids);
            $recentBids = array_slice($providerBids, 0, 3);

            $totalRevenue = (float) $this->earningsModel->getTotalEarnings($providerId);
            $earningsChange = (float) $this->earningsModel->getMonthlyEarningsChange($providerId);
            $completedThisMonth = (int) $this->earningsModel->getCompletedThisMonth($providerId);
            $recentPayments = $this->earningsModel->getRecentTransactions($providerId, 3);

            $chartData = $this->earningsModel->getMonthlyChartData($providerId);
            $allChartLabels = (array) ($chartData['All']['labels'] ?? []);
            $allChartValues = (array) ($chartData['All']['earnings'] ?? []);
            $threeYearLabels = array_slice($allChartLabels, -12);
            $threeYearValues = array_slice($allChartValues, -12);

            $dashboardChartSeries = [
                '6m' => [
                    'labels' => array_values((array) ($chartData['6M']['labels'] ?? [])),
                    'values' => array_values((array) ($chartData['6M']['earnings'] ?? [])),
                ],
                '12m' => [
                    'labels' => array_values((array) ($chartData['1Y']['labels'] ?? [])),
                    'values' => array_values((array) ($chartData['1Y']['earnings'] ?? [])),
                ],
                '36m' => [
                    'labels' => array_values($threeYearLabels),
                    'values' => array_values($threeYearValues),
                ],
                'all' => [
                    'labels' => array_values($allChartLabels),
                    'values' => array_values($allChartValues),
                ],
            ];

            $activeProjects = array_slice((array) ($ongoingProjectsPayload['data'] ?? []), 0, 3);

            $viewFile = __DIR__ . '/../views/provider/Dashboard/index.php';
        } else {
            $this->notFound();
            return;
        }

        include $viewFile;
    }

}