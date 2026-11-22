<?php
session_start();
if (!isset($_SESSION['user_id'])) { http_response_code(401); exit; }
require 'db.php';

$config = $configCollection->findOne(['_id' => 'bomba_state']);
$nuevoEstado = $config['bomba_manual'] ? 0 : 1;

$configCollection->updateOne(
    ['_id' => 'bomba_state'],
    ['$set' => ['bomba_manual' => $nuevoEstado]]
);

echo json_encode(["status" => "success", "estado" => $nuevoEstado]);
?>