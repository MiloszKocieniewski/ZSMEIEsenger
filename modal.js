const modal = document.querySelector("#modal");
const openModal = document.querySelector(".open-button");
const closeModal = document.querySelector(".close-button");
const searchButton = document.querySelector("#search-button");
const searchInput = document.querySelector("#search-username");
const searchResults = document.querySelector("#search-results");
const requestsContainer = document.querySelector("#requests-container");
const refreshButton = document.querySelector("#button-refresh");

// Otwieranie i zamykanie modala
openModal.addEventListener("click", () => modal.showModal());
closeModal.addEventListener("click", () => modal.close());

// Wyszukiwanie użytkownika
searchButton.addEventListener("click", async () => {
    const username = searchInput.value.trim();
    if (username === "") return;
    
    const response = await fetch(`search_user.php?username=${username}`);
    const users = await response.json();

    searchResults.innerHTML = users.length 
        ? users.map(user => `<div>${user} <button id="send-request" onclick="sendFriendRequest('${user}')">Wyślij zaproszenie</button></div>`).join("")
        : "<p id='Not-fund'>Nie znaleziono użytkownika</p>";
});

// Wysyłanie zaproszenia do znajomych
async function sendFriendRequest(toUser) {
    const response = await fetch("send_request.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ to_user: toUser })
    });

    const result = await response.text();
    alert(result);
}

// Ładowanie zaproszeń do znajomych
async function loadFriendRequests() {
    const response = await fetch("get_requests.php");
    const requests = await response.json();

    requestsContainer.innerHTML = requests.length
        ? requests.map(req => `
            <div>
                ${req.from_user} 
                <button id="button-accept" onclick="acceptRequest(${req.id})">Akceptuj</button>
                <button id="button-reject" onclick="rejectRequest(${req.id})">Odrzuć</button>
            </div>`).join("")
        : "<p id='empty-request'>Brak zaproszeń.</p>";
}

// Akceptowanie zaproszenia
async function acceptRequest(requestId) {
    const response = await fetch("accept_request.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ request_id: requestId })
    });

    const result = await response.text();
    alert(result);
    loadFriendRequests();
}

// Odrzucanie zaproszenia
async function rejectRequest(requestId) {
    const response = await fetch("reject_request.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ request_id: requestId })
    });

    const result = await response.text();
    alert(result);
    loadFriendRequests();
}

// Odświeżanie zaproszeń po kliknięciu w przycisk
refreshButton.addEventListener("click", loadFriendRequests);

// Załaduj zaproszenia po otwarciu modala
openModal.addEventListener("click", loadFriendRequests);
