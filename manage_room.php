<?php
session_start();
$conn = new mysqli("localhost", "root", "mini", "beatbunk");

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

$owner_id = $_SESSION['user_id']; 

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query = $conn->prepare("
        SELECT rp.id, rp.user_id, rp.room_code, u.username
        FROM room_participants rp
        JOIN rooms r ON rp.room_code = r.room_code
        JOIN users u ON rp.user_id = u.id
        WHERE r.created_by = ? AND rp.approved = 0
    ");
    $query->bind_param("i", $owner_id);
    $query->execute();
    $result = $query->get_result();
    
    $requests = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode(['success' => true, 'requests' => $requests]);
    exit();
}

// Approve or deny requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_id = $_POST['request_id'];
    $action = $_POST['action']; // 'approve' or 'deny'

    if ($action === 'approve') {
        $update_query = $conn->prepare("UPDATE room_participants SET approved = 1 WHERE id = ?");
        $update_query->bind_param("i", $request_id);
    } elseif ($action === 'deny') {
        $update_query = $conn->prepare("DELETE FROM room_participants WHERE id = ?");
        $update_query->bind_param("i", $request_id);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit();
    }

    if ($update_query->execute()) {
        echo json_encode(['success' => true, 'message' => 'Request successfully handled']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to handle request']);
    }
    $update_query->close();
}
$conn->close();
?>
