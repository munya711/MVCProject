<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$database = "event management system"; 

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read SQL file
$sqlFile = file_get_contents("database.sql");

if (!$sqlFile) {
    die("Error: Could not read SQL file.");
}

// Split SQL statements (ensure multi-line queries are executed correctly)
$sqlArray = explode(";", $sqlFile);

// Execute each SQL query
foreach ($sqlArray as $query) {
    $trimmedQuery = trim($query);
    if (!empty($trimmedQuery)) {
        if ($conn->query($trimmedQuery) === FALSE) {
            echo "Error executing query: " . $conn->error . "<br>";
        }
    }
}

echo "Database and tables created successfully!";

// Close connection
$conn->close();
?>
