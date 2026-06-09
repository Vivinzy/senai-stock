<?php
require __DIR__ . '/../vendor/autoload.php';
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
$stmt = $db->query('select id,email,re,role,password from users');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($rows);
