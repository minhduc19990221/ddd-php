<?php

namespace Utils;

use JsonException;

class ResponseSender
{

    /**
     * @throws JsonException
     */
    public static function sendErrorResponse(int $code, string $message): void
    {
        header('Content-Type: application/json');
        http_response_code($code);
        echo json_encode(['error' => $message], JSON_THROW_ON_ERROR);
    }

    public static function sendSuccessResponse(int $code, string $message): void
    {
        http_response_code($code);
        try {
            echo json_encode(['message' => $message], JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            var_dump($e->getMessage());
        }
    }
}
