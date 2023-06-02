<?php

namespace D002834\Backend\middleware\router;


use D002834\Backend\handlers\users\UserHandler;
use function D002834\Backend\middleware\handle_login_request;

include_once __DIR__ . '/authentication.php';

function handle_register_request(): void
{
    global $request_body;
    $fullname = $request_body['fullname'];
    $email = $request_body['email'];
    $password = $request_body['password'];
    $user_handler = new UserHandler();
    $user_handler->register($fullname, $email, $password);
}

function register_routing($request_method): void
{
    switch ($request_method) {
        case 'POST':
            handle_register_request();
            break;
        default:
            http_response_code(405);
            echo json_encode(["message" => "Method not allowed"]);
    }
}

function login_routing($request_method): void
{
    switch ($request_method) {
        case 'POST':
            handle_login_request();
            break;
        default:
            http_response_code(405);
            echo json_encode(["message" => "Method not allowed"]);
    }
}

function user_routing($request_method): void
{
    switch ($request_method) {
        case 'GET':
            handle_get_request(REQUEST_URI);
            break;
        case 'POST':
            handle_post_request(REQUEST_URI);
            break;
        case 'PUT':
            handle_put_request(REQUEST_URI);
            break;
        case 'DELETE':
            handle_delete_request(REQUEST_URI);
            break;
        default:
            http_response_code(405);
            echo json_encode(["message" => "Method not allowed"]);
    }
}

function handle_get_request($request_uri): void
{
    // In a real-world application, you would fetch data from a database here.
    // For this example, we'll just return a fixed user.
    $userId = REQUEST_BODY['userId'];
    if ($userId === "1") {
        echo json_encode(["id" => 1, "name" => "John Doe", "email" => "john.doe@example.com"]);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "User not found"]);
    }
}

function handle_post_request($request_uri): void
{
    // In a real-world application, you would validate the input and save the new user in the database here.
    // For this example, we'll just return a
    // Continue from handle_post_request function
    $postData = json_decode(file_get_contents('php://input'), true);
    $newUserId = rand(100, 999);  // Just for this example. You would use an auto-incrementing ID in a real-world app.
    $postData['id'] = $newUserId;
    echo json_encode($postData);
}

function handle_put_request($request_uri): void
{
    // In a real-world application, you would validate the input and update the user in the database here.
    // For this example, we'll just return a fixed response.
    $userId = array_shift($request_uri);
    $putData = json_decode(file_get_contents('php://input'), true);
    $putData['id'] = $userId;
    echo json_encode($putData);
}

function handle_delete_request($request_uri): void
{
    // In a real-world application, you would delete the user from the database here.
    // For this example, we'll just return a fixed response.
    $userId = array_shift($request_uri);
    echo json_encode(["message" => "User with id $userId has been deleted"]);
}
