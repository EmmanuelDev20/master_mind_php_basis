<?php

$host = "localhost:3306";
$database = "master_mind_contacts_app";
$user = "root";
$password = "";

try {
 $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
} catch (PDOException $e) {
  die("PDO connection error: " . $e->getMessage());
}