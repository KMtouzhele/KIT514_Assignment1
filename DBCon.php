<?php
// Load the .env file
$env = parse_ini_file('.env');

// Access the variables
$dbHost = $env['DB_HOST'];
$dbName = $env['DB_NAME'];
$dbUser = $env['DB_USER'];
$dbPassword = $env['DB_PASSWORD'];

// Create a MySQL connection
$mysqli = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} else {
    echo "Connection successful!  <br>";
}