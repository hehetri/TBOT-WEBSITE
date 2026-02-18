<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = '127.0.0.1';
$user = 'bout';
$pass = '202040pp';
$db   = 'bout_evolution';

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset('utf8mb4');
