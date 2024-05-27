<?php

namespace App\Application\Handler;

use App\Application\Command\CreatePostCommand;
use App\Domain\Model\Post;
use App\Infrastructure\Persistence\Doctrine\Repository\PostRepository;

class CreatePostHandler
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function handle(CreatePostCommand $command): void
    {
        $post = new Post($command->title, $command->content, new \DateTimeImmutable(),  $command->user);
        $this->postRepository->save($post);
    }
}
