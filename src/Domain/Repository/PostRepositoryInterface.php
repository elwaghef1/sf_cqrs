<?php

namespace App\Domain\Repository;

use App\Domain\Model\Post;

interface PostRepositoryInterface
{
    public function save(Post $post): void;

    public function findById(int $id): ?Post;

    public function findAll(): array;

    public function delete(Post $post): void;
}
