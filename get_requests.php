<?php
session_start();
include "db.php";

$user = $_SESSION["user"];
$sql = "SELECT id, from_user FROM friend_requests WHERE to_user = ? AND status = 'pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}

echo json_encode($requests);
$conn->close();
?>
