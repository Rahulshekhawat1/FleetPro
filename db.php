<?php
// Database connection
global $pdo;
if (!isset($pdo)) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=fleetApp', 'username', 'password');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

// Vehicle operations
function getVehicles() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM vehicles");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addVehicle($data) {
    global $pdo;
    $sql = "INSERT INTO vehicles (make, model, year, license_plate, status) 
            VALUES (:make, :model, :year, :license_plate, :status)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($data);
}

function updateVehicle($id, $data) {
    global $pdo;
    $sql = "UPDATE vehicles SET 
            make = :make, 
            model = :model, 
            year = :year, 
            license_plate = :license_plate, 
            status = :status 
            WHERE id = :id";
    $data['id'] = $id;
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($data);
}

function deleteVehicle($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM vehicles WHERE id = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

// Driver operations
function getDrivers() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM drivers");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addDriver($data) {
    global $pdo;
    $sql = "INSERT INTO drivers (name, license_number, contact, status) 
            VALUES (:name, :license_number, :contact, :status)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($data);
}

function updateDriver($id, $data) {
    global $pdo;
    $sql = "UPDATE drivers SET 
            name = :name, 
            license_number = :license_number, 
            contact = :contact, 
            status = :status 
            WHERE id = :id";
    $data['id'] = $id;
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($data);
}

function deleteDriver($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM drivers WHERE id = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

// Dashboard stats
function getVehicleCount() {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) FROM vehicles");
    return $stmt->fetchColumn();
}

function getDriverCount() {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) FROM drivers");
    return $stmt->fetchColumn();
}

function getVehicleStatusCounts() {
    global $pdo;
    $stmt = $pdo->query("SELECT status, COUNT(*) as count FROM vehicles GROUP BY status");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>