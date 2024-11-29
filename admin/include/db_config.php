<?php
    // Database configuration file

    // Define database server details
    define('DB_SERVER', 'localhost'); // Database server 
    define('DB_USERNAME', 'root');    // Database username 
    define('DB_PASSWORD', '');        // Database password 
    define('DB_DATABASE', 'hotel');   // Name of your database

    // Create a connection instance
    $connection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Optionally, start the session here if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

?>
