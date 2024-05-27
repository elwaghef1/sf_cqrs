<?php

namespace App\Application\Handler;

use App\Application\Command\UpdatePostCommand;
use App\Domain\Repository\PostRepositoryInterface;
use App\Application\Exception\NotFoundException;

class UpdatePostHandler
{
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function handle(UpdatePostCommand $command): void
    {
        $post = $this->postRepository->findById($command->id);

        if (!$post) {
            throw new NotFoundException('Post not found');
        }

        $post->setTitle($command->title);
        $post->setContent($command->content);

        $this->postRepository->save($post);
    }
}
