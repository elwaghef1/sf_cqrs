<?php

namespace App\Application\Handler;

use App\Application\Query\PostQuery;
use App\Domain\Model\Post;
use App\Domain\Repository\PostRepositoryInterface;

class PostQueryHandler
{
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function handle(PostQuery $query): ?Post
    {
        return $this->postRepository->findById($query->getId());
    }
}
