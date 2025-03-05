<?php
session_start();
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$requestId = $data["request_id"];

$sql = "UPDATE friend_requests SET status = 'rejected' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $requestId);
echo $stmt->execute() ? "Zaproszenie odrzucone!" : "Błąd!";
$conn->close();
?>
