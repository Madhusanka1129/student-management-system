<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Management System - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-image: url('https://images.unsplash.com/photo-1571260899304-425eee4c7efc');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
        }
        .bg-overlay {
            background-color: rgba(0, 0, 0, 0.6);
        }
    </style>
</head>
<body class="text-white font-sans">

    <!-- Navbar -->
    <?php include 'components/navbar.php'; ?>

    <!-- Hero Section -->
    <div class="bg-overlay min-h-screen flex flex-col justify-center items-center text-center px-6">
        <h1 class="text-4xl md:text-6xl font-bold mb-4 text-violet-300">Welcome to the Student Management System</h1>
        <p class="text-lg md:text-2xl mb-6 text-violet-100">Manage student data efficiently with our powerful and easy-to-use platform.</p>
        <a href="register.php" class="bg-violet-600 hover:bg-violet-700 text-white font-semibold px-6 py-3 rounded-lg text-lg transition">
            Register NOW
        </a>
    </div>

    <!-- About Section -->
    <div class="bg-white text-gray-800 py-16 px-6 md:px-20">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-10 text-violet-700">About the System</h2>
        <p class="max-w-4xl mx-auto text-lg text-center text-gray-700">
            This student management system allows administrators to add, update, delete, and search student records with ease. Built with PHP and styled using Tailwind CSS, it ensures fast performance and a clean interface.
        </p>
    </div>

    <!-- Features Section -->
    <div class="bg-gradient-to-r from-violet-100 to-violet-50 py-16 px-6 md:px-20">
        <h2 class="text-3xl md:text-4xl font-bold text-center text-violet-800 mb-10">Features</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-semibold text-violet-600 mb-3">Add Students</h3>
                <p>Add new students with complete information including name, NIC, course, and contact details.</p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-semibold text-violet-600 mb-3">Update & Delete</h3>
                <p>Quickly update student records or remove them as needed—all in one dashboard.</p>
            </div>
            <div class="bg-white  rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-semibold text-violet-600 mb-3">Search</h3>
                <p >Find students instantly by NIC or name using the built-in search functionality.</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'components/footer.php'; ?>

</body>
</html>
