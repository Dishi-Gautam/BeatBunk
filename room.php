<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "mini", "beatbunk");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch rooms from the database, including the user_id of the creator
$query = "SELECT id, user_id, room_name, genre, description, room_code FROM rooms ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Available Rooms - BeatBunk</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="room.css">
    <style>
        /* Style for highlighting rooms created by the logged-in user */
        .room-card.my-room {
            border: 2px solid #4CAF50; /* Highlight with green border */
            background-color: #f9fff0; /* Subtle green background */
        }

        .manage-requests-button {
            background-color: #4CAF50; /* Green background */
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }

        .manage-requests-button:hover {
            background-color: #45a049; /* Darker green on hover */
        }

        .join-room-button {
            background-color: #2196F3; /* Blue background */
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }

        .join-room-button:hover {
            background-color: #0b7dda; /* Darker blue on hover */
        }
    </style>
</head>

<body>

<header>
    <nav class="navbar">
        <img src="./assets/logo.png" id="beatbunk-logo">
        <ul class="nav-links">
            <li><a href="homepage.php">Home</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<section class="rooms-list">
    <div class="form-container">
        <h1>Available Rooms</h1>

        <?php if ($result && $result->num_rows > 0): ?>
            <ul class="room-list">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <!-- Check if the room is created by the logged-in user -->
                    <li class="room-card <?php echo $row['user_id'] == $_SESSION['user_id'] ? 'my-room' : ''; ?>">
                        <h2><?php echo htmlspecialchars($row['room_name']); ?></h2>
                        <p><strong>Genre:</strong> <?php echo htmlspecialchars($row['genre']); ?></p>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                        <p><strong>Room Code:</strong> <?php echo htmlspecialchars($row['room_code']); ?></p>
                        
                        <?php if ($row['user_id'] == $_SESSION['user_id']): ?>
                            <!-- Add Manage Requests button for rooms owned by the user -->
                            <a href="manage_requests.php?room_code=<?php echo urlencode($row['room_code']); ?>" class="manage-requests-button">Manage Requests</a>
                        <?php else: ?>
                            <!-- Add Join Room button for other rooms -->
                            <a href="join_room.php?room_code=<?php echo urlencode($row['room_code']); ?>" class="join-room-button">Join Room</a>
                        <?php endif; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No rooms available at the moment.</p>
        <?php endif; ?>
    </div>
</section>

<footer>
    <div class="footer-content">
        <div class="footer-logo">
            <a href="#" class="logo">Beatbunk</a>
        </div>
        <ul class="footer-links">
            <li><a href="#about">About</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 BeatBunk. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>

<?php
$conn->close();
?> 
