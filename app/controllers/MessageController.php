<?php

require_once __DIR__ . '/../models/MessageModel.php';

class MessageController extends BaseController
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

        $MessageModel = new MessageModel;
        $Conversations = $MessageModel->getAllConversations($userId, $role);
        // echo "<pre>";
        // print_r(array_column($Conversations, "id"));
        // echo "</pre>";
        $MessageModel->updateMessageStatusByReceiver($userId, $role, "Delivered");

        if ($role === 'Client' || $role === 'Provider') {
            $viewFile = __DIR__ . '/../views/message/index.php';
        } else {
            http_response_code(403);
            echo "Invalid role";
            return;
        }

        include $viewFile;
    }

    public function getMessages()
    {

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        $MessageModel = new MessageModel;
        $Messages = $MessageModel->getMessagesByID($userId, $role, $_POST["User_ID"]);

        $MessageModel->updateMessageStatusBySenderAndReceiver($_POST["User_ID"], $userId, $role, "Read");

        foreach ($Messages as $key => $value) {
            $date = explode(" ", date("Y-m-d H:i", strtotime($value["Sent_At"])));
            $Messages[$key]["Date"] = $date[0];
            $Messages[$key]["Time"] = $date[1];
        }

        echo json_encode($Messages);
    }
}
