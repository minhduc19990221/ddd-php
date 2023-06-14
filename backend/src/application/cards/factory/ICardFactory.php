<?php

namespace Application\cards\factory;
interface ICardFactory
{
    public function createOne(string $title, int $board_id, int $index_board): void;
}
