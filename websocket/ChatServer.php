<?php

error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 0);


require_once __DIR__ . '/../app/controllers/LoginController.php';
require_once __DIR__ . '/../app/models/MessageModel.php';
require_once __DIR__ . '/../app/models/ClientModel.php';
require_once __DIR__ . '/../app/models/ProviderModel.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatServer implements MessageComponentInterface
{
    protected \SplObjectStorage $clients;
    protected $userData = [];
    protected $userConnections = []; // userKey => [conn1, conn2]

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        echo "WebSocket server started...\n";
    }

    private function getUserKey($userId, $role)
    {
        return $role . '_' . $userId;
    }

    private function getOppositeRole($role)
    {
        return $role === 'Provider' ? 'Client' : 'Provider';
    }

    private function getUserFirstName($userId, $role)
    {
        if ($role === 'Provider') {
            $model = new ProviderModel();
            $user = $model->getProviderById($userId);
        } else {
            $model = new ClientModel();
            $user = $model->getClientById($userId);
        }

        return $user['First_Name'] ?? null;
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

        $userKey = $this->getUserKey($userId, $userRole);

        $this->userData[$conn->resourceId] = [
            'User_ID' => $userId,
            'Role' => $userRole,
            'UserKey' => $userKey
        ];

        $this->clients->attach($conn);

        // ✅ Store connection
        $this->userConnections[$userKey][] = $conn;

        $Model = new MessageModel;

        // ✅ Mark online
        $Model->setUserOnline($userId, $userRole);

        // ✅ Send currently online users
        $relevantUsers = $Model->getConversationUserIds($userId, $userRole);

        foreach ($relevantUsers as $targetUserId) {

            $targetRole = $this->getOppositeRole($userRole);
            $targetKey = $this->getUserKey($targetUserId, $targetRole);

            if (isset($this->userConnections[$targetKey])) {
                $conn->send(json_encode([
                    'Type' => 'Presence',
                    'User_ID' => $targetUserId,
                    'Status' => 'Online',
                    'Role' => $targetRole,
                    'Onload' => true
                ]));
            }
        }

        // ✅ Notify others
        $this->broadcastPresenceToRelevant($userId, $userRole, 'Online');

        // ✅ Deliver pending messages
        $Model->updateMessageStatusByReceiver($userId, $userRole, "Delivered");

        echo "$userRole $userId connected\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        if (!$data) return;



        if (isset($data['Type']) && $data['Type'] === 'Ping') {
            $from->send(json_encode([
                'Type' => 'Pong',
                'Timestamp' => time()
            ]));
            return;
        }

        if (isset($data['Type']) && $data['Type'] === 'Seen') {

            $receiverId = $this->userData[$from->resourceId]['User_ID'];
            $receiverRole = $this->userData[$from->resourceId]['Role'];

            $senderId = (int)$data['From']; // person who sent messages

            $Model = new MessageModel;

            // ✅ Update DB → Delivered → Seen
            $Model->updateMessageStatusBySenderAndReceiver(
                $senderId,
                $receiverId,
                $receiverRole,
                "Read"
            );

            // ✅ Notify sender if online
            $senderRole = $this->getOppositeRole($receiverRole);
            $senderKey = $this->getUserKey($senderId, $senderRole);

            if (isset($this->userConnections[$senderKey])) {
                foreach ($this->userConnections[$senderKey] as $conn) {
                    $conn->send(json_encode([
                        'Type' => 'Seen',
                        'From' => $receiverId
                    ]));
                }
            }

            return;
        }




        $senderId = $this->userData[$from->resourceId]['User_ID'];
        $senderRole = $this->userData[$from->resourceId]['Role'];

        $receiverId = (int)$data['To'];
        $receiverRole = $this->getOppositeRole($senderRole);

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
        $ReplyTo = isset($data['Reply_To']) ? (int)$data['Reply_To'] : null;

        $Model = new MessageModel;
        $MessageID = $Model->insertMessage($messageContent, $ClientToProvider, $ProviderID, $ClientID, $ReplyTo);

        $Status = 'Sent';

        $receiverKey = $this->getUserKey($receiverId, $receiverRole);

        if (isset($this->userConnections[$receiverKey])) {
            $senderFirstName = $this->getUserFirstName($senderId, $senderRole);
            foreach ($this->userConnections[$receiverKey] as $client) {
                if ($client !== $from) {
                    $client->send(json_encode([
                        'Type' => 'New Message',
                        'From' => $senderId,
                        'From_Name' => $senderFirstName,
                        'Content' => $messageContent,
                        'Reply_To' => $ReplyTo,
                        'DB_ID' => $MessageID
                    ]));
                }
            }

            $Model->updateMessageStatus($MessageID, "Delivered");
            $Status = 'Delivered';
        }

        $from->send(json_encode([
            'Type' => 'Ack',
            'Message_ID' => $data["Message_ID"],
            'Status' => $Status,
            'DB_ID' => $MessageID
        ]));

    }

    public function onClose(ConnectionInterface $conn)
    {
        $user = $this->userData[$conn->resourceId] ?? null;

        if ($user) {
            $userId = $user['User_ID'];
            $role = $user['Role'];
            $userKey = $user['UserKey'];

            if (isset($this->userConnections[$userKey])) {

                // ✅ Remove this connection
                $this->userConnections[$userKey] = array_values(array_filter(
                    $this->userConnections[$userKey],
                    fn($c) => $c->resourceId !== $conn->resourceId
                ));

                // ✅ If no more connections → update DB and broadcast offline
                if (empty($this->userConnections[$userKey])) {
                    unset($this->userConnections[$userKey]);

                    $Model = new MessageModel;
                    $Model->setUserOffline($userId, $role);
                    $this->broadcastPresenceToRelevant($userId, $role, 'Offline');

                    echo "User $userId ($role) OFFLINE\n";
                }
            }
        }

        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }

    private function broadcastPresenceToRelevant($userId, $role, $status)
    {
        $Model = new MessageModel;
        $relevantUsers = $Model->getConversationUserIds($userId, $role);

        $targetRole = $this->getOppositeRole($role);

        foreach ($relevantUsers as $targetUserId) {

            $targetKey = $this->getUserKey($targetUserId, $targetRole);

            if (!isset($this->userConnections[$targetKey])) continue;

            foreach ($this->userConnections[$targetKey] as $conn) {
                $conn->send(json_encode([
                    'Type' => 'Presence',
                    'User_ID' => $userId,
                    'Status' => $status,
                    'Role' => $role
                ]));
            }
        }
    }
}
