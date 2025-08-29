<?php
// db.php
// This script establishes a connection to the MySQL database using PDO.
// Using PDO is recommended for its security features (like prepared statements)
// and its ability to work with various database systems.

// --- Database Configuration ---
// Replace these with your actual database credentials.
$db_host = 'localhost';     // Database host (usually 'localhost')
$db_name = 'crud_pdo_db';   // Database name
$db_user = 'root';          // Database username
$db_pass = '';              // Database password

// --- DSN (Data Source Name) ---
// This string contains the information required to connect to the database.
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

// --- PDO Connection Options ---
// These options configure the behavior of the PDO connection.
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Turn on errors in the form of exceptions.
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Make the default fetch mode associative array.
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Turn off emulation mode for real prepared statements.
];

// --- Establish the Connection ---
try {
    // Create a new PDO instance.
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    // If the connection fails, catch the exception and display an error message.
    // In a production environment, you would want to log this error instead of
    // showing it to the user for security reasons.
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>
