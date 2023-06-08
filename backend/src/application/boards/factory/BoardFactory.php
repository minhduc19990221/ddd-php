<?php

namespace D002834\Backend\application\boards\factory;

use D002834\Backend\domain\repository\BoardRepository;

class BoardFactory implements IBoardFactory
{
    private static ?BoardFactory $instance = null;

    private static ?BoardRepository $board_repository;

    private function __construct()
    {
        self::$board_repository = BoardRepository::getInstance();
        self::$board_repository->createTable();
    }

    public static function getInstance(): ?BoardFactory
    {
        if (self::$instance == null) {
            self::$instance = new BoardFactory();
        }
        return self::$instance;
    }

    public function createOne(string $title): void
    {
        self::$board_repository->create($title);
    }

}