<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION["user"] = $username;
            header("Location: home.php");
            exit;
        } else {
            echo "Nieprawidłowe hasło!";
        }
    } else {
        echo "Nie znaleziono użytkownika!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style1.css">
    <title>Login</title>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up">
            <form action="register.php" method="post">
                <h1>Stwórz konto</h1>
                <input type="text" name="username" id="username" placeholder="Nazwa użytkownika">
                <input type="password" name="password" id="password" placeholder="Hasło">
                <button type="submit">Zarejestruj się</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form action="index.php" method="post">
                <h1>Logowanie</h1>
                <input type="text" name="username" id="username" placeholder="Nazwa użytkownika">
                <input type="password" name="password" id="password" placeholder="Hasło">
                <button type="submit">Zaloguj się</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Witaj!</h1>
                    <p>Wpisz swoje dane aby się zalogować do strony</p>
                    <button class="hidden" id="login">Logowanie</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Witaj!</h1>
                    <p>Stwórz swoje konto aby dostać się do strony</p>
                    <button class="hidden" id="register">Rejestrowanie</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const container = document.getElementById('container');
        const registerBtn = document.getElementById('register');
        const loginBtn = document.getElementById('login');

        registerBtn.addEventListener('click', () => {
            container.classList.add("active");
        });

        loginBtn.addEventListener('click', () => {
            container.classList.remove("active");
        });
    </script>
</body>

</html>
