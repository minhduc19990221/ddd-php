<?php

namespace Domain\entity;

class Card
{
    private int $id;
    private string $title;
    private int $index_board;

    public function __construct(int $id, string $title, int $index_board)
    {
        $this->id = $id;
        $this->title = $title;
        $this->index_board = $index_board;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return htmlspecialchars($this->title);
    }

    public function getIndexBoard(): int
    {
        return $this->index_board;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'index_board' => $this->index_board
        ];
    }
}
