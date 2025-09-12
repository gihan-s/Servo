<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Post;
use App\Models\Bid;
use App\Models\Message;

class ProviderController extends Controller
{
    public function dashboard(): void
    {
        $this->view('provider/dashboard');
    }

    public function posts(): void { $this->view('provider/posts'); }
    public function jobRequests(): void { $this->view('provider/job_requests'); }
    public function jobs(): void { $this->view('provider/jobs'); }
    public function earnings(): void { $this->view('provider/earnings'); }
    public function messages(): void { $this->view('provider/messages'); }
    public function profile(): void { $this->view('provider/profile'); }

    public function browsePosts(): void
    {
        $cat = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
        $items = (new Post())->browseActive($cat);
        header('Content-Type: application/json');
        echo json_encode($items);
    }

    public function placeBid(): void
    {
        $bidId = (new Bid())->create([
            'Comment' => $_POST['comment'] ?? null,
            'Amount' => (float)($_POST['amount'] ?? 0),
            'Est_Date' => $_POST['est_date'] ?? null,
            'Status' => 'pending',
            'Post_ID' => (int)($_POST['post_id'] ?? 0),
        ]);
        header('Content-Type: application/json');
        echo json_encode(['bid_id' => $bidId]);
    }

    public function sendMessage(): void
    {
        session_start();
        $providerId = (int)($_SESSION['user_id'] ?? 0);
        $messageId = (new Message())->send([
            'Content' => $_POST['content'] ?? '',
            'Is_Client_To_Provider' => false,
            'Provider_ID' => $providerId,
            'Client_ID' => (int)($_POST['client_id'] ?? 0),
        ]);
        header('Content-Type: application/json');
        echo json_encode(['message_id' => $messageId]);
    }
}


