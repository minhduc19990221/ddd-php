<?php

namespace D002834\Backend\domain\entity;

class Board
{
    private int $id;
    private string $title;

    public function __construct(int $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return htmlspecialchars($this->title);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title
        ];
    }
}
