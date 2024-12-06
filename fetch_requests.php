<?php
session_start();
$conn = new mysqli("localhost", "root", "mini", "beatbunk");

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

$room_code = $_GET['room_code'];
$owner_id = $_SESSION['user_id']; // Current room owner's ID

// Check if the user owns the room
$room_query = $conn->prepare("SELECT id FROM rooms WHERE room_code = ? AND owner_id = ?");
$room_query->bind_param("si", $room_code, $owner_id);
$room_query->execute();
$room_query->store_result();

if ($room_query->num_rows > 0) {
    $request_query = $conn->prepare("SELECT rp.user_id, u.username FROM room_participants rp 
                                    JOIN users u ON rp.user_id = u.id
                                    WHERE rp.room_code = ? AND rp.approved = 0");
    $request_query->bind_param("s", $room_code);
    $request_query->execute();
    $result = $request_query->get_result();

    $requests = [];
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }

    echo json_encode(['success' => true, 'requests' => $requests]);
} else {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
}

$room_query->close();
$conn->close();
?>
