<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Post;
use App\Models\Payment;
use App\Models\Message;

class ClientController extends Controller
{
    public function dashboard(): void
    {
        $this->view('client/Dashboard/index');
    }

    public function posts(): void { $this->view('client/Posts/index'); }
    public function jobs(): void { $this->view('client/Projects/index'); }
    public function payments(): void { $this->view('client/Payments/index'); }
    public function messages(): void { $this->view('client/Messages/index'); }
    public function profile(): void { $this->view('client/Profile/index'); }
    public function providers(): void { $this->view('client/Providers/index'); }
    public function notifications(): void { $this->view('client/Notification/index'); }

    // JSON endpoints
    public function listPosts(): void
    {
        session_start();
        $clientId = (int)($_SESSION['user_id'] ?? 0);
        $status = $_GET['status'] ?? null;
        $posts = (new Post())->listForClient($clientId, $status);
        header('Content-Type: application/json');
        echo json_encode($posts);
    }

    public function createPost(): void
    {
        session_start();
        $clientId = (int)($_SESSION['user_id'] ?? 0);
        $postId = (new Post())->create([
            'Category_ID' => (int)($_POST['category_id'] ?? 0),
            'Post_Type' => $_POST['type'] ?? 'public',
            'Post_Status' => $_POST['status'] ?? 'draft',
            'Client_ID' => $clientId,
            'Title' => $_POST['title'] ?? null,
            'Description' => $_POST['description'] ?? null,
            'Requesting_Price' => isset($_POST['price']) ? (float)$_POST['price'] : null,
        ]);
        header('Content-Type: application/json');
        echo json_encode(['post_id' => $postId]);
    }

    public function updatePost(): void
    {
        $postId = (int)($_POST['post_id'] ?? 0);
        $ok = (new Post())->update($postId, [
            'Category_ID' => (int)($_POST['category_id'] ?? 0),
            'Post_Type' => $_POST['type'] ?? 'public',
            'Post_Status' => $_POST['status'] ?? 'draft',
            'Provider_ID' => isset($_POST['provider_id']) ? (int)$_POST['provider_id'] : null,
            'Title' => $_POST['title'] ?? null,
            'Description' => $_POST['description'] ?? null,
            'Requesting_Price' => isset($_POST['price']) ? (float)$_POST['price'] : null,
        ]);
        header('Content-Type: application/json');
        echo json_encode(['success' => $ok]);
    }

    public function deletePost(): void
    {
        $postId = (int)($_POST['post_id'] ?? 0);
        $ok = (new Post())->delete($postId);
        header('Content-Type: application/json');
        echo json_encode(['success' => $ok]);
    }

    public function listPayments(): void
    {
        $projectId = (int)($_GET['project_id'] ?? 0);
        $items = (new Payment())->listForProject($projectId);
        header('Content-Type: application/json');
        echo json_encode($items);
    }

    public function sendMessage(): void
    {
        session_start();
        $clientId = (int)($_SESSION['user_id'] ?? 0);
        $messageId = (new Message())->send([
            'Content' => $_POST['content'] ?? '',
            'Is_Client_To_Provider' => true,
            'Provider_ID' => (int)($_POST['provider_id'] ?? 0),
            'Client_ID' => $clientId,
        ]);
        header('Content-Type: application/json');
        echo json_encode(['message_id' => $messageId]);
    }
}


