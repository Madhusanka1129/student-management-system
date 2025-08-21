<?php
session_start();
include 'db_connection.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username already exists
    $sql = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $error = "Username or email already exists.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $sql = "INSERT INTO users (email, username, password) VALUES ('$email', '$username', '$hashed_password')";
        if (mysqli_query($conn, $sql)) {
            $success = "Account created successfully!";
            // Optionally, log the user in automatically
            $_SESSION['username'] = $username;
            header("Location: dashboard.php"); // Redirect to dashboard or home page
            exit;
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-image: linear-gradient(rgba(139, 92, 246, 0.6), rgba(139, 92, 246, 0.6)),
            url('https://images.unsplash.com/photo-1571260899304-425eee4c7efc?auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body>
    <?php include 'components/navbar.php'; ?>

    <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <form method="POST" class="bg-white bg-opacity-90 p-8 rounded-2xl shadow-2xl max-w-md w-full">
            <h2 class="text-2xl font-bold text-center text-violet-700 mb-6">Create Account</h2>

            <?php if (isset($success)) : ?>
                <p class="text-green-600 text-sm mb-4 text-center"><?php echo $success; ?></p>
            <?php endif; ?>
            <?php if (isset($error)) : ?>
                <p class="text-red-600 text-sm mb-4 text-center"><?php echo $error; ?></p>
            <?php endif; ?>

            <input type="email" name="email" placeholder="Email" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 mb-4" required>
            <input type="text" name="username" placeholder="Username" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 mb-4" required>
            <input type="password" name="password" placeholder="Password" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 mb-6" required>

            <button type="submit" class="w-full bg-gradient-to-r from-violet-500 to-violet-700 text-white font-semibold py-3 rounded-lg hover:from-violet-600 hover:to-violet-800 transition duration-300 shadow-md">
                Register
            </button>

            <p class="text-center text-sm text-gray-600 mt-4">
                Already registered? 
                <a href="login.php" class="text-violet-600 hover:underline font-medium">Login here</a>
            </p>
        </form>
    </div>

    <?php include 'components/footer.php'; ?>
</body>
</html>

