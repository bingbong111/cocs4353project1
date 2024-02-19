<?php
$host = "cosc4353db.mysql.database.azure.com";
$dbname = "cosc4353";
$username = "loctrinh";
$password = "84268426bA";
$port = 3306;
$mysqli = mysqli_init();
mysqli_ssl_set($mysqli, NULL, NULL, "./DigiCertGlobalRootCA.crt.pem", NULL, NULL);
if (!$mysqli->real_connect($host, $username, $password, $dbname, $port, NULL, MYSQLI_CLIENT_SSL)) {
    die("Connection error: " . $mysqli->connect_error);
}
return $mysqli;
