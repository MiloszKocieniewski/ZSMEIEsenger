<?php
session_start();
include "db.php";

if (!isset($_SESSION["user"])) {
    echo json_encode(["status" => "error", "message" => "Nie jesteś zalogowany!"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sender = $_SESSION["user"];
    $receiver = $_POST["receiver"];
    $message = trim($_POST["message"]);

    if (empty($message)) {
        echo json_encode(["status" => "error", "message" => "Wiadomość jest pusta!"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO messages (sender, receiver, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $sender, $receiver, $message);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Wiadomość wysłana!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Błąd wysyłania wiadomości."]);
    }

    $stmt->close();
    $conn->close();
}
?>
