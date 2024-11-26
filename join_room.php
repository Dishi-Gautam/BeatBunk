<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Room</title>
</head>
<body>
    <h1>Join a Room</h1>
    <input type="text" id="roomCode" placeholder="Enter Room Code">
    <button id="joinRoom" disabled>Join Room</button>

    <div id="status"></div>

    <script>
        let socket;  // Declare socket variable outside the function
        const userId = 'user1';  // Placeholder user ID. Ideally, this should come from the session or login.

        // Function to create WebSocket connection
        function connectToServer() {
            socket = new WebSocket('ws://localhost:8081');  // Create WebSocket connection

            socket.onopen = function() {
                console.log('WebSocket connection established');
            };

            socket.onmessage = function(event) {
                const data = JSON.parse(event.data);
                console.log('Message from server:', data);

                if (data.action === 'join_approved') {
                    document.getElementById('status').textContent = 'Request Approved: You are in the room!';
                } else if (data.action === 'join_denied') {
                    document.getElementById('status').textContent = 'Request Denied: You cannot join the room.';
                } else if (data.action === 'error') {
                    document.getElementById('status').textContent = `Error: ${data.message}`;
                }
            };

            socket.onerror = function(error) {
                console.error('WebSocket Error:', error);
                document.getElementById('status').textContent = 'WebSocket connection failed!';
            };
        }

        // Send the join room request to the server when the button is clicked
        document.getElementById('joinRoom').onclick = function() {
            const roomCode = document.getElementById('roomCode').value;

            if (roomCode && socket && socket.readyState === WebSocket.OPEN) {
                // Send the join request
                socket.send(JSON.stringify({
                    action: 'join_room',
                    room_code: roomCode,
                    user_id: userId
                }));
            } else {
                alert('Please enter a valid room code or try again later.');
            }
        };

        // Listen for input change and enable the Join Room button
        document.getElementById('roomCode').addEventListener('input', function() {
            const roomCode = document.getElementById('roomCode').value;
            document.getElementById('joinRoom').disabled = !roomCode;  // Enable button if room code exists
        });

        // Connect to WebSocket once the page loads
        window.onload = function() {
            connectToServer();  // Connect to the server when the page loads
        };
    </script>
</body>
</html>
