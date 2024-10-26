document.getElementById('send-button').onclick = function() {
    const userInput = document.getElementById('user-input').value;
    if (userInput.trim() === '') return;

    // Display user's message
    document.getElementById('chat-box').innerHTML += `<div>User: ${userInput}</div>`;

    // Send request to the server
    fetch('api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ message: userInput })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('chat-box').innerHTML += `<div>Bot: ${data.response}</div>`;
        document.getElementById('user-input').value = ''; // Clear input field
    })
    .catch(error => console.error('Error:', error));
};
