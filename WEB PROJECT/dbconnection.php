<?php
// Database configuration
$host = 'localhost';             
$db   = 'webproject';       // Your database name
$user = 'root';                 
$pass = '';                      
// $charset = 'utf8mb4';            // Character set

// Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$db";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        
    PDO::ATTR_EMULATE_PREPARES   => false,                 
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
