<?php

require_once __DIR__ . '/../models/MessageModel.php';
require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/ProviderModel.php';

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
            $this->notFound();
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

    public function startConversation()
    {
        $Client_ID = $_POST['Client_ID'];
        $Provider_ID = $_POST['Provider_ID'];

        $Model = new MessageModel;
        $ProviderModel = new ProviderModel;
        $ClientModel = new ClientModel;

        $Conversation_ID = $Model->createOrGetConversation($Provider_ID, $Client_ID);

        if ($_SESSION['role'] === 'Client') {
            $otherUser = $ProviderModel->getProviderById($Provider_ID);
        } else {
            $otherUser = $ClientModel->getClientById($Client_ID);
        }

        echo json_encode([
            'Conversation_ID' => $Conversation_ID,
            'first_name' => $otherUser["First_Name"],
            'last_name' => $otherUser["Last_Name"],
            'profile_picture' => $otherUser["Profile_Picture"],
        ]);
    }


    public function getUser()
    {
        // 🔒 Basic validation
        if (!isset($_GET['id'])) {
            echo json_encode(['error' => 'Missing user id']);
            return;
        }

        $id = (int) $_GET['id'];

        $ProviderModel = new ProviderModel;
        $ClientModel = new ClientModel;
        if ($_SESSION['role'] === 'Client') {
            $user = $ProviderModel->getProviderById($id);
        } else {
            $user = $ClientModel->getClientById($id);
        }

        if (!$user) {
            echo json_encode(['error' => 'User not found']);
            return;
        }

         echo json_encode([
            'id' => (isset($user["Provider_ID"])) ? $user["Provider_ID"] : $user["Client_ID"],
            'first_name' => $user["First_Name"],
            'last_name' => $user["Last_Name"],
            'profile_picture' => $user["Profile_Picture"],
        ]);
    }
}
