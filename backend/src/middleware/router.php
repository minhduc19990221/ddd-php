<?php

namespace D002834\Backend\middleware\router;


use D002834\Backend\handlers\users\UserHandler;
use function D002834\Backend\middleware\handle_login_request;
use function D002834\Backend\middleware\validate_token;

include_once __DIR__ . '/authentication.php';

function handle_register_request($request_body): void
{
    $fullname = $request_body['fullname'];
    $email = $request_body['email'];
    $password = $request_body['password'];
    $user_handler = new UserHandler();
    $user_handler->register($fullname, $email, $password);
}

function register_routing($request_method, $request_body): void
{
    switch ($request_method) {
        case 'POST':
            handle_register_request($request_body);
            break;
        default:
            http_response_code(405);
            echo json_encode(["message" => "Method not allowed"]);
    }
}

function login_routing($request_method, $request_body): void
{
    switch ($request_method) {
        case 'POST':
            handle_login_request($request_body);
            break;
        default:
            http_response_code(405);
            echo json_encode(["message" => "Method not allowed"]);
    }
}

function user_routing($request_method, $request_body): void
{
    $is_authenticated = validate_token();
    if (!$is_authenticated) {
        http_response_code(401);
        echo json_encode(["message" => "Unauthorized"]);
        return;
    }
    switch ($request_method) {
        case 'PUT':
            update_user_request($request_body);
            break;
        case 'GET':
            get_user_request();
            break;
        default:
            http_response_code(405);
            echo json_encode(["message" => "Method not allowed"]);
    }
}


function update_user_request($request_body): void
{
    $fullname = $request_body['fullname'];
    $email = $request_body['email'];
    $user_handler = new UserHandler();
    $user_handler->update($fullname, $email);
}

function get_user_request(): void
{
    if (!isset($_GET['email'])) {
        http_response_code(400);
        echo json_encode(["message" => "Missing parameters"]);
        return;
    }
    $email = $_GET['email'];
    $user_handler = new UserHandler();
    $user_handler->getOne($email);
}
