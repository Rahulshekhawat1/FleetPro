<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Get dashboard statistics
$vehicleCount = getVehicleCount();
$driverCount = getDriverCount();
$statusCounts = getVehicleStatusCounts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Fleet Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        html, body {
            height: 100%;
            overflow: hidden;
        }
        .main-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .content-wrapper {
            flex: 1;
            min-height: 0;
            display: flex;
        }
        .sidebar {
            transition: all 0.3s;
            flex-shrink: 0;
        }
        .main-content {
            flex: 1;
            overflow-y: auto;
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
        }
        #statusChart {
            width: 100%;
            max-height: 300px;
        }
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap');
        * {
            font-family: "Quicksand", sans-serif;
            font-optical-sizing: auto;
            font-weight: 700;
            font-style: normal;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="main-container">
        <!-- Mobile header -->
        <header class="bg-white shadow-sm md:hidden flex-shrink-0">
            <div class="flex items-center justify-between p-4">
                <button class="text-gray-500 focus:outline-none sidebar-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
            </div>
        </header>

        <div class="content-wrapper">
            <!-- Sidebar -->
            <div class="sidebar bg-purple-600 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out">
                <div class="text-white flex items-center space-x-2 px-4">
                    <i class="fas fa-truck text-2xl"></i>
                    <span class="text-xl font-semibold">FleetPro</span>
                </div>
                <nav>
                    <a href="dashboard.php" class="block py-2.5 px-4 rounded transition duration-200 bg-purple-700">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    <a href="vehicles.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-700">
                        <i class="fas fa-truck mr-2"></i>Vehicles
                    </a>
                    <a href="drivers.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-700">
                        <i class="fas fa-users mr-2"></i>Drivers
                    </a>
                    <a href="about.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-700">
                        <i class="fas fa-info-circle mr-2"></i>About
                    </a>
                    <a href="logout.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-700">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </nav>
            </div>

            <!-- Main content area -->
            <div class="main-content">
                <!-- Desktop header -->
                <header class="bg-white shadow-sm hidden md:block flex-shrink-0">
                    <div class="flex items-center justify-between p-4">
                        <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-600">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            <div class="w-8 h-8 rounded-full bg-purple-500 flex items-center justify-center text-white">
                                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Content -->
                <div class="p-4">
                    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4">
                <div class="container mx-auto px-4">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Total Vehicles -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-100 text-blue-600 mr-4">
                                    <i class="fas fa-truck text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500">Total Vehicles</p>
                                    <h3 class="text-2xl font-bold"><?php echo $vehicleCount; ?></h3>
                                </div>
                            </div>
                        </div>

                        <!-- Total Drivers -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                                    <i class="fas fa-users text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500">Total Drivers</p>
                                    <h3 class="text-2xl font-bold"><?php echo $driverCount; ?></h3>
                                </div>
                            </div>
                        </div>

                        <!-- Available Vehicles -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                                    <i class="fas fa-check-circle text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500">Available Vehicles</p>
                                    <h3 class="text-2xl font-bold">
                                        <?php 
                                            $available = array_filter($statusCounts, function($item) {
                                                return $item['status'] === 'available';
                                            });
                                            echo $available ? current($available)['count'] : 0;
                                        ?>
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <!-- In Maintenance -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                                    <i class="fas fa-tools text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500">In Maintenance</p>
                                    <h3 class="text-2xl font-bold">
                                        <?php 
                                            $maintenance = array_filter($statusCounts, function($item) {
                                                return $item['status'] === 'maintenance';
                                            });
                                            echo $maintenance ? current($maintenance)['count'] : 0;
                                        ?>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Vehicle Status Chart -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-lg font-semibold mb-4">Vehicle Status</h2>
                            <canvas id="statusChart" height="250"></canvas>
                        </div>

                        <!-- Recent Activity -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-lg font-semibold mb-4">Recent Activity</h2>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center text-blue-600 mr-3">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">New vehicle added</p>
                                        <p class="text-xs text-gray-500">5 minutes ago</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 mr-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">Driver assigned to vehicle</p>
                                        <p class="text-xs text-gray-500">2 hours ago</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 mr-3">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">Vehicle sent for maintenance</p>
                                        <p class="text-xs text-gray-500">Yesterday</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow p-6 mb-8">
                        <h2 class="text-lg font-semibold mb-4">Quick Actions</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <a href="vehicles.php?action=add" class="bg-purple-50 hover:bg-purple-100 text-blue-800 p-4 rounded-lg text-center transition duration-200">
                                <i class="fas fa-plus-circle text-2xl mb-2"></i>
                                <p>Add Vehicle</p>
                            </a>
                            <a href="drivers.php?action=add" class="bg-green-50 hover:bg-green-100 text-green-800 p-4 rounded-lg text-center transition duration-200">
                                <i class="fas fa-user-plus text-2xl mb-2"></i>
                                <p>Add Driver</p>
                            </a>
                            <a href="vehicles.php" class="bg-purple-50 hover:bg-purple-100 text-purple-800 p-4 rounded-lg text-center transition duration-200">
                                <i class="fas fa-list text-2xl mb-2"></i>
                                <p>View Vehicles</p>
                            </a>
                            <a href="drivers.php" class="bg-yellow-50 hover:bg-yellow-100 text-yellow-800 p-4 rounded-lg text-center transition duration-200">
                                <i class="fas fa-users text-2xl mb-2"></i>
                                <p>View Drivers</p>
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mobile sidebar toggle
    
        document.querySelector('.sidebar-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('-translate-x-full');
        });

        // Vehicle Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: [
                    'Available', 
                    'In Use', 
                    'Maintenance', 
                    'Unavailable'
                ],
                datasets: [{
                    data: [
                        <?php 
                            $available = array_filter($statusCounts, function($item) {
                                return $item['status'] === 'available';
                            });
                            echo $available ? current($available)['count'] : 0;
                        ?>,
                        <?php 
                            $inUse = array_filter($statusCounts, function($item) {
                                return $item['status'] === 'in_use';
                            });
                            echo $inUse ? current($inUse)['count'] : 0;
                        ?>,
                        <?php 
                            $maintenance = array_filter($statusCounts, function($item) {
                                return $item['status'] === 'maintenance';
                            });
                            echo $maintenance ? current($maintenance)['count'] : 0;
                        ?>,
                        <?php 
                            $unavailable = array_filter($statusCounts, function($item) {
                                return $item['status'] === 'unavailable';
                            });
                            echo $unavailable ? current($unavailable)['count'] : 0;
                        ?>
                    ],
                    backgroundColor: [
                        '#10B981',
                        '#3B82F6',
                        '#F59E0B',
                        '#EF4444'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>