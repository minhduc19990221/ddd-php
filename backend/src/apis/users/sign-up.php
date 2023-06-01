<?php
// Include the User class file

// Check if the request method is POST
use D002834\Backend\repository\UserRepository;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Create a new user object
    $user = UserRepository::getInstance();

    // Insert the new user record into the database
    $user->createOne($data['fullname'], $data['email'], $data['password']);

    // Return a success message
    header('Content-Type: application/json');
    echo json_encode(['message' => 'User created successfully']);
}