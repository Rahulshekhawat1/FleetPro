<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';

$isLoggedIn = isLoggedIn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fleet Management System</title>
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
        
        .hero-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1608307167740-9ed69b6c3a0d?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8cHVycGxlJTIwc2VhfGVufDB8fDB8fHww');
            background-size: cover;
            background-position: center;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="font-sans">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-7">
                    <div>
                        <a href="index.php" class="flex items-center py-4 px-2">
                            <span class="font-bold text-purple-700 text-3xl">FleetPro</span>
                        </a>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-3">
                    <a href="#features" class="py-4 px-2 text-gray-500 hover:text-purple-500">Features</a>
                    <a href="#about" class="py-4 px-2 text-gray-500 hover:text-purple-500">About</a>
                    <?php if(!$isLoggedIn): ?>
                        <a href="login.php" class="py-2 px-4 text-gray-500 hover:text-purple-500">Login</a>
                        <a href="register.php" class="py-2 px-4 bg-purple-500 text-white rounded hover:bg-purple-400 transition duration-300">Register</a>
                    <?php else: ?>
                        <a href="dashboard.php" class="py-2 px-4 bg-purple-500 text-white rounded hover:bg-purple-400 transition duration-300">Dashboard</a>
                    <?php endif; ?>
                </div>
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button class="outline-none mobile-menu-button">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div class="hidden mobile-menu">
            <ul class="">
                <li><a href="#features" class="block text-sm px-2 py-4 hover:bg-purple-500 hover:text-white">Features</a></li>
                <li><a href="#about" class="block text-sm px-2 py-4 hover:bg-purple-500 hover:text-white">About</a></li>
                <?php if(!$isLoggedIn): ?>
                    <li><a href="login.php" class="block text-sm px-2 py-4 hover:bg-purple-500 hover:text-white">Login</a></li>
                    <li><a href="register.php" class="block text-sm px-2 py-4 hover:bg-purple-500 hover:text-white">Register</a></li>
                <?php else: ?>
                    <li><a href="dashboard.php" class="block text-sm px-2 py-4 hover:bg-purple-500 hover:text-white">Dashboard</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-4xl font-bold mb-6">Efficient Fleet Management Solution</h1>
            <p class="text-xl mb-12 max-w-2xl mx-auto">Streamline your vehicle and driver management with our comprehensive system</p>
            <div class="flex flex-col md:flex-row justify-center gap-4">
                <?php if(!$isLoggedIn): ?>
                    <a href="register.php" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">Get Started</a>
                    <a href="login.php" class="bg-transparent hover:bg-purple-500 text-blue-200 hover:text-white font-semibold py-3 px-6 border border-blue-200 hover:border-transparent rounded-lg transition duration-300">Login</a>
                <?php else: ?>
                    <a href="dashboard.php" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">Go to Dashboard</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 bg-purple-100">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Key Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-white rounded-lg shadow-md p-6 transition duration-300">
                    <div class="text-blue-500 mb-4">
                        <i class="fas fa-truck text-4xl text-purple-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Vehicle Management</h3>
                    <p class="text-gray-600">Track all your vehicles with detailed information including status, maintenance records, and more.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card bg-white rounded-lg shadow-md p-6 transition duration-300">
                    <div class="text-blue-500 mb-4">
                        <i class="fas fa-users text-4xl text-purple-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Driver Management</h3>
                    <p class="text-gray-600">Manage driver information, licenses, and assignments with ease.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card bg-white rounded-lg shadow-md p-6 transition duration-300">
                    <div class="text-blue-500 mb-4 ">
                        <i class="fas fa-chart-line text-4xl text-purple-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Real-time Dashboard</h3>
                    <p class="text-gray-600">Get instant insights into your fleet's status and performance.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">About FleetPro</h2>
            <div class="max-w-3xl mx-auto text-center">
                <p class="text-gray-600 mb-8">
                    FleetPro is a comprehensive fleet management solution designed to help businesses efficiently manage their vehicles and drivers. 
                    Our system provides real-time tracking, maintenance scheduling, and detailed reporting to optimize your fleet operations.
                </p>
                <p class="text-gray-600">
                    Whether you have a small fleet of vehicles or manage hundreds of assets, FleetPro scales to meet your needs with intuitive 
                    interfaces and powerful features.
                </p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-purple-800 text-white py-8">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; <?php echo date('Y'); ?> FleetPro. All rights reserved.</p>
        </div>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
        const btn = document.querySelector('.mobile-menu-button');
        const menu = document.querySelector('.mobile-menu');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>
