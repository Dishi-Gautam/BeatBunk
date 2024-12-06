<?php
$conn = new mysqli("localhost", "root", "mini", "beatbunk");

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

$room_code = $_GET['room_code'];

$query = $conn->prepare("SELECT u.username FROM room_participants rp 
                        JOIN users u ON rp.user_id = u.id
                        WHERE rp.room_code = ? AND rp.approved = 1");
$query->bind_param("s", $room_code);
$query->execute();
$result = $query->get_result();

$participants = [];
while ($row = $result->fetch_assoc()) {
    $participants[] = $row;
}

echo json_encode(['success' => true, 'participants' => $participants]);

$query->close();
$conn->close();
?>
