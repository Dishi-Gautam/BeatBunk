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

// Fetch rooms from the database
$query = "SELECT * FROM rooms";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rooms - BeatBunk</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="room.css">
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
        
        <?php if ($result->num_rows > 0): ?>
            <ul>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li>
                        <h2><?php echo htmlspecialchars($row['room_name']); ?></h2>
                        <p><strong>Genre:</strong> <?php echo htmlspecialchars($row['genre']); ?></p>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                        <a href="join_room.php?room_id=<?php echo $row['id']; ?>">Join Room</a>
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
        <p>&copy; 2024 Beatbunk. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
