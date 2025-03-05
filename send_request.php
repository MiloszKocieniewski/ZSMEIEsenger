<?php
session_start();
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$fromUser = $_SESSION["user"];
$toUser = $data["to_user"];

$sql = "INSERT INTO friend_requests (from_user, to_user) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $fromUser, $toUser);

echo $stmt->execute() ? "Zaproszenie wysłane!" : "Błąd wysyłania.";
$conn->close();
?>
