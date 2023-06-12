<?php

namespace Application\cards\services;


use Application\cards\factory\CardFactory;
use Domain\entity\Card;
use Domain\repository\CardRepository;
use Utils\ResponseSender;

class CardService
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
        $card = CardFactory::getInstance();
        if ($card === null) {
            $this->response_sender->sendErrorResponse(500, 'Internal server error');
            return;
        }
        $card->createOne($title);
        $this->response_sender->sendSuccessResponse('Card created successfully');
    }

    public function getOne(int $id): ?Card
    {
        if (!$id) {
            $this->response_sender->sendErrorResponse(400, 'Missing parameters');
            return null;
        }

        $card_repository = CardRepository::getInstance();
        if ($card_repository === null) {
            $this->response_sender->sendErrorResponse(500, 'Internal server error');
            return null;
        }

        $card_record = $card_repository->readOne($id);
        return new Card($card_record['id'], $card_record['title']);
    }
}