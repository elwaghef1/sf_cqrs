<?php

namespace App\Application\Command;

use App\Domain\Model\User;

class CreatePostCommand
{
    public string $title;
    public string $content;
    public \DateTimeInterface $createdAt;
    public User $user;

    public function __construct(
        string $title,
        string $content,
        \DateTimeInterface $createdAt,
        User $user
    )
    {
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->user = $user;
    }
}
