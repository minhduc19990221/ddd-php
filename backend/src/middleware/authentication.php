<?php

use Firebase\JWT\JWT;

$key = "your_secret_key"; // Secret key, must be stored securely
$userId = 1;
$username = 2;
$token = array(
    "iss" => "http://example.org", // Issuer
    "aud" => "http://example.com", // Audience
    "iat" => 1356999524, // Issued At
    "nbf" => 1357000000, // Not Before
    "exp" => time() + (60 * 60), // Expiration time
    "data" => [ // Data related to the signer user
        "userId" => $userId, // userid from the users table
        "userName" => $username, // username
    ]
);

$jwt = JWT::encode($token, $key);
