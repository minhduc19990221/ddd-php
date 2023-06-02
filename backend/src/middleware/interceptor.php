<?php
namespace D002834\Backend\middleware;

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Access");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);

// Remove script path from request URI, if necessary
if (str_starts_with($requestUri, $scriptPath)) {
    $requestUri = substr($requestUri, strlen($scriptPath));
}

$requestUri = explode('/', trim($requestUri, '/'));

$request_body = json_decode(file_get_contents('php://input'), true);

var_dump($request_body);

$resource = array_shift($requestUri);

if ($resource === 'users') {
    switch($requestMethod) {
        case 'GET':
            handleGetRequest($requestUri);
            break;
        case 'POST':
            handlePostRequest($requestUri);
            break;
        case 'PUT':
            handlePutRequest($requestUri);
            break;
        case 'DELETE':
            handleDeleteRequest($requestUri);
            break;
        default:
            http_response_code(405);
            echo json_encode(["message" => "Method not allowed"]);
    }
} else {
    http_response_code(404);
    echo json_encode(["message" => "Resource not found"]);
}

function handleGetRequest($requestUri): void
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

function handlePostRequest($requestUri): void
{
    // In a real-world application, you would validate the input and save the new user in the database here.
    // For this example, we'll just return a
    // Continue from handlePostRequest function
    $postData = json_decode(file_get_contents('php://input'), true);
    $newUserId = rand(100, 999);  // Just for this example. You would use an auto-incrementing ID in a real-world app.
    $postData['id'] = $newUserId;
    echo json_encode($postData);
}

function handlePutRequest($requestUri): void
{
    // In a real-world application, you would validate the input and update the user in the database here.
    // For this example, we'll just return a fixed response.
    $userId = array_shift($requestUri);
    $putData = json_decode(file_get_contents('php://input'), true);
    $putData['id'] = $userId;
    echo json_encode($putData);
}

function handleDeleteRequest($requestUri): void
{
    // In a real-world application, you would delete the user from the database here.
    // For this example, we'll just return a fixed response.
    $userId = array_shift($requestUri);
    echo json_encode(["message" => "User with id $userId has been deleted"]);
}


