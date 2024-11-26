<h1>Manage Room Join Requests</h1>
<div id="requests"></div>

<script>
    // Connect to the WebSocket server
    const socket = new WebSocket('ws://127.0.0.1:8081');

    // WebSocket open event
    socket.onopen = function () {
        console.log("WebSocket connected.");
    };

    // WebSocket message event
    socket.onmessage = function (event) {
        const data = JSON.parse(event.data);

        // Handle join requests
        if (data.action === 'join_request') {
            displayJoinRequest(data.user_id, data.room_code);
        }
    };

    // Display a join request in the UI
    function displayJoinRequest(userId, roomCode) {
        const requestsDiv = document.getElementById('requests');
        const requestDiv = document.createElement('div');
        requestDiv.id = `request-${userId}-${roomCode}`;
        requestDiv.innerHTML = `
            <div class="request-card">
                <p><strong>User ID:</strong> ${userId} wants to join <strong>Room:</strong> ${roomCode}</p>
                <button onclick="approve('${roomCode}', '${userId}', '${requestDiv.id}')">Approve</button>
                <button onclick="deny('${roomCode}', '${userId}', '${requestDiv.id}')">Deny</button>
            </div>
        `;
        requestsDiv.appendChild(requestDiv);
    }

    // Approve a join request
    function approve(roomCode, userId, requestId) {
        socket.send(JSON.stringify({ action: 'approve_request', room_code: roomCode, user_id: userId }));
        removeRequest(requestId);
    }

    // Deny a join request
    function deny(roomCode, userId, requestId) {
        socket.send(JSON.stringify({ action: 'deny_request', room_code: roomCode, user_id: userId }));
        removeRequest(requestId);
    }

    // Remove the request from the UI
    function removeRequest(requestId) {
        const requestElement = document.getElementById(requestId);
        if (requestElement) {
            requestElement.remove();
        }
    }

    // Handle WebSocket errors
    socket.onerror = function (error) {
        console.error("WebSocket error:", error);
    };

    // Handle WebSocket closure
    socket.onclose = function () {
        console.warn("WebSocket connection closed.");
    };
</script>
