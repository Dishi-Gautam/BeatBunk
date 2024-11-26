<?php
session_start();

// Check if user is logged in by verifying session variables
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header("Location: login.html");
    exit();
}

// Get the username from the session
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BeatBunk - Welcome, <?php echo htmlspecialchars($username); ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <nav class="navbar">
    <img src="./assests/logo.png" id="beatbunk-logo">
    <ul class="nav-links">
      <li><a href="#home">Home</a></li>
      <li><a href="#about">About</a></li>
      <li><a href="room.php">Rooms</a></li>
      <li><a href="create_room.php">Create Room</a></li>
      <li><a href="profile.php">Profile</a></li>
      <li><a href="logout.php">Logout</a></li> <!-- Logout option -->
    </ul>
  </nav>
</header>

<section class="hero" id="home">
  <div class="hero-content">
    <h1>Welcome back, <?php echo htmlspecialchars($username); ?>!</h1>
    <p>Explore the ultimate music room experience with features that bring music lovers together.</p>
    <a href="#features" class="cta-button">Discover Features</a>
  </div>
</section>

<!-- Additional content for logged-in user -->
<div id="tag-line">
  <h2 class="tag-line">Join the beat, Find your bunk</h2>
</div>
<section id="features" class="features">
  <div class="feature" id="f1">
    <h2>Feature 1</h2>
    <p>Discover the best music rooms and <br>
      connect with fellow music lovers.</p>
  </div>
  
  <div class="feature" id="f2">
    <h2>Feature 2</h2>
    <p>Access playlists curated by users and
      <br> top DJs from around the world.</p>
  </div>
  
  <div class="feature">
    <h2>Feature 3</h2>
    <p>Create your own music room, invite friends, and 
      <br> start jamming together.</p>
  </div>
</section>

<!-- Footer Section Start -->
<footer class="footer">
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

<script src="script.js"></script>
</body>
</html>
