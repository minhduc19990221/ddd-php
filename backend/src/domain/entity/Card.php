<?php

namespace Backend\domain\entity;

class Card
{
    private int $id;
    private string $title;
    private string $description;
    private string $created_at;
    private string $updated_at;

    public function __construct(int $id, string $title, string $description, string $created_at, string $updated_at)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
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

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

}
