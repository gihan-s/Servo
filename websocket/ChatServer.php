<?php

require_once __DIR__ . '/../app/controllers/LoginController.php';
require_once __DIR__ . '/../app/models/MessageModel.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;


class ChatServer implements MessageComponentInterface
{
    protected \SplObjectStorage $clients;
    protected $userData = [];

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        echo "WebSocket server started...\n";
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $query = $conn->httpRequest->getUri()->getQuery();
        parse_str($query, $params);

        if (empty($params['token'])) {
            $conn->close();
            return;
        }

        $verifiedUser = LoginController::verifyAuthToken($params['token']);
        $userId = $verifiedUser["User_ID"];
        $userRole = $verifiedUser["Role"];
        if (!$userId) {
            $conn->close();
            return;
        }

        // $conn->userId = $userId;
        $this->userData[$conn->resourceId] = ['User_ID' => $userId, 'Role' => $userRole]; // safe

        $this->clients->attach($conn);

        echo "$userRole $userId connected\n";
    }

   public function onMessage(ConnectionInterface $from, $msg)
{
    $data = json_decode($msg, true);

    if (!$data) return;

    // ✅ Get the real sender ID from the connection
    $senderId = $this->userData[$from->resourceId]['User_ID'];
    $senderRole = $this->userData[$from->resourceId]['Role'];
    $receiverId = (int) $data['To'];
    if ($senderRole === 'Provider') {
        $ClientToProvider = 0;
        $ProviderID = $senderId;
        $ClientID = $receiverId;
    } else {
        $ClientToProvider = 1;
        $ProviderID = $receiverId;
        $ClientID = $senderId;
    }
    
    $messageContent = $data['Content'];

    // Save in DB
    $Model = new MessageModel;
    $MessageID = $Model->insertMessage($messageContent, $ClientToProvider, $ProviderID, $ClientID);

    $Status = 'Sent';
    // Broadcast to receiver if connected
    foreach ($this->clients as $client) {
        $clientId = $this->userData[$client->resourceId]['User_ID'] ?? null;

        if ($clientId === $receiverId) {
            $client->send(json_encode([
                'Type' => 'New Message',
                'From' => $senderId,
                'Content' => $messageContent
            ]));

            $Model->updateMessageStatus($MessageID, "Delivered");
            $Status = 'Delivered';
        }
    }

    $from->send(json_encode([
        'Type' => 'Ack',
        'Message_ID' => $data["Message_ID"],
        'Status' => $Status
    ]));

    echo "Message from $senderId to $receiverId saved\n";
}


    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}
