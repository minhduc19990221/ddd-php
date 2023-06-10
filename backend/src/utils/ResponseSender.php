<?php

namespace Utils;

use JsonException;

class ResponseSender
{

    public function sendErrorResponse(string $message, int $code): void
    {
        header('Content-Type: application/json');
        http_response_code($code);
        try {
            $json_exception = new JsonException($message);
            echo json_encode(['error' => $message], JSON_THROW_ON_ERROR);
            throw $json_exception;
        } catch (JsonException $e) {
            var_dump($e->getMessage());
        }
    }

    public function sendSuccessResponse(string $message): void
    {
        http_response_code(200);
        try {
            echo json_encode(['message' => $message], JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            var_dump($e->getMessage());
        }
    }
}
