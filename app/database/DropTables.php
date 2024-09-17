<?php

require_once 'DbConnection.php';
use App\Database\DbConnection;

$conn = DbConnection::getInstance()->getConnection();

$conn->query("SET FOREIGN_KEY_CHECKS = 0");
$result = $conn->query("SHOW TABLES");

if ($result) {

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


$conn->query("SET FOREIGN_KEY_CHECKS = 1");
$conn->close();