<?php

namespace Application\cards\services;


use Application\cards\factory\CardFactory;
use Domain\entity\Card;
use Domain\repository\CardRepository;
use Utils\ResponseSender;

class CardService
{
    public function create(string $title, int $board_id, int $index_board): void
    {
        if (!$title || !$board_id || !$index_board) {
            ResponseSender::sendErrorResponse(400, 'Missing parameters');
            return;
        }
        $card = CardFactory::getInstance();
        if ($card === null) {
            ResponseSender::sendErrorResponse(500, 'Internal server error');
            return;
        }
        $card->createOne($title, $board_id, $index_board);
        ResponseSender::sendSuccessResponse(201, ['message' => 'Card created successfully']);
    }

    public function getOne(int $id): ?Card
    {
        if (!$id) {
            ResponseSender::sendErrorResponse(400, 'Missing parameters');
            return null;
        }

        $card_repository = CardRepository::getInstance();
        if ($card_repository === null) {
            ResponseSender::sendErrorResponse(500, 'Internal server error');
            return null;
        }

        $card_record = $card_repository->readOne($id);
        return new Card($card_record['id'], $card_record['title'], $card_record['description']);
    }

    public function getAll(int $board_id): array
    {
        if (!$board_id) {
            ResponseSender::sendErrorResponse(400, 'Missing parameters');
            exit();
        }

        $card_repository = CardRepository::getInstance();
        if ($card_repository === null) {
            ResponseSender::sendErrorResponse(500, 'Internal server error');
            exit();
        }

        $card_records = $card_repository->read($board_id);
//        error_log('Card records: ' . print_r($card_records, true)); // Debug line

        $cards = [];
        if (count($card_records) > 0) {
            foreach ($card_records as $card_record) {
                error_log('Card records: ' . print_r($card_record, true)); // Debug line
                $cards[] = (new Card($card_record['id'], $card_record['title'], $card_record['index_board']))->toArray();
            }
        }
        return $cards;
    }

    public function updateOne(int $id, string $title, int $index_board): void
    {
        if (!$id || !$title || !$index_board) {
            ResponseSender::sendErrorResponse(400, 'Missing parameters');
            return;
        }

        $card_repository = CardRepository::getInstance();
        if ($card_repository === null) {
            ResponseSender::sendErrorResponse(500, 'Internal server error');
            return;
        }

        $card_repository->update($id, $title, $index_board);
        ResponseSender::sendSuccessResponse(200, ['message' => 'Card updated successfully']);
    }

    public function deleteOne(int $id): void
    {
        if (!$id) {
            ResponseSender::sendErrorResponse(400, 'Missing parameters');
            return;
        }

        $card_repository = CardRepository::getInstance();
        if ($card_repository === null) {
            ResponseSender::sendErrorResponse(500, 'Internal server error');
            return;
        }

        $card_repository->delete($id);
        ResponseSender::sendSuccessResponse(200, ['message' => 'Card deleted successfully']);
    }
}
