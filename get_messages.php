<?php
session_start();
include "db.php";

if (!isset($_SESSION["user"])) {
    echo json_encode([]);
    exit;
}

$user = $_SESSION["user"];
$friend = $_GET["friend"];

$sql = "SELECT sender, message, timestamp FROM messages 
        WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?) 
        ORDER BY timestamp ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $user, $friend, $friend, $user);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
$stmt->close();
$conn->close();
?>
