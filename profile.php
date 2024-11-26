<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "mini", "beatbunk");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_msg = "";
$success_msg = "";

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    if (empty($username) || empty($email)) {
        $error_msg = "All fields are required.";
    } else {
        $update_query = "UPDATE users SET username = ?, email = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssi", $username, $email, $user_id);
        if ($update_stmt->execute()) {
            $success_msg = "Profile updated successfully.";
            $_SESSION['username'] = $username;
        } else {
            $error_msg = "Failed to update profile.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BeatBunk - Profile</title>
  <link rel="stylesheet" href="profile.css">
  <link rel="stylesheet" href="style.css">
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

<section class="profile">
  <div class="profile-content">
    <h1>Your Profile</h1>
    <?php if ($error_msg): ?>
      <p class="error-msg"><?php echo $error_msg; ?></p>
    <?php endif; ?>
    <?php if ($success_msg): ?>
      <p class="success-msg"><?php echo $success_msg; ?></p>
    <?php endif; ?>
    <form method="POST" action="profile.php">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

      <button type="submit">Update Profile</button>
    </form>
  </div>
</section>

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
</body>
</html>
