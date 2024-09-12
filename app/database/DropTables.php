<?php

require_once 'DbConnection.php';
use App\Database\DbConnection;

$conn = DbConnection::getInstance()->getConnection();

// Disable foreign key checks
$conn->query("SET FOREIGN_KEY_CHECKS = 0");

// Retrieve the list of all tables in the database
$result = $conn->query("SHOW TABLES");

if ($result) {
    // Loop through the list of tables and drop each one
    while ($row = $result->fetch_array()) {
        $table = $row[0];
        $dropSql = "DROP TABLE IF EXISTS $table";
        if ($conn->query($dropSql) === TRUE) {
            echo "Tabelle $table erfolgreich gelöscht<br>";
        } else {
            echo "Fehler beim Löschen der Tabelle $table: " . $conn->error . "<br>";
        }
    }
} else {
    echo "Fehler beim Abrufen der Tabellenliste: " . $conn->error;
}

// Re-enable foreign key checks
$conn->query("SET FOREIGN_KEY_CHECKS = 1");

// Close the connection
$conn->close();