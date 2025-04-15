<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if (loginUser($email, $password)) {
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Invalid email or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | FleetPro</title>
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
    <div class="flex items-center justify-between bg-purple-500 px-6 py-4">
        <h2 class="text-xl font-bold text-white">FleetPro</h2>
        <a href="index.php" class="flex items-center text-white hover:text-gray-200 transition">
            <h2 class="inline text-xl font-bold text-white hover:text-gray-200 mr-2">Home</h2>
            <i class="fas fa-home text-2xl"></i>
        </a>
    </div>

    <div class="p-8">
        <h2 class="text-3xl font-extrabold text-gray-800 text-center">Sign In</h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Or <a href="register.php" class="text-purple-500 hover:underline">register a new account</a>
        </p>

        <!-- Error Message -->
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mt-4">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="login.php" method="POST" class="mt-6 space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-envelope text-purple-500"></i> Email
                </label>
                <input id="email" name="email" type="email" autocomplete="email" required
                    class="w-full px-4 py-2 mt-1 border rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-lock text-purple-500"></i> Password
                </label>
                <input id="password" name="password" type="password" autocomplete="current-password" required
                    class="w-full px-4 py-2 mt-1 border rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox"
                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 text-sm text-gray-700">
                        Remember me
                    </label>
                </div>
                <a href="#" class="text-sm text-purple-500 hover:underline">Forgot password?</a>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full bg-purple-600 text-white py-2 rounded-lg shadow-lg hover:bg-purple-700 transition">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>

