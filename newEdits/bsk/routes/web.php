<?php
use Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ClientController;
use App\Controllers\ProviderController;

/** @var Router $router */

$router->get('/', [HomeController::class, 'index']);

// Auth
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->any('/logout', [AuthController::class, 'logout']);

// Client
$router->get('/client/dashboard', [ClientController::class, 'dashboard']);
$router->get('/client/posts', [ClientController::class, 'posts']);
$router->get('/client/jobs', [ClientController::class, 'jobs']);
$router->get('/client/payments', [ClientController::class, 'payments']);
$router->get('/client/messages', [ClientController::class, 'messages']);
$router->get('/client/profile', [ClientController::class, 'profile']);
$router->get('/client/providers', [ClientController::class, 'providers']);
$router->get('/client/notifications', [ClientController::class, 'notifications']);
$router->get('/api/client/posts', [ClientController::class, 'listPosts']);
$router->post('/api/client/posts/create', [ClientController::class, 'createPost']);
$router->post('/api/client/posts/update', [ClientController::class, 'updatePost']);
$router->post('/api/client/posts/delete', [ClientController::class, 'deletePost']);
$router->get('/api/client/payments', [ClientController::class, 'listPayments']);
$router->post('/api/client/messages/send', [ClientController::class, 'sendMessage']);

// Provider
$router->get('/provider/dashboard', [ProviderController::class, 'dashboard']);
$router->get('/provider/posts', [ProviderController::class, 'posts']);
$router->get('/provider/job-requests', [ProviderController::class, 'jobRequests']);
$router->get('/provider/jobs', [ProviderController::class, 'jobs']);
$router->get('/provider/earnings', [ProviderController::class, 'earnings']);
$router->get('/provider/messages', [ProviderController::class, 'messages']);
$router->get('/provider/profile', [ProviderController::class, 'profile']);
$router->get('/api/provider/posts', [ProviderController::class, 'browsePosts']);
$router->post('/api/provider/bids/place', [ProviderController::class, 'placeBid']);
$router->post('/api/provider/messages/send', [ProviderController::class, 'sendMessage']);


