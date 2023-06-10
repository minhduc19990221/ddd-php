<?php

namespace Backend\application\boards\services;

use Backend\application\boards\factory\BoardFactory;
use Backend\domain\entity\Board;
use Backend\domain\repository\BoardRepository;
use Utils\ResponseSender;

class BoardService
{
    private ResponseSender $response_sender;

    public function __construct(ResponseSender $response_sender)
    {
        $this->response_sender = $response_sender;
    }

    public function create(string $title): void
    {
        if (!$title) {
            $this->response_sender->sendErrorResponse(400, 'Missing parameters');
            return;
        }
        $board = BoardFactory::getInstance();
        if ($board === null) {
            $this->response_sender->sendErrorResponse(500, 'Internal server error');
            return;
        }
        $board->createOne($title);
        $this->response_sender->sendSuccessResponse('Board created successfully');
    }

    public function getOne(int $id): ?Board
    {
        if (!$id) {
            $this->response_sender->sendErrorResponse(400, 'Missing parameters');
            return null;
        }

        $board_repository = BoardRepository::getInstance();
        if ($board_repository === null) {
            $this->response_sender->sendErrorResponse(500, 'Internal server error');
            return null;
        }

        $board_record = $board_repository->readOne($id);
        return new Board($board_record['id'], $board_record['title']);
    }
}
