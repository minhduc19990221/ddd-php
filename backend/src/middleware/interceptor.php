<?php

namespace D002834\Backend\middleware;

use D002834\Backend\handlers\users\UserHandler;
use D002834\Backend\repository\UserRepository;

// Overwrite header to avoid CORS error when integration testing
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Access");

define("REQUEST_METHOD", $_SERVER["REQUEST_METHOD"]);
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$script_path = dirname($_SERVER['SCRIPT_NAME']);

// Remove script path from request URI, if necessary
if (str_starts_with($request_uri, $script_path)) {
    $request_uri = substr($request_uri, strlen($script_path));
}

$request_uri = explode('/', trim($request_uri, '/'));
define("REQUEST_URI", $request_uri);

$request_body = json_decode(file_get_contents('php://input'), true);
define("REQUEST_BODY", $request_body);

$resource = array_shift($request_uri);
define("RESOURCE", $resource);

function handle_login_request(array $request_uri): void
{
    $user = UserRepository::getInstance();
    $user->login($request_uri[0], $request_uri[1]);
}

function handle_register_request(): void
{
    global $request_body;
    $fullname = $request_body['fullname'];
    $email = $request_body['email'];
    $password = $request_body['password'];
    $user_handler = new UserHandler();
    $user_handler->register($fullname, $email, $password);
}

if (RESOURCE === 'register') {
    switch (REQUEST_METHOD) {
        case 'POST':
            handle_register_request(REQUEST_URI);
            break;
        default:
            http_response_code(405);
            echo json_encode(["message" => "Method not allowed"]);
    }
} else {
    http_response_code(404);
    echo json_encode(["message" => "Resource not found"]);
}

if (RESOURCE === 'login') {
    switch (REQUEST_METHOD) {
        case 'POST':
            handle_login_request(REQUEST_URI);
            break;
        default:
            http_response_code(405);
            echo json_encode(["message" => "Method not allowed"]);
    }
} else {
    http_response_code(404);
    echo json_encode(["message" => "Resource not found"]);
}


if (RESOURCE === 'users') {
    switch (REQUEST_METHOD) {
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
} else {
    http_response_code(404);
    echo json_encode(["message" => "Resource not found"]);
}

function handle_get_request($request_uri): void
{
    // In a real-world application, you would fetch data from a database here.
    // For this example, we'll just return a fixed user.
    global $request_body;
    $userId = $request_body['userId'];
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


