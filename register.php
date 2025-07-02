<?php
session_start();
$conn = new mysqli("localhost", "root", "", "blog", 3307);

$registration_successful = false;

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Handle registration
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // Check if username exists
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $message = "<p style='color: red;'>❌ Username already taken!</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed);
        if ($stmt->execute()) {
            $registration_successful = true;
            $message = "<p style='background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; font-weight: bold;'>
                            ✅ Registration successful! Welcome, $username.
                        </p>";
        } else {
            $message = "<p style='color: red;'>❌ Registration failed. Try again.</p>";
        }
    }
}
?>

<h2>Register</h2>
<?php
if (isset($message)) {
    echo $message;
}
?>

<form method="POST">
    <input type="text" name="username" placeholder="Enter username" required><br><br>
    <input type="password" name="password" placeholder="Enter password" required><br><br>
    <button type="submit" name="register" style="padding: 8px 16px; background-color: #5D3FD3; color: white; border: none; border-radius: 5px;">
        📝 Register
    </button>
    <?php if ($registration_successful): ?>
        <button type="button" onclick="window.location.href='login.php'" style="padding: 8px 16px; margin-left: 10px; background-color: #28a745; color: white; border: none; border-radius: 5px;">
            🔐 Login Now
        </button>
    <?php endif; ?>
</form>

<?php if (!$registration_successful): ?>
    <p>Already have an account? <a href="login.php">Login here</a></p>
<?php endif; ?>
