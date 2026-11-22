<?php
require 'vendor/autoload.php';

// Conexión a MongoDB local
$client = new MongoDB\Client("mongodb://localhost:27017");

// Selecciona o crea la base de datos
$db = $client->riego_vanilla;

// Selecciona las colecciones
$usersCollection = $db->users;
$sensorCollection = $db->sensor_data;
$configCollection = $db->configuracion;

// --- Crear usuario por defecto si no existe ---
if ($usersCollection->countDocuments() == 0) {
    $hash = password_hash('12345678', PASSWORD_DEFAULT);
    $usersCollection->insertOne([
        'email' => 'admin@riego.com',
        'password' => $hash
    ]);
}

// --- Crear configuración inicial de la bomba ---
if ($configCollection->countDocuments() == 0) {
    $configCollection->insertOne([
        '_id' => 'bomba_state',
        'bomba_manual' => 0
    ]);
}
?>