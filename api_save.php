<?php
require 'db.php';
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $sensorCollection->insertOne([
        'temperatura' => (float)($data['temperatura'] ?? 0),
        'humedad' => (float)($data['humedad'] ?? 0),
        'peso' => (float)($data['peso'] ?? 0),
        'alerta_termica' => $data['alerta_termica'] ? 1 : 0,
        'bomba_encendida' => $data['bomba_encendida'] ? 1 : 0,
        'alerta_peso' => $data['alerta_peso'] ? 1 : 0,
        'created_at' => new \MongoDB\BSON\UTCDateTime()
    ]);
    echo json_encode(["status" => "success"]);
} else {
    http_response_code(400);
    echo json_encode(["status" => "error"]);
}
?>