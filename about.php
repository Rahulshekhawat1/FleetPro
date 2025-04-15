<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | Fleet Management</title>
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
        
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen">
        <div class="sidebar bg-purple-600 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out">
            <div class="text-white flex items-center space-x-2 px-4">
                <i class="fas fa-truck text-2xl"></i>
                <span class="text-xl font-semibold">FleetPro</span>
            </div>
            <nav>
                <a href="dashboard.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-700">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                <a href="vehicles.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-700">
                    <i class="fas fa-truck mr-2"></i>Vehicles
                </a>
                <a href="drivers.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-700">
                    <i class="fas fa-users mr-2"></i>Drivers
                </a>
                <a href="about.php" class="block py-2.5 px-4 rounded transition duration-200 bg-purple-700">
                    <i class="fas fa-info-circle mr-2"></i>About
                </a>
                <a href="logout.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-700">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </nav>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm md:hidden">
                <div class="flex items-center justify-between p-4">
                    <button class="text-gray-500 focus:outline-none sidebar-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800">About</h1>
                </div>
            </header>

            <header class="bg-white shadow-sm hidden md:block">
                <div class="flex items-center justify-between p-4">
                    <h1 class="text-xl font-semibold text-gray-800">About</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <div class="w-8 h-8 rounded-full bg-purple-500 flex items-center justify-center text-white">
                            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4">
                <div class="container mx-auto px-4">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-2xl font-bold mb-6">About FleetPro</h2>
                        <p class="text-gray-700 mb-6">
                            FleetPro is a comprehensive fleet management solution designed to help businesses efficiently 
                            manage their vehicles and drivers. Our system provides real-time tracking, maintenance scheduling, 
                            and detailed reporting to optimize your fleet operations.
                        </p>

                        <h3 class="text-xl font-semibold mb-4">Development Team</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Developer 1 -->
                            <div class="bg-gray-50 rounded-lg p-6 text-center">
                                <div class="w-24 h-24 mx-auto rounded-full bg-purple-100 flex items-center justify-center text-blue-500 text-3xl mb-4">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h4 class="font-bold text-lg">Aman</h4>
                                <p class="text-gray-600">Lead Developer</p>
                                <p class="text-sm text-gray-500 mt-2">Full-stack development and system architecture</p>
                            </div>

                            <!-- Developer 2 -->
                            <div class="bg-gray-50 rounded-lg p-6 text-center">
                                <div class="w-24 h-24 mx-auto rounded-full bg-green-100 flex items-center justify-center text-green-500 text-3xl mb-4">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h4 class="font-bold text-lg">Aditya Pratap Singh</h4>
                                <p class="text-gray-600">Frontend Developer</p>
                                <p class="text-sm text-gray-500 mt-2">UI/UX design and frontend implementation</p>
                            </div>

                            <!-- Developer 3 -->
                            <div class="bg-gray-50 rounded-lg p-6 text-center">
                                <div class="w-24 h-24 mx-auto rounded-full bg-purple-100 flex items-center justify-center text-purple-500 text-3xl mb-4">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h4 class="font-bold text-lg">Harshit Raj</h4>
                                <p class="text-gray-600">Backend Developer</p>
                                <p class="text-sm text-gray-500 mt-2">Database design and API development</p>
                            </div>

                            <!-- Developer 4 -->
                            <div class="bg-gray-50 rounded-lg p-6 text-center">
                                <div class="w-24 h-24 mx-auto rounded-full bg-yellow-100 flex items-center justify-center text-yellow-500 text-3xl mb-4">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h4 class="font-bold text-lg">Mukesh</h4>
                                <p class="text-gray-600">QA Engineer</p>
                                <p class="text-sm text-gray-500 mt-2">Testing and quality assurance</p>
                            </div>
                        </div>

                        <div class="mt-8">
                            <h3 class="text-xl font-semibold mb-4">Contact Us</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-bold mb-2"><i class="fas fa-envelope mr-2 text-blue-500"></i> Email</h4>
                                    <p class="text-gray-600">support@fleetpro.com</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-bold mb-2"><i class="fas fa-phone mr-2 text-blue-500"></i> Phone</h4>
                                    <p class="text-gray-600">+1 (555) 123-4567</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Mobile sidebar toggle
        document.querySelector('.sidebar-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('-translate-x-full');
        });
    </script>
</body>
</html>