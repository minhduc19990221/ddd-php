<?php

namespace Domain\entity;

class Card
{
    private int $id;
    private string $title;
    private string $description;

    public function __construct(int $id, string $title, string $description)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return htmlspecialchars($this->title);
    }

    public function getDescription(): string
    {
        return htmlspecialchars($this->description);
    }

}
