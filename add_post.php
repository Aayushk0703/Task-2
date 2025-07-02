<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>

<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "blog",3307);

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $content);
    $stmt->execute();

    echo "Post added successfully!";
}
?>

<form method="POST">
  <input type="text" name="title" placeholder="Title" required><br>
  <textarea name="content" placeholder="Content" required></textarea><br>
  <button type="submit" name="submit">Add Post</button>
</form>
