let selectedUser = null;

function onUserSelected(user) {
    selectedUser = user;
    document.getElementById("chat-messages").innerHTML = `<p>Ładowanie wiadomości z ${user}...</p>`;
    loadMessages();
}

function sendMessage() {
    if (!selectedUser) {
        alert("Wybierz użytkownika do rozmowy!");
        return;
    }

    const messageInput = document.getElementById("message");
    const message = messageInput.value.trim();

    if (message === "") return;

    fetch("send_message.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `receiver=${selectedUser}&message=${encodeURIComponent(message)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            messageInput.value = "";
            loadMessages();
        } else {
            alert(data.message);
        }
    })
}

function loadMessages() {
    if (!selectedUser) return;

    fetch(`get_messages.php?friend=${selectedUser}`)
    .then(response => response.json())
    .then(messages => {
        const chatContainer = document.getElementById("chat-messages");
        chatContainer.innerHTML = "";

        messages.forEach(msg => {
            const div = document.createElement("div");
            div.classList.add(msg.sender === "<?php echo $_SESSION['user']; ?>" ? "my-message" : "friend-message");
            div.innerHTML = `<strong>${msg.sender}:</strong> ${msg.message} <span class="timestamp">${msg.timestamp}</span>`;
            chatContainer.appendChild(div);
        });

        chatContainer.scrollTop = chatContainer.scrollHeight;
    })
}

// Odświeżaj wiadomości co 3 sekundy
setInterval(loadMessages, 3000);
