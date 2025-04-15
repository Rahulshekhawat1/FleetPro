<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Handle form submissions
$action = $_GET['action'] ?? '';
$vehicleId = $_GET['id'] ?? 0;

// Add/Edit Vehicle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'make' => trim($_POST['make']),
        'model' => trim($_POST['model']),
        'year' => trim($_POST['year']),
        'license_plate' => trim($_POST['license_plate']),
        'status' => trim($_POST['status'])
    ];

    if ($action === 'edit' && $vehicleId > 0) {
        if (updateVehicle($vehicleId, $data)) {
            $_SESSION['success'] = 'Vehicle updated successfully!';
        } else {
            $_SESSION['error'] = 'Failed to update vehicle.';
        }
    } else {
        if (addVehicle($data)) {
            $_SESSION['success'] = 'Vehicle added successfully!';
        } else {
            $_SESSION['error'] = 'Failed to add vehicle.';
        }
    }
    header('Location: vehicles.php');
    exit();
}

// Delete Vehicle
if ($action === 'delete' && $vehicleId > 0) {
    if (deleteVehicle($vehicleId)) {
        $_SESSION['success'] = 'Vehicle deleted successfully!';
    } else {
        $_SESSION['error'] = 'Failed to delete vehicle.';
    }
    header('Location: vehicles.php');
    exit();
}

// Get all vehicles
$vehicles = getVehicles();
$statuses = ['available', 'in_use', 'maintenance', 'unavailable'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicles | Fleet Management</title>
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
        <!-- Sidebar -->
        <div class="sidebar bg-purple-600 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out">
            <div class="text-white flex items-center space-x-2 px-4">
                <i class="fas fa-truck text-2xl"></i>
                <span class="text-xl font-semibold">FleetPro</span>
            </div>
            <nav>
                <a href="dashboard.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-700">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                <a href="vehicles.php" class="block py-2.5 px-4 rounded transition duration-200 bg-purple-700">
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

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Mobile header -->
            <header class="bg-white shadow-sm md:hidden">
                <div class="flex items-center justify-between p-4">
                    <button class="text-gray-500 focus:outline-none sidebar-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800">Vehicle Management</h1>
                </div>
            </header>

            <!-- Desktop header -->
            <header class="bg-white shadow-sm hidden md:block">
                <div class="flex items-center justify-between p-4">
                    <h1 class="text-xl font-semibold text-gray-800">Vehicle Management</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <div class="w-8 h-8 rounded-full bg-purple-500 flex items-center justify-center text-white">
                            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4">
                <div class="container mx-auto px-4">
                    <!-- Success/Error Messages -->
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline"><?php echo $_SESSION['success']; ?></span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <button class="close-alert">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline"><?php echo $_SESSION['error']; ?></span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <button class="close-alert">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <!-- Add Vehicle Button -->
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Vehicles</h2>
                        <a href="vehicles.php?action=add" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-plus mr-2"></i>Add Vehicle
                        </a>
                    </div>

                    <!-- Add/Edit Form -->
                    <?php if ($action === 'add' || $action === 'edit'): ?>
                        <?php 
                            $vehicle = [];
                            if ($action === 'edit' && $vehicleId > 0) {
                                foreach ($vehicles as $v) {
                                    if ($v['id'] == $vehicleId) {
                                        $vehicle = $v;
                                        break;
                                    }
                                }
                            }
                        ?>
                        <div class="bg-white rounded-lg shadow p-6 mb-6">
                            <h3 class="text-lg font-semibold mb-4">
                                <?php echo $action === 'add' ? 'Add New Vehicle' : 'Edit Vehicle'; ?>
                            </h3>
                            <form method="POST" action="vehicles.php?action=<?php echo $action; ?><?php echo $action === 'edit' ? '&id='.$vehicleId : ''; ?>">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-gray-700 mb-2" for="make">Make</label>
                                        <input type="text" id="make" name="make" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            value="<?php echo $vehicle['make'] ?? ''; ?>">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 mb-2" for="model">Model</label>
                                        <input type="text" id="model" name="model" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            value="<?php echo $vehicle['model'] ?? ''; ?>">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 mb-2" for="year">Year</label>
                                        <input type="number" id="year" name="year" min="1900" max="<?php echo date('Y') + 1; ?>" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            value="<?php echo $vehicle['year'] ?? ''; ?>">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 mb-2" for="license_plate">License Plate</label>
                                        <input type="text" id="license_plate" name="license_plate" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            value="<?php echo $vehicle['license_plate'] ?? ''; ?>">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 mb-2" for="status">Status</label>
                                        <select id="status" name="status" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <?php foreach ($statuses as $status): ?>
                                                <option value="<?php echo $status; ?>" 
                                                    <?php if (isset($vehicle['status']) && $vehicle['status'] === $status) echo 'selected'; ?>>
                                                    <?php echo ucfirst($status); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-6 flex justify-end space-x-4">
                                    <a href="vehicles.php" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                        Cancel
                                    </a>
                                    <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded">
                                        <?php echo $action === 'add' ? 'Add Vehicle' : 'Update Vehicle'; ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>

                    <!-- Vehicles Table -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Make</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">License Plate</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php if (empty($vehicles)): ?>
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No vehicles found</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($vehicles as $vehicle): ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($vehicle['make']); ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($vehicle['model']); ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($vehicle['year']); ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($vehicle['license_plate']); ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <?php 
                                                        $statusClass = '';
                                                        switch ($vehicle['status']) {
                                                            case 'available':
                                                                $statusClass = 'bg-green-100 text-green-800';
                                                                break;
                                                            case 'in_use':
                                                                $statusClass = 'bg-purple-100 text-blue-800';
                                                                break;
                                                            case 'maintenance':
                                                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                                                break;
                                                            case 'unavailable':
                                                                $statusClass = 'bg-red-100 text-red-800';
                                                                break;
                                                            default:
                                                                $statusClass = 'bg-gray-100 text-gray-800';
                                                        }
                                                    ?>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusClass; ?>">
                                                        <?php echo ucfirst($vehicle['status']); ?>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="vehicles.php?action=edit&id=<?php echo $vehicle['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="vehicles.php?action=delete&id=<?php echo $vehicle['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this vehicle?');">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
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

        // Close alert messages
        document.querySelectorAll('.close-alert').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('div[role="alert"]').remove();
            });
        });
    </script>
</body>
</html>