<?php
$conn = new mysqli("localhost", "root", "", "blog", 3307);

$id = $_GET['id'];

if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("UPDATE posts SET title=?, content=? WHERE id=?");
    $stmt->bind_param("ssi", $title, $content, $id);
    $stmt->execute();

    echo "Post updated!";
}

$result = $conn->query("SELECT * FROM posts WHERE id=$id");
$row = $result->fetch_assoc();
?>

<form method="POST">
  <input type="text" name="title" value="<?= $row['title'] ?>" required><br>
  <textarea name="content" required><?= $row['content'] ?></textarea><br>
  <button type="submit" name="update">Update</button>
</form>
