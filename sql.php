<?php

//Funktion zum verbinden der MySQL und returnt die Connection
function connect() {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "kurse";
    
    $conn = new mysqli($host, $username, $password, $database);
    
    if($conn->connect_error) {
        die("Database-Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

// Sucht einen Kurs in der Datenbank und gibt ein Array mit den Einträgen zurück
function getkurs($kursnummer) {
    $connection = connect();
    
    $result = $connection->query("SELECT * FROM kurse WHERE kursnummer='" . $kursnummer . "'");

        if($result != null && $result->num_rows > 0 && $row = $result->fetch_assoc()) {
            return $row;
        }else{
            return null;
        }
    
    $connection->close();
}