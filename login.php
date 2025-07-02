<?php
session_start();
$conn = new mysqli("localhost", "root", "", "blog", 3307);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Logout success message
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    echo "<p style='background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; font-weight: bold;'>
            ✅ Logout successful. Please log in again.
          </p>";
}

// ✅ Registration success message
if (isset($_GET['registered']) && $_GET['registered'] == 1) {
    echo "<p style='background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; font-weight: bold;'>
            ✅ Registration successful! Please log in below.
          </p>";
}

$loginSuccess = false;

// ✅ Handle login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username'];
            $loginSuccess = true;
            echo "<p style='background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; font-weight: bold;'>
                    ✅ Logged in successfully as <strong>$username</strong>.
                  </p>";
        } else {
            echo "<p style='color: red;'>❌ Incorrect password!</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Username not found!</p>";
    }
}
?>

<!-- 🎨 Styling -->
<style>
    button:hover {
        background-color: #372fa2;
        cursor: pointer;
        transition: 0.3s ease;
    }
    input[type="text"],
    input[type="password"] {
        padding: 8px;
        width: 250px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
</style>

<h2>Login</h2>

<?php if (!$loginSuccess): ?>
<form method="POST">
  <input type="text" name="username" placeholder="Username" required autofocus><br><br>

  <div style="position: relative; display: inline-block;">
    <input type="password" name="password" id="password" placeholder="Password" required>
    <span onclick="togglePassword()" style="position: absolute; right: 10px; top: 8px; cursor: pointer;">👁️</span>
  </div>
  <br><br>

  <button type="submit" name="login" style="padding: 8px 16px; background-color: #1a1a8a; color: white; border: none; border-radius: 5px;">
    🔐 Login
  </button>
</form>
<p>Don't have an account? <a href="register.php">Register here</a></p>

<?php else: ?>
<!-- ✅ After successful login -->
<br>
<!-- After successful login -->
<div style="margin-top: 20px;">
  <a href="index.php" style="padding: 8px 16px; background-color: #5D3FD3; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; margin-right: 10px;">
    🚀 Go to Dashboard
  </a>
  <a href="logout.php" style="padding: 8px 16px; background-color: #333333; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">
    🔓 Logout
  </a>
</div>

<?php endif; ?>

<!-- 👁️ JS -->
<script>
function togglePassword() {
  const pw = document.getElementById("password");
  pw.type = pw.type === "password" ? "text" : "password";
}
</script>
