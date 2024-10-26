<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input
    $data = json_decode(file_get_contents('php://input'), true);
    $userMessage = $data['message'];

    // Call OpenAI API
    $apiKey = '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'; // Replace with your OpenAI API key
    $url = 'https://api.openai.com/v1/chat/completions';

    $postData = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'user', 'content' => $userMessage]
        ],
        'max_tokens' => 150
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($response, true);
    $botMessage = $responseData['choices'][0]['message']['content'] ?? 'Sorry, I could not understand that.';

    echo json_encode(['response' => $botMessage]);
}
?>
