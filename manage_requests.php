<?php
session_start();
$conn = new mysqli("localhost", "root", "mini", "beatbunk");

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$owner_id = $_SESSION['user_id'];

// Debug: Check if user is the room owner
file_put_contents('debug.log', "Owner ID: $owner_id\n", FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Fetch join requests for rooms owned by the user
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
    file_put_contents('debug.log', "Fetched Requests: " . json_encode($requests) . "\n", FILE_APPEND);

    if ($requests) {
        echo json_encode(['success' => true, 'requests' => $requests]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No pending requests']);
    }

    exit();
}

$conn->close();
?>
