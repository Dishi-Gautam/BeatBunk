<?php
session_start();
$conn = new mysqli("localhost", "root", "mini", "beatbunk");

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}   

$user_id = $_SESSION['user_id']; // Logged-in user ID
// $room_code = $_POST['room_code'];

$url_components = parse_url($_SERVER["REQUEST_URI"]);
parse_str($url_components['query'], $params);

$room_code = $params['room_code'];

// Check if the room exists
$room_query = $conn->prepare("SELECT id FROM rooms WHERE room_code = ?");
$room_query->bind_param("s", $room_code);
$room_query->execute();
$room_query->store_result();

if ($room_query->num_rows > 0) {
    // Add a pending join request
    $insert_query = $conn->prepare("INSERT INTO room_participants (room_code, user_id, approved) VALUES (?, ?, 0)");
    $insert_query->bind_param("si", $room_code, $user_id);
    if ($insert_query->execute()) {
        echo json_encode([
            'success' => true, 
            'message' => 'Join request sent', 
        ]);
        // Redirect to the desired URL
        header('Location: http://127.0.0.1:5501/Music-player/youtubemusic/index.html');

        exit(); // Make sure to exit after redirection to stop script execution
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send request']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid room code']);
}

$room_query->close();
$conn->close();
?>
