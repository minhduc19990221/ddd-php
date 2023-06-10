<?php

namespace Backend\application\boards\services;

use Backend\application\boards\factory\BoardFactory;
use Backend\domain\entity\Board;
use Backend\domain\repository\BoardRepository;

class BoardService
{
    public function create(string $title): void
    {
        if (!$title) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing parameters']);
            return;
        }
        $board = BoardFactory::getInstance();
        $board->createOne($title);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Board created successfully']);
    }

    public function getOne(int $id): Board|null
    {
        if (!$id) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing parameters']);
            return null;
        }
        $board_repository = BoardRepository::getInstance();
        $board_record = $board_repository->readOne($id);
        return new Board($board_record['id'], $board_record['title']);
    }
}
