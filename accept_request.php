<?php
session_start();
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$requestId = $data["request_id"];

$sql = "SELECT from_user, to_user FROM friend_requests WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $requestId);
$stmt->execute();
$result = $stmt->get_result();
$request = $result->fetch_assoc();

if ($request) {
    $sql = "INSERT INTO friends (user1, user2) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $request["from_user"], $request["to_user"]);
    $stmt->execute();

    $sql = "UPDATE friend_requests SET status = 'accepted' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $requestId);
    $stmt->execute();

    echo "Zaproszenie zaakceptowane!";
} else {
    echo "Błąd!";
}

$conn->close();
?>
