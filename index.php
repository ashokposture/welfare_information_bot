<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welfare Bot</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            flex-direction: column;
        }

        #date-time {
            font-size: 1.1em;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
            text-align: center; /* Center the date/time */
        }

        #chat-container {
            width: 50%;
            max-width: 600px;
            min-width: 280px;
            height: 70%;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 1.5em; /* Adjusted size */
            margin: 10px 0; /* Adjust margin for spacing above and below */
            padding: 0 15px; /* Added padding for space between text and emoji */
            font-family: 'Arial', sans-serif; /* Change this to your preferred font */
            font-weight: bold; /* You can also change the font weight if needed */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1); /* Optional: Adds a subtle shadow */
            letter-spacing: 0.5px; /* Optional: Adds a bit of spacing between letters */
        }

        #chat-box {
            flex-grow: 1;
            overflow-y: auto;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fafafa;
        }

        #user-input, #send-button, #voice-button {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-right: 5px;
        }

        #user-input {
            flex-grow: 1;
        }

        #controls {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div id="date-time"></div> <!-- This will now display the date, time, and day of the week -->

    <div id="chat-container">
        <h1>  🇮🇳  Welfare Schemes Information Bot  🇮🇳  </h1>    
    
        <div id="chat-box"></div>
        <div id="controls">
            <input type="text" id="user-input" placeholder="Type your message...">
            <button id="send-button">Send</button>
            <button id="voice-button">🎤 Voice Input</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sendButton = document.getElementById('send-button');
            const voiceButton = document.getElementById('voice-button');
            const userInput = document.getElementById('user-input');
            const chatBox = document.getElementById('chat-box');
            const dateTime = document.getElementById('date-time');

            const updateDateTime = () => {
                const now = new Date();
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                dateTime.textContent = now.toLocaleDateString('en-US', options);
            };
            updateDateTime();
            setInterval(updateDateTime, 60000); // Update every minute

            function sendMessage() {
                const inputText = userInput.value.trim();
                if (inputText === '') return;

                userInput.value = '';
                chatBox.innerHTML += `<div>User: ${inputText}</div>`;

                fetch('index.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'user_input=' + encodeURIComponent(inputText)
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    const botResponse = data.response;
                    chatBox.innerHTML += `<div>Bot: ${botResponse}</div>`;
                    chatBox.scrollTop = chatBox.scrollHeight;
                    speak(botResponse);
                })
                .catch(error => {
                    console.error('Error:', error);
                    chatBox.innerHTML += `<div>Bot: Sorry, there was an error processing your request.</div>`;
                });
            }

            sendButton.addEventListener('click', event => {
                event.preventDefault();
                sendMessage();
            });

            userInput.addEventListener('keypress', event => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    sendMessage();
                }
            });

            voiceButton.addEventListener('click', () => {
                const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
                recognition.lang = 'en-US';
                recognition.start();

                recognition.onresult = event => {
                    const voiceInput = event.results[0][0].transcript;
                    userInput.value = voiceInput;
                    sendMessage();
                };

                recognition.onerror = event => {
                    console.error('Voice recognition error:', event.error);
                    chatBox.innerHTML += `<div>Bot: Sorry, I couldn't hear you clearly.</div>`;
                };
            });

            function speak(text) {
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'en-US';
                window.speechSynthesis.speak(utterance);
            }
        });
    </script>
</body>
</html>
