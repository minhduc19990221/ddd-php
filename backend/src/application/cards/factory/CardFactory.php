<?php

namespace Application\cards\factory;


use Domain\repository\CardRepository;

class CardFactory implements ICardFactory
{
    private static ?CardFactory $instance = null;

    private static ?CardRepository $card_repository;

    private function __construct()
    {
        self::$card_repository = CardRepository::getInstance();
        self::$card_repository->createTable();
    }

    public static function getInstance(): ?CardFactory
    {
        if (self::$instance === null) {
            self::$instance = new CardFactory();
        }
        return self::$instance;
    }

    public function createOne(string $title): void
    {
        self::$card_repository->create($title);
    }

}
