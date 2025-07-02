<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "blog", 3307);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Blog</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">

<?php
// ✅ Show delete success message
if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
    echo "<p style='background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; font-weight: bold;'>✅ Post deleted successfully!</p>";
}
?>

<!-- Buttons -->
<p>
  <a href="logout.php" style="padding: 8px 16px; background-color: #333333; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; margin-right: 10px;">
    🚪 Logout
  </a>
  <a href="add_post.php" style="padding: 8px 16px; background-color: #5D3FD3; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">
    ➕ Add New Post
  </a>
</p>
<h1 style="color: #333;">Welcome to the Dashboard, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>
<p style="color: #555;">Here you can manage your blog posts.</p>

<hr>

<h2 style="color: #333;">📝 All Posts</h2>

<?php
$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div style='border: 1px solid #ccc; padding: 15px; border-radius: 8px; margin-bottom: 20px;'>";
        echo "<h3 style='margin-top: 0;'>" . htmlspecialchars($row['title']) . "</h3>";
        echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
        echo "<small>🗓️ Posted on: " . $row['created_at'] . "</small><br><br>";
        echo "<a href='edit_post.php?id=" . $row['id'] . "' style='color: #007bff;'>Edit</a> | ";
        echo "<a href='delete_post.php?id=" . $row['id'] . "' style='color: #dc3545;' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
        echo "</div>";
    }
} else {
    echo "<p>No posts yet!</p>";
}

$conn->close();
?>

</body>
</html>
