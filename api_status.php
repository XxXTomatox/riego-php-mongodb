<?php
require 'db.php';
$config = $configCollection->findOne(['_id' => 'bomba_state']);
$estado = $config ? $config['bomba_manual'] : 0;
echo json_encode(["bomba_manual" => (bool)$estado]);
?>