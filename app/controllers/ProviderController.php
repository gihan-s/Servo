<?php

require_once __DIR__ . '/../../helpers/locations.php';

class ProviderController extends BaseController
{

    public function __construct()
    {
        if (isset($_GET['url']) && $_GET['url'] === 'providers/search') {
            return;
        }

        parent::__construct();
    }

    // GET /dashboard
    public function index()
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];
        $categories = [];

        // Choose view by role
        if ($role === 'Client') {
            require_once __DIR__ . '/../models/CategoryModel.php';
            $categoryModel = new CategoryModel();
            $categories = $categoryModel->getCategories();
            $viewFile = __DIR__ . '/../views/client/Providers/index.php';
        }
        // elseif ($role === 'Provider') {
        //     $viewFile = __DIR__ . '/../views/provider/Providers/index.php';
        // }
        else {
            http_response_code(403);
            echo "Invalid role";
            return;
        }

        include $viewFile;
    }

    // GET /providers/search - AJAX endpoint for searching providers
    public function search()
    {
        header('Content-Type: application/json');

        try {
            require_once __DIR__ . '/../models/ProviderModel.php';
            require_once __DIR__ . '/../models/ProviderSocialModel.php';
            require_once __DIR__ . '/../models/CategoryModel.php';
            require_once __DIR__ . '/../models/LocationModel.php';
            require_once __DIR__ . '/../../helpers/socialmedia.php';

            $providerModel = new ProviderModel();
            $socialModel = new ProviderSocialModel();
            $categoryModel = new CategoryModel();
            $locationModel = new LocationModel();

            // Get pagination parameters
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 12;
            $offset = ($page - 1) * $limit;

            $providers = [];
            $search = trim((string) ($_GET['q'] ?? ''));
            $sort = trim((string) ($_GET['sort'] ?? ''));

            $providerRows = $providerModel->getActiveProvidersForSearch($limit, $offset, $search, $sort);

            foreach ($providerRows as $provider) {
                $providerSkills = $providerModel->getSkillsByProviderId($provider['Provider_ID']);
                $providerCategoriesRaw = $categoryModel->getByProviderId((int) $provider['Provider_ID']);
                $providerCategories = [];
                $providerCategoryIds = [];
                foreach ($providerCategoriesRaw as $categoryRow) {
                    $categoryName = trim((string) ($categoryRow['Category_Type'] ?? ''));
                    if ($categoryName !== '') {
                        $providerCategories[] = $categoryName;
                    }

                    $providerCategoryId = (int) ($categoryRow['ID'] ?? 0);
                    if ($providerCategoryId > 0) {
                        $providerCategoryIds[] = $providerCategoryId;
                    }
                }
                $providerCategories = array_values(array_unique($providerCategories));
                $providerCategoryIds = array_values(array_unique($providerCategoryIds));

                $providerLocations = [];
                if (!empty($providerCategoryIds)) {
                    $locationsByCategory = $locationModel->getByProviderCategoryIds($providerCategoryIds);
                    foreach ($providerCategoryIds as $providerCategoryId) {
                        if (!empty($locationsByCategory[$providerCategoryId]) && is_array($locationsByCategory[$providerCategoryId])) {
                            $providerLocations = array_merge($providerLocations, $locationsByCategory[$providerCategoryId]);
                        }
                    }
                }

                $formattedLocation = '';
                if (!empty($providerLocations)) {
                    $formattedLocation = formatLocations($providerLocations);
                }

                // Get social media links
                $socialLinks = $socialModel->getByProviderId($provider['Provider_ID']);
                $formattedSocialLinks = [];
                
                foreach ($socialLinks as $social) {
                    $type = strtolower($social['Social_Type']);
                    $iconClass = getSocialMediaIconClass($type);
                    $color = getSocialMediaColor($type);

                    $formattedSocialLinks[] = [
                        'type' => $type,
                        'link' => $social['Social_Link'],
                        'icon_class' => $iconClass ?? 'fa-link',
                        'color' => $color ?? '#666666',
                        'name' => getSocialMediaName($type) ?? $social['Social_Type']
                    ];
                }

                // Format provider data
                $provider['skills'] = $providerSkills;
                $provider['categories'] = $providerCategories;
                $provider['social_links'] = $formattedSocialLinks;
                $provider['avatar'] = $provider['Profile_Picture'];
                $provider['formatted_location'] = $formattedLocation;
                $providerRatingPercentage = isset($provider['avg_rating']) ? (float) $provider['avg_rating'] : 0.0;
                $provider['rating'] = round(max(0.0, min(5.0, $providerRatingPercentage / 20)), 1);
                $provider['total_earning_formatted'] = $provider['Total_Earning'] > 0 ? 'LKR ' . number_format($provider['Total_Earning'], 2) : 'LKR 0.00';

                $providers[] = $provider;
            }

            // Get total count for pagination
            $total = $providerModel->getActiveProviderCount($search);
            $totalPages = ceil($total / $limit);

            echo json_encode([
                'success' => true,
                'providers' => $providers,
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $totalPages,
                    'total_records' => $total,
                    'limit' => $limit
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // GET /providers/{provider_id}/services - Get services/projects of a provider
    public function getServices()
    {
        header('Content-Type: application/json');

        try {
            require_once __DIR__ . '/../models/ProviderCategoriesModel.php';

            $providerCategoriesModel = new ProviderCategoriesModel();

            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $limit;
            $providerId = isset($_GET['provider_id']) ? (int) $_GET['provider_id'] : 0;

            $filters = [
                'search' => trim((string) ($_GET['q'] ?? '')),
                'sort' => trim((string) ($_GET['sort'] ?? '')),
                'price_range' => trim((string) ($_GET['price_range'] ?? '')),
                'completion_range' => trim((string) ($_GET['completion_range'] ?? '')),
                'price_types' => trim((string) ($_GET['price_types'] ?? '')),
                'category_ids' => trim((string) ($_GET['category_ids'] ?? '')),
            ];

            $services = $providerCategoriesModel->getServicesForListing($providerId, $limit, $offset, $filters);
            $totalCount = $providerCategoriesModel->getServicesForListingCount($providerId, $filters);
            $serviceIds = array_values(array_filter(array_map(static function ($service) {
                return (int) ($service['Provider_Categories_ID'] ?? 0);
            }, $services)));

            $skillsByService = $providerCategoriesModel->getSkillsByProviderCategoryIds($serviceIds);
            $locationsByService = $providerCategoriesModel->getLocationsByProviderCategoryIds($serviceIds);
            $latestRequestStatuses = [];

            if (!empty($_SESSION['user_id'])) {
                $latestRequestStatuses = $providerCategoriesModel->getLatestRequestStatusesByServiceIds((int) $_SESSION['user_id'], $serviceIds);
            }

            foreach ($services as &$service) {
                $serviceId = (int) ($service['Provider_Categories_ID'] ?? 0);
                $service['skills'] = $skillsByService[$serviceId] ?? [];
                $service['locations'] = $locationsByService[$serviceId] ?? [];
                $service['price_display'] = isset($service['Default_Price']) && $service['Default_Price'] !== null && (float) $service['Default_Price'] > 0
                    ? 'LKR ' . number_format((float) $service['Default_Price'], 2)
                    : 'Contact for price';
                $service['Rating'] = isset($service['Rating']) ? (float) $service['Rating'] : 0.0;
                $service['Total_Earning'] = isset($service['Total_Earning']) ? (float) $service['Total_Earning'] : 0.0;
                $providerRatingPercentage = isset($service['Provider_Rating']) ? (float) $service['Provider_Rating'] : 0.0;
                $service['provider_star_rating'] = round(max(0.0, min(5.0, $providerRatingPercentage / 20)), 1);
                $service['success_rate_display'] = (string) max(0, min(100, (int) round($service['Rating']))) . '%';
                $service['rate_type_display'] = trim((string) ($service['Price_Type'] ?? '')) !== ''
                    ? $service['Price_Type']
                    : 'N/A';
                $service['total_earning_formatted'] = $service['Total_Earning'] > 0
                    ? 'LKR ' . number_format($service['Total_Earning'], 2)
                    : 'LKR 0.00';
                $service['portfolio_link'] = trim((string) ($service['Portfolio_Link'] ?? ''));
                $service['show_links'] = !empty($service['portfolio_link']) ? [[
                    'label' => 'Portfolio',
                    'url' => $service['portfolio_link']
                ]] : [];
                $service['request_status'] = $latestRequestStatuses[$serviceId] ?? '';
                $service['formatted_location'] = !empty($service['locations']) ? formatLocations($service['locations']) : '';
                
            }

            echo json_encode([
                'success' => true,
                'services' => $services,
                'pagination' => [
                    'total_services' => $totalCount,
                    'limit' => $limit,
                    'current_page' => $page,
                    'total_pages' => (int) ceil($totalCount / max(1, $limit))
                ]
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // GET /provider/incoming-requests - Fetch incoming requests with pagination
    public function getIncomingRequests()
    {
        header('Content-Type: application/json');

        try {
            $this->ensureAuth();
            
            require_once __DIR__ . '/../models/PostModel.php';

            $providerId = (int) $_SESSION['user_id'];
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $limit = isset($_GET['limit']) ? max(1, min((int)$_GET['limit'], 100)) : 10;

            $postModel = new PostModel();
            $result = $postModel->getIncomingRequestsForProvider($providerId, $page, $limit);

            // Format the response
            $formattedData = [];
            foreach ($result['data'] as $request) {
                $priceType = trim($request['Price_Type']) ?: 'Fixed';
                $budgetDisplay = 'Rs. ' . number_format((float)$request['Requesting_Price'], 2) . ' (' . $priceType . ')';
                
                $formattedData[] = [
                    'Post_ID' => (int)$request['Post_ID'],
                    'Client_ID' => (int)$request['Client_ID'],
                    'title' => $request['Title'],
                    'description' => substr($request['Description'], 0, 150) . (strlen($request['Description']) > 150 ? '...' : ''),
                    'full_description' => $request['Description'],
                    'budget' => $request['Requesting_Price'],
                    'price_type' => $priceType,
                    'budget_display' => $budgetDisplay,
                    'timeline' => $request['Est_Date'],
                    'level' => $request['Level'],
                    'category' => $request['Category_Name'],
                    'client_name' => $request['Client_Name'],
                    'request_type' => ucfirst(strtolower($request['Post_Type'] ?? 'direct')),
                    'client_avatar' => $request['Profile_Picture'] ? BASE_URL . $request['Profile_Picture'] : BASE_URL . '/assets/img/default-avatar.jpg',
                    'posted_date' => date('M d, Y', strtotime($request['Created_At'])),
                    'posted_date_relative' => $this->getTimeAgo($request['Created_At'])
                ];
            }

            echo json_encode([
                'success' => true,
                'data' => $formattedData,
                'pagination' => [
                    'current_page' => $result['current_page'],
                    'total_pages' => $result['pages'],
                    'total_records' => $result['total'],
                    'limit' => $limit
                ]
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get time difference in human-readable format
     */
    private function getTimeAgo(string $datetime): string
    {
        $time = strtotime($datetime);
        $current = time();
        $diff = $current - $time;

        if ($diff < 60) {
            return 'just now';
        } elseif ($diff < 3600) {
            $mins = floor($diff / 60);
            return $mins . ' minute' . ($mins > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } else {
            return date('M d, Y', $time);
        }
    }

    // POST /provider/reject-request - Reject an incoming request
    public function rejectRequest()
    {
        header('Content-Type: application/json');

        try {
            $this->ensureAuth();

            $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
            $reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';

            if ($postId <= 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid post ID']);
                return;
            }

            if (empty($reason)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Reason is required']);
                return;
            }

            require_once __DIR__ . '/../models/PostModel.php';

            $postModel = new PostModel();
            $success = $postModel->changePostRequestStatus($postId, 'rejected', $reason);

            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Request rejected successfully'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to reject request'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // GET /provider/ongoing-projects - Fetch ongoing projects for the provider
    public function getOngoingProjects()
    {
        header('Content-Type: application/json');

        try {
            $this->ensureAuth();

            require_once __DIR__ . '/../models/ProjectModel.php';

            $providerId = (int) $_SESSION['user_id'];
            $page  = max(1, (int) ($_GET['page']  ?? 1));
            $limit = max(1, min((int) ($_GET['limit'] ?? 10), 100));

            $projectModel = new ProjectModel();
            $result = $projectModel->getOngoingProjectsForProvider($providerId, $page, $limit);

            $formattedData = [];
            foreach ($result['data'] as $row) {
                $priceType     = trim($row['Price_Type']) ?: 'Fixed';
                $budgetDisplay = 'Rs. ' . number_format((float) $row['Requesting_Price'], 2) . ' (' . $priceType . ')';

                $formattedData[] = [
                    'Project_ID'       => (int) $row['Project_ID'],
                    'Post_ID'          => (int) $row['Post_ID'],
                    'Client_ID'        => (int) $row['Client_ID'],
                    'title'            => $row['Title'],
                    'description'      => mb_substr($row['Description'], 0, 150) . (mb_strlen($row['Description']) > 150 ? '...' : ''),
                    'full_description' => $row['Description'],
                    'budget'           => $row['Requesting_Price'],
                    'price_type'       => $priceType,
                    'budget_display'   => $budgetDisplay,
                    'timeline'         => $row['Est_Date'] ?? '—',
                    'level'            => $row['Level'] ?? '—',
                    'category'         => $row['Category_Name'] ?? '—',
                    'post_type'        => ucfirst(strtolower($row['Post_Type'] ?? 'direct')),
                    'client_name'      => $row['Client_Name'] ?? 'Unknown',
                    'client_avatar'    => !empty($row['Profile_Picture'])
                        ? BASE_URL . $row['Profile_Picture']
                        : BASE_URL . '/assets/img/default-avatar.jpg',
                    'started_date'     => $row['Started_At'] ? date('M d, Y', strtotime($row['Started_At'])) : '—',
                    'progress'         => (int) $row['Progress'],
                ];
            }

            echo json_encode([
                'success'    => true,
                'data'       => $formattedData,
                'pagination' => [
                    'current_page'  => $result['current_page'],
                    'total_pages'   => $result['pages'],
                    'total_records' => $result['total'],
                    'limit'         => $limit,
                ],
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // POST /provider/accept-request - Accept an incoming request
    public function acceptRequest()
    {
        header('Content-Type: application/json');

        try {
            $this->ensureAuth();

            $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;

            if ($postId <= 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid post ID']);
                return;
            }

            require_once __DIR__ . '/../models/PostModel.php';

            $postModel = new PostModel();
            $success = $postModel->changePostRequestStatus($postId, 'accepted');

            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Request accepted successfully'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to accept request'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}