<?php

namespace Application\boards\services;

use Application\boards\factory\BoardFactory;
use Domain\entity\Board;
use Domain\repository\BoardRepository;
use Utils\ResponseSender;

class BoardService
{
    public function create(string $title): void
    {
        if (!$title) {
            ResponseSender::sendErrorResponse(400, 'Missing parameters');
            return;
        }
        $board = BoardFactory::getInstance();
        if ($board === null) {
            ResponseSender::sendErrorResponse(500, 'Internal server error');
            return;
        }
        $board->createOne($title);
        ResponseSender::sendSuccessResponse(201, ['message' => 'Board created successfully']);
    }

    public function getOne(int $id): ?Board
    {
        if (!$id) {
            ResponseSender::sendErrorResponse(400, 'Missing parameters');
            return null;
        }

        $board_repository = BoardRepository::getInstance();
        if ($board_repository === null) {
            ResponseSender::sendErrorResponse(500, 'Internal server error');
            return null;
        }

        $board_record = $board_repository->readOne($id);
        return new Board($board_record['id'], $board_record['title']);
    }
}
