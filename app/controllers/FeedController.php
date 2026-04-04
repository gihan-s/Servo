<?php

class FeedController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    // GET /dashboard
    public function index()
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        // Choose view by role
        if ($role === 'Provider') {
            $feedItems = [
                [
                    'client' => 'Nadia Perera',
                    'title' => 'Build a Portfolio Website',
                    'budget' => '$600',
                    'timeline' => '10 days',
                    'posted' => '2h ago',
                    'category' => 'Web Development',
                    'description' => 'Need a modern, responsive personal portfolio with project showcase and contact form.',
                    'statusClass' => 'status-awaiting',
                    'statusLabel' => 'Open Opportunity',
                ],
                [
                    'client' => 'Isuru Fernando',
                    'title' => 'Logo + Brand Kit',
                    'budget' => '$350',
                    'timeline' => '5 days',
                    'posted' => '5h ago',
                    'category' => 'Graphic Design',
                    'description' => 'Client is looking for a clean logo, color palette and typography suggestions for a new startup.',
                    'statusClass' => 'status-review',
                    'statusLabel' => 'Hot Lead',
                ],
                [
                    'client' => 'Tharushi De Silva',
                    'title' => 'WordPress SEO Optimization',
                    'budget' => '$480',
                    'timeline' => '14 days',
                    'posted' => '1d ago',
                    'category' => 'SEO',
                    'description' => 'On-page + technical SEO improvements for an e-commerce WordPress site to increase search visibility.',
                    'statusClass' => 'status-progress',
                    'statusLabel' => 'Recommended',
                ],
            ];
            $viewFile = __DIR__ . '/../views/provider/Feed/index.php';
        } else {
            http_response_code(403);
            echo "Invalid role";
            return;
        }

        include $viewFile;
    }

}