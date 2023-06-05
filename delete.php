<?php

require "db.php";

session_start();

$id = $_GET["id"];

$statement = $conn->prepare("SELECT * FROM contacts WHERE id = :id LIMIT 1");
$exe = $statement->execute([":id" => $id]);

if($statement->rowCount() == 0) {
  http_response_code(404);
  echo("HTTP 404 Not found");
  return;
}

$contact = $statement->fetch(PDO::FETCH_ASSOC);

if($contact["user_id"] !== $_SESSION["user"]["id"]) {
  http_response_code(403);
  echo("HTTP 403 UNATHORIZED");
  return;
}

$conn->prepare("DELETE FROM contacts WHERE id = :id")->execute([":id" => $id]);

$_SESSION["flash"] = ["message" => "Contact {$_POST['name']} has been deleted."];

header("Location: home.php");