<?php
include "db.php";

$username = $_GET["username"];
$sql = "SELECT username FROM users WHERE username LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$username%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row["username"];
}

echo json_encode($users);
$conn->close();
?>
