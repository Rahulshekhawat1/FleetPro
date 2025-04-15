<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validation
    if (empty($username)) {
        $errors['username'] = 'Username is required';
    } elseif (strlen($username) < 3) {
        $errors['username'] = 'Username must be at least 3 characters';
    }

    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters';
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match';
    }

    if (empty($errors)) {
        if (registerUser ($username, $email, $password)) {
            $_SESSION['success_message'] = 'Registration successful! Please login.';
            header('Location: login.php');
            exit();
        } else {
            $errors['general'] = 'Email already registered. Please use a different email.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | FleetPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap');
        * {
        font-family: "Quicksand", sans-serif;
        font-optical-sizing: auto;
        font-weight: 700;
        font-style: normal;
        }
        body{
                background-image: url("https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcROy6X44zYJaFVLL3Cm7tEqnPPqZkxheIfVcQ&s");
                background-size: cover;
            } 
               
        
    </style>
</head>
<body class=" min-h-screen flex items-center justify-center">

<div class="w-full max-w-md bg-white rounded-lg shadow-lg overflow-hidden">
    <!-- Header with Home Button -->
    <div class="flex items-center justify-between bg-purple-600 px-6 py-4">
        <h2 class="text-xl font-bold text-white">FleetPro</h2>
        <a href="index.php" class="flex items-center text-white hover:text-gray-200 transition">
            <h2 class="inline text-xl font-bold text-white hover:text-gray-200 mr-2">Home</h2>
            <i class="fas fa-home text-2xl"></i>
        </a>
    </div>

    <div class="p-8">
        <h2 class="text-3xl font-extrabold text-gray-800 text-center">Create a New Account</h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Or <a href="login.php" class="text-purple-500 hover:underline">login to existing account</a>
        </p>

        <!-- Error Message -->
        <?php if (isset($errors['general'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mt-4">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($errors['general']); ?>
            </div>
        <?php endif; ?>

        <!-- Register Form -->
        <form class="mt-6 space-y-4" action="register.php" method="POST">

            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-user text-purple-500"></i> Username
                </label>
                <input id="username" name="username" type="text" autocomplete="username" required
                    class="w-full px-4 py-2 mt-1 border rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:outline-none"
                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-envelope text-purple-500"></i> Email
                </label>
                <input id="email" name="email" type="email" autocomplete="email" required
                    class="w-full px-4 py-2 mt-1 border rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:outline-none"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-lock text-purple-500"></i> Password
                </label>
                <input id="password" name="password" type="password" autocomplete="new-password" required
                    class="w-full px-4 py-2 mt-1 border rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>

            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-check-circle text-purple-500"></i> Confirm Password
                </label>
                <input id="confirm_password" name="confirm_password" type="password" autocomplete="new-password" required
                    class="w-full px-4 py-2 mt-1 border rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full bg-purple-600 text-white py-2 rounded-lg shadow-lg hover:bg-purple-700 transition">
                    <i class="fas fa-user-plus"></i> Register
                </button>
            </div>
        </form>

    
    </div>
</div>
</body>
</html>
