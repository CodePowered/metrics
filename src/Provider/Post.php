<?php declare(strict_types=1);

namespace App\Provider;

use DateTimeInterface;

class Post
{
    private string $authorName;
    private string $authorId;
    private string $message;
    private DateTimeInterface $createdTime;

    public function __construct(string $authorName, string $authorId, string $message, DateTimeInterface $createdTime)
    {
        $this->authorName = $authorName;
        $this->authorId = $authorId;
        $this->message = $message;
        $this->createdTime = $createdTime;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function getAuthorId(): string
    {
        return $this->authorId;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCreatedTime(): DateTimeInterface
    {
        return $this->createdTime;
    }
}
