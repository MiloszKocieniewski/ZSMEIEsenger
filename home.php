<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}
include "db.php";

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

$user = $_SESSION["user"];

$sql = "SELECT DISTINCT CASE
            WHEN user1 = '$user' THEN user2
            ELSE user1
        END AS friend
        FROM friends
        WHERE user1 = '$user' OR user2 = '$user'";
$result = $conn->query($sql);

$friends = [];
while ($row = $result->fetch_assoc()) {
    $friends[] = $row['friend'];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zsmeiesenger</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <button class="button open-button">open modal</button>
        <h2><?php echo $_SESSION["user"]; ?></h2>
        <a href="logout.php">Wyloguj się</a>
    </header>
    <div class="messenger-container">
        <div class="users-list">
            <h3 id="user-list-h3">Twoi znajomi</h3>
            <hr id="friend-line">
            <?php foreach ($friends as $friend): ?>
                <div onclick="onUserSelected('<?php echo htmlspecialchars($friend); ?>')"><?php echo htmlspecialchars($friend); ?></div>
            <?php endforeach; ?>
        </div>
        <div class="chat-area">
            <div id="chat-messages">
                <p>Wybierz znajomego, aby rozpocząć czat.</p>
            </div>
            <div class="chat-input">
                <input type="text" id="message" placeholder="Wpisz wiadomość..." autocomplete="off">
                <button onclick="sendMessage()">Wyślij</button>
            </div>
        </div>
    </div>


    <dialog class="modal" id="modal">
        <div>
            <button id="modal-button" class="button close-button">X</button>
            <h3 id="modal-text">Dodaj znajomego</h3>
            <input type="text" id="search-username" placeholder="Wyszukaj użytkownika...">
            <button id="search-button">Szukaj</button>
        </div>
        <div id="search-results"></div>
        <hr id="line">
        <div class="friend-requests">
            <h3 id="modal-text">Zaproszenia do znajomych</h3>
            <div id="requests-container">
            </div>
            <button id="button-refresh" onclick="loadFriendRequests()">Odśwież zaproszenia</button>
    </dialog>
    <script src="chat.js"></script>
    <script src="modal.js"></script>
</body>
</html>