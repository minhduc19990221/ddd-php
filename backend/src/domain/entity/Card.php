<?php

namespace Domain\entity;

class Card
{
    private int $id;
    private string $title;
    private int $index_board;
    private int $board_id;


    public function __construct(int $id, string $title, int $index_board, int $board_id)
    {
        $this->id = $id;
        $this->title = $title;
        $this->index_board = $index_board;
        $this->board_id = $board_id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'index_board' => $this->index_board,
            'board_id' => $this->board_id
        ];
    }
}
