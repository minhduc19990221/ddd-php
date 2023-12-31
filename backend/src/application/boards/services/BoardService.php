<?php

namespace Application\boards\services;

use Application\boards\factory\BoardFactory;
use Domain\entity\Board;
use Domain\repository\BoardRepository;
use Utils\ResponseSender;

class BoardService
{
    public function create(string $title, string $email): void
    {
        if (!$title || !$email) {
            ResponseSender::sendErrorResponse(400, 'Missing parameters');
            return;
        }
        $board = BoardFactory::getInstance();
        if ($board === null) {
            ResponseSender::sendErrorResponse(500, 'Internal server error');
            return;
        }
        $board->createOne($title, $email);
        ResponseSender::sendSuccessResponse(201, ['message' => 'Board created successfully']);
    }

    public function getAll(string $email): array
    {
        if (!$email) {
            ResponseSender::sendErrorResponse(400, 'Missing parameters');
            exit();
        }

        $board_repository = BoardRepository::getInstance();
        if ($board_repository === null) {
            ResponseSender::sendErrorResponse(500, 'Internal server error');
            exit();
        }

        $board_records = $board_repository->read($email);
        $boards = [];
        if (count($board_records) > 0) {
            foreach ($board_records as $board_record) {
                $boards[] = (new Board($board_record['id'], $board_record['title']))->toArray();
            }
        }
        return $boards;
    }

    public function updateOne(string $title, int $id): void
    {
        if (!$title || !$id) {
            ResponseSender::sendErrorResponse(400, 'Missing parameters');
            return;
        }
        $board_repository = BoardRepository::getInstance();
        if ($board_repository === null) {
            ResponseSender::sendErrorResponse(500, 'Internal server error');
            return;
        }
        $board_repository->update($title, $id);
        ResponseSender::sendSuccessResponse(200, ['message' => 'Board updated successfully']);
    }

    public function deleteOne(int $id): void
    {
        if (!$id) {
            ResponseSender::sendErrorResponse(400, 'Missing parameters');
            return;
        }
        $board_repository = BoardRepository::getInstance();
        if ($board_repository === null) {
            ResponseSender::sendErrorResponse(500, 'Internal server error');
            return;
        }
        $board_repository->delete($id);
        ResponseSender::sendSuccessResponse(200, ['message' => 'Board deleted successfully']);
    }
}
