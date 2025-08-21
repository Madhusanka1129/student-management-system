<?php
session_start();
include 'db_connection.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid credentials.";
        }
    } else {
        $error = "Invalid credentials.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Private Campus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-image: linear-gradient(rgba(139, 92, 246, 0.7), rgba(139, 92, 246, 0.7)), url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=1740&q=80');
            background-size: cover;
            background-position: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body>
    <?php include 'components/navbar.php'; ?>

    <div class="min-h-screen flex items-center justify-center">
        <form method="POST" class="bg-white bg-opacity-90 p-8 rounded-2xl shadow-2xl max-w-md w-full">
            <h2 class="text-2xl font-bold text-center text-violet-700 mb-6">Admin Login</h2>

            <?php if (isset($error)) : ?>
                <p class="text-red-600 text-sm mb-4 text-center"><?php echo $error; ?></p>
            <?php endif; ?>

            <label for="username" class="block text-gray-700 mb-1">Username</label>
            <input type="text" name="username" id="username" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500 mb-4" required>

            <label for="password" class="block text-gray-700 mb-1">Password</label>
            <input type="password" name="password" id="password" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500 mb-6" required>

            <button type="submit" class="w-full bg-gradient-to-r from-violet-500 to-violet-700 text-white font-semibold py-3 rounded-lg shadow-md hover:from-violet-600 hover:to-violet-800 transition duration-300">
                Login
            </button>

            <p class="text-center text-sm text-gray-600 mt-4">
                Not registered? 
                <a href="register.php" class="text-violet-600 hover:underline font-medium">Register here</a>
            </p>
        </form>
    </div>

    <?php include 'components/footer.php'; ?>
</body>
</html>

