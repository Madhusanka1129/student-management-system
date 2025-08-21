<?php
include 'db_connection.php';

$action = $_GET['action'] ?? 'view'; // default to 'view'

$editMode = false;
$editData = [];
$success = '';
$error = '';

// Handle Create or Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action_type'])) {
    // Collect and sanitize form data
    $name = $_POST['name'] ?? '';
    $nic = $_POST['nic'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $address = $_POST['address'] ?? '';
    $age = $_POST['age'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';
    $email = $_POST['email'] ?? '';
    $course = $_POST['course'] ?? '';

    if ($_POST['action_type'] === 'update' && !empty($_POST['edit_nic'])) {
        // Update student
        $edit_nic = $_POST['edit_nic'];
        $sql = "UPDATE students SET 
                    name='$name', 
                    gender='$gender', 
                    address='$address', 
                    age='$age',
                    contact_number='$contact_number', 
                    email='$email', 
                    course='$course' 
                WHERE nic='$edit_nic'";
        if (mysqli_query($conn, $sql)) {
            $success = "Student updated successfully.";
        } else {
            $error = "Error updating student: " . mysqli_error($conn);
        }
    } else {
        // Create new student
        if (!empty($nic)) {
            $sql = "INSERT INTO students (name, nic, gender, address, contact_number, email, course,age) 
                    VALUES ('$name', '$nic', '$gender', '$address', '$contact_number', '$email', '$course','$age')";
            if (mysqli_query($conn, $sql)) {
                $success = "Student added successfully.";
            } else {
                $error = "Error adding student: " . mysqli_error($conn);
            }
        } else {
            $error = "NIC is required to add a new student.";
        }
    }
}

// Delete student
if (isset($_GET['delete'])) {
    $nic = $_GET['delete'];
    $sql = "DELETE FROM students WHERE nic='$nic'";
    if (mysqli_query($conn, $sql)) {
        $success = "Student deleted successfully.";
    } else {
        $error = "Error deleting student: " . mysqli_error($conn);
    }
}

// Load data for editing
if (isset($_GET['edit'])) {
    $edit_nic = $_GET['edit'];
    $query = mysqli_query($conn, "SELECT * FROM students WHERE nic='$edit_nic'");
    if ($query && mysqli_num_rows($query) > 0) {
        $editData = mysqli_fetch_assoc($query);
        $editMode = true;
        $action = 'update';
    }
}

// Handle Search
$searchResults = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $search_nic = $_POST['search_nic'] ?? '';
    $searchResults = mysqli_query($conn, "SELECT * FROM students WHERE nic LIKE '%$search_nic%' OR name LIKE '%$search_nic%'");
} else {
    $searchResults = mysqli_query($conn, "SELECT * FROM students");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    body {
        background-image: url('https://images.unsplash.com/photo-1601597111021-15d121c9d3d6');
        background-size: cover;
        background-attachment: fixed;
        background-position: center;
    }
    .bg-glass {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
    }
</style>
</head>
<body class="min-h-screen font-sans">

<?php include 'components/navbar.php'; ?>

<div class="flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-glass p-4 h-screen sticky top-0">
        <h2 class="text-xl font-bold mb-6 text-center">Student Panel</h2>
        <nav class="space-y-4">
        <a href="?action=create" class="block p-2 rounded bg-violet-600 text-white hover:bg-violet-700 text-center">Create</a>
<a href="?action=view" class="block p-2 rounded bg-violet-600 text-white hover:bg-violet-700 text-center">View</a>
<a href="?action=update" class="block p-2 rounded bg-violet-600 text-white hover:bg-violet-700 text-center">Update</a>
<a href="?action=delete" class="block p-2 rounded bg-violet-600 text-white hover:bg-violet-700 text-center">Delete</a></nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">

        <!-- Permanent Search -->
        <div class="bg-glass p-4 mb-6 rounded-lg">
            <form method="POST" class="flex flex-col md:flex-row gap-4">
                <input type="text" name="search_nic" placeholder="Search by NIC or Name" class="flex-grow p-2 border rounded" required>
                <button type="submit" name="search" class="bg-violet-600 hover:bg-violet-700 text-white px-4 py-2 rounded">Search</button>


            </form>
        </div>

        <!-- Display Alerts -->
        <?php if (isset($success)) echo "<p class='text-green-700 font-semibold mb-4'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='text-red-700 font-semibold mb-4'>$error</p>"; ?>

        <!-- Content -->
        <?php if ($action === 'create' || ($action === 'update' && $editMode)) { ?>
            <!-- Form for Create/Update -->
            <div class="bg-glass p-6 rounded shadow-lg">
                <h2 class="text-2xl font-bold mb-4"><?= $editMode ? 'Update Student' : 'Add New Student' ?></h2>
                <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="hidden" name="edit_nic" value="<?= $editMode ? $editData['nic'] : '' ?>">
                    <input type="hidden" name="action_type" value="<?= $editMode ? 'update' : 'create' ?>">

                    <input type="text" name="name" placeholder="Name" value="<?= $editData['name'] ?? '' ?>" class="p-2 border rounded" required>
                    <input type="text" name="nic" placeholder="NIC" value="<?= $editData['nic'] ?? '' ?>" class="p-2 border rounded" <?= $editMode ? 'readonly' : 'required' ?>>
                    <input type="number" name="age" placeholder="Age" value="<?= $editData['age'] ?? '' ?>" class="p-2 border rounded" <?= $editMode ? 'readonly' : 'required' ?>>

                    <select name="gender" class="p-2 border rounded">
                        <option value="Male" <?= ($editData['gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= ($editData['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>

                    <input type="text" name="contact_number" placeholder="Contact Number" value="<?= $editData['contact_number'] ?? '' ?>" class="p-2 border rounded" required>
                    <input type="email" name="email" placeholder="Email" value="<?= $editData['email'] ?? '' ?>" class="p-2 border rounded" required>
                    <input type="text" name="course" placeholder="Course" value="<?= $editData['course'] ?? '' ?>" class="p-2 border rounded" required>
                    <textarea name="address" placeholder="Address" class="p-2 border rounded col-span-1 md:col-span-2" required><?= $editData['address'] ?? '' ?></textarea>

                    <button type="submit" class="bg-violet-600 hover:bg-violet-700 text-white p-2 rounded col-span-1 md:col-span-2">
    <?= $editMode ? 'Update Student' : 'Add Student' ?>
</button>
                </form>
            </div>

        <?php } elseif (in_array($action, ['view', 'delete', 'update'])) { ?>
            <!-- Student Table -->
            <div class="bg-glass p-4 rounded-lg shadow-md overflow-x-auto">
                <table class="w-full table-auto text-center">
                <thead class="bg-violet-600 text-white">
                        <tr>
                            <th class="p-2">NIC</th>
                            <th class="p-2">Name</th>
                            <th class="p-2">Course</th>
                            <th class="p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($student = mysqli_fetch_assoc($searchResults)) { ?>
                            <tr class="border-b bg-white bg-opacity-70">
                                <td class="p-2"><?= $student['nic'] ?></td>
                                <td class="p-2"><?= $student['name'] ?></td>
                                <td class="p-2"><?= $student['course'] ?></td>
                                <td class="p-2">
                                    <?php if ($action === 'update') { ?>
                                        <a href="dashboard.php?action=update&edit=<?= $student['nic'] ?>" class="text-blue-600 hover:underline">Edit</a>
                                    <?php } elseif ($action === 'delete') { ?>
                                        <a href="dashboard.php?action=delete&delete=<?= $student['nic'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Delete this student?')">Delete</a>
                                    <?php } else { ?>
                                        <span class="text-gray-600">No Actions</span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>

    </main>
</div>
-- <!-- 1. CREATE a table
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
 
<form method="POST" action="">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <input type="submit" name="submit" value="Register">
</form>
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // 3. Insert into database
    $sql = "INSERT INTO users (username, email) VALUES ('$username', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>User registered successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}

-- 2. INSERT rows into the table
INSERT INTO customers (name, email) VALUES 
('John Doe', 'john@example.com'),
('Jane Smith', 'jane@example.com'),
('Alice Johnson', 'alice@example.com');

-- 3. SELECT all data
SELECT * FROM customers;

-- 4. SELECT specific columns
SELECT name, email FROM customers;

-- 5. SELECT with a condition
SELECT * FROM customers WHERE name = 'John Doe';

-- 6. UPDATE a record
UPDATE customers 
SET email = 'john.doe@newmail.com' 
WHERE name = 'John Doe';

-- 7. DELETE a record
DELETE FROM customers 
WHERE name = 'Alice Johnson';

-- 8. ADD a new column
ALTER TABLE customers 
ADD phone VARCHAR(15);

-- 9. UPDATE column name and type
ALTER TABLE customers 
CHANGE phone phone_number VARCHAR(20);

-- 10. RENAME the table
RENAME TABLE customers TO client_data;

-- 11. DROP a column
ALTER TABLE client_data 
DROP COLUMN phone_number;

-- 12. DROP the entire table
DROP TABLE client_data; -->

<?php include 'components/footer.php'; ?>
</body>
</html>
