<?php

namespace Application\cards\services;


use Application\cards\factory\CardFactory;
use Domain\entity\Card;
use Domain\repository\CardRepository;
use Utils\ResponseSender;

class CardService
{
    public function create(string $title): void
    {
        if (!$title) {
            ResponseSender::sendErrorResponse(400, 'Missing parameters');
            return;
        }
        $card = CardFactory::getInstance();
        if ($card === null) {
            ResponseSender::sendErrorResponse(500, 'Internal server error');
            return;
        }
        $card->createOne($title);
        ResponseSender::sendSuccessResponse(201, 'Card created successfully');
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
}
