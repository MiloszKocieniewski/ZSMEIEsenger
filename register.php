<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    
    if (empty($username) || empty($password)) {
        echo "Wszystkie pola są wymagane!";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Błąd: Nazwa użytkownika już istnieje.";
    }
}
?>
