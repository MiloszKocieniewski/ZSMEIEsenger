<?php
session_start();
include "db.php";

if (!isset($_SESSION["user"])) {
    echo json_encode(["error" => "Nie jesteś zalogowany"]);
    exit;
}

$user = $_SESSION["user"];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    $action = $_POST["action"];

    if ($action == "send") {
        // Wysłanie zaproszenia do znajomego
        if (!isset($_POST["to_user"])) {
            echo json_encode(["error" => "Brak użytkownika docelowego"]);
            exit;
        }
        $to_user = $_POST["to_user"];

        // Sprawdzenie czy zaproszenie już istnieje
        $check_sql = "SELECT * FROM friend_requests WHERE from_user='$user' AND to_user='$to_user'";
        $result = $conn->query($check_sql);

        if ($result->num_rows > 0) {
            echo json_encode(["error" => "Zaproszenie już wysłane"]);
            exit;
        }

        $sql = "INSERT INTO friend_requests (from_user, to_user, status) VALUES ('$user', '$to_user', 'pending')";
        if ($conn->query($sql)) {
            echo json_encode(["success" => "Zaproszenie wysłane"]);
        } else {
            echo json_encode(["error" => "Błąd zapytania"]);
        }
    }

    if ($action == "respond") {
        // Akceptowanie lub odrzucanie zaproszenia
        if (!isset($_POST["from_user"]) || !isset($_POST["response"])) {
            echo json_encode(["error" => "Nieprawidłowe dane"]);
            exit;
        }
        $from_user = $_POST["from_user"];
        $response = $_POST["response"];

        if ($response == "accepted") {
            // Dodanie do znajomych
            $conn->query("INSERT INTO friends (user1, user2) VALUES ('$user', '$from_user')");
            $conn->query("UPDATE friend_requests SET status='accepted' WHERE from_user='$from_user' AND to_user='$user'");
            echo json_encode(["success" => "Zaproszenie zaakceptowane"]);
        } elseif ($response == "rejected") {
            $conn->query("UPDATE friend_requests SET status='rejected' WHERE from_user='$from_user' AND to_user='$user'");
            echo json_encode(["success" => "Zaproszenie odrzucone"]);
        } else {
            echo json_encode(["error" => "Nieprawidłowa akcja"]);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Pobieranie zaproszeń
    $sql = "SELECT from_user FROM friend_requests WHERE to_user='$user' AND status='pending'";
    $result = $conn->query($sql);
    
    $requests = [];
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row["from_user"];
    }
    echo json_encode($requests);
}

$conn->close();
?>
