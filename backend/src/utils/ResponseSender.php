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
        http_response_code($code);
        echo json_encode(['error' => $message], JSON_THROW_ON_ERROR);
    }

    public static function sendSuccessResponse(int $code, array $message): void
    {
        http_response_code($code);
        echo json_encode($message, JSON_THROW_ON_ERROR);
    }
}
