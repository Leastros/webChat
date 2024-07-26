<?php

require '../db/DB.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}


// get data
$jsonInput = file_get_contents('php://input');
$data = json_decode($jsonInput, true);
if (!$data) {
    http_response_code(400);
    exit;
}

$username = $data['username'] ?? '';

// connect
$conn = DB::connect();
if ($conn->connect_error) {
    http_response_code(500);
    exit;
}

// prepare
$stmt = $conn->prepare("SELECT username FROM users WHERE username=?");
if (!$stmt) {
    http_response_code(500);
    exit;
}

// execute
$stmt->bind_param("s", $username);
if (!$stmt->execute()) {
    http_response_code(500);
    exit;
}

// evaluate
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo 'false';
} else {
    echo 'true';
}

$stmt->close();
$conn->close();
