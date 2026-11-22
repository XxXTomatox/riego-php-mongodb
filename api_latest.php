<?php
require 'db.php';
$data = $sensorCollection->findOne([], ['sort' => ['_id' => -1]]);
echo json_encode($data);
?>