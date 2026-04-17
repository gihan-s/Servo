<?php

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
            require_once __DIR__ . '/../../helpers/socialmedia.php';

            $providerModel = new ProviderModel();
            $socialModel = new ProviderSocialModel();
            $categoryModel = new CategoryModel();

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
                foreach ($providerCategoriesRaw as $categoryRow) {
                    $categoryName = trim((string) ($categoryRow['Category_Type'] ?? ''));
                    if ($categoryName !== '') {
                        $providerCategories[] = $categoryName;
                    }
                }
                $providerCategories = array_values(array_unique($providerCategories));

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
            $ongoingServiceIds = [];

            if (!empty($_SESSION['user_id'])) {
                $ongoingServiceIds = $providerCategoriesModel->getOngoingRequestServiceIds((int) $_SESSION['user_id'], $serviceIds);
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
                $service['request_status'] = in_array($serviceId, $ongoingServiceIds, true) ? 'ongoing' : '';
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
}