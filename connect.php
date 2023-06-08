<?php
$host = "localhost";
$port = "5432";
$dbname = "perpustakaan";
$user = "postgres";
$password = "admin";

// Establish a connection to the PostgreSQL database
$db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Check if the connection was successful
if (!$db) {
    die("Connection failed: " . pg_last_error());
}
