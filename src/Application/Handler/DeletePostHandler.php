<?php


namespace App\Application\Handler;

use App\Application\Command\DeletePostCommand;
use App\Domain\Repository\PostRepositoryInterface;
use App\Application\Exception\NotFoundException;

class DeletePostHandler
{
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function handle(DeletePostCommand $command): void
    {
        $post = $this->postRepository->findById($command->id);

        if (!$post) {
            throw new NotFoundException('Post not found');
        }

        $this->postRepository->delete($post);
    }
}
